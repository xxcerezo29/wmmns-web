<?php

namespace App\Repositories;

use Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\Report;
use Carbon\Carbon;

class ReportRepository
{
    /**
     * Get the current total complaints count.
     *
     * @return int
     */
    public function getCurrentCount()
    {
        if (Auth::user()->hasRole('admin')) {
            return Report::count();
        } else {
            return Report::where('barangay', Auth::user()->barangay)->count();
        }
    }

    /**
     * Get the complaints count by report type.
     *
     * @param string $reportType
     * @return int
     */
    public function getCountByStatus($status)
    {
        if (Auth::user()->hasRole('admin')) {
            return Report::where('status', $status)->count();
        } else {
            return Report::where('barangay', Auth::user()->barangay)->where('status', $status)->count();
        }
    }

    /**
     * Get the complaints count from a previous day, week, or month by report type.
     *
     * @param string $reportType
     * @param string $period ('day', 'week', 'month')
     * @return int
     */
    public function getPreviousCountByStatus($status, $period = 'day')
    {
        switch ($period) {
            case 'week':
                if (Auth::user()->hasRole('admin')) {
                    return Report::where('status', $status)
                        ->whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])
                        ->count();
                } else {
                    return Report::where('barangay', Auth::user()->barangay)->where('status', $status)
                        ->whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])
                        ->count();
                }
            case 'month':
                if (Auth::user()->hasRole('admin')) {
                    return Report::where('status', $status)
                        ->whereMonth('created_at', Carbon::now()->subMonth()->month)
                        ->count();
                } else {
                    return Report::where('barangay', Auth::user()->barangay)->where('status', $status)
                        ->whereMonth('created_at', Carbon::now()->subMonth()->month)
                        ->count();
                }
            default: // 'day'
                if (Auth::user()->hasRole('admin')) {
                    return Report::where('status', $status)
                        ->whereDate('created_at', Carbon::now()->subDay())
                        ->count();
                } else {
                    return Report::where('barangay', Auth::user()->barangay)
                        ->where('status', $status)
                        ->whereDate('created_at', Carbon::now()->subDay())
                        ->count();
                }
        }
    }

    public function getPreviousCount($period = 'day')
    {
        switch ($period) {
            case 'week':
                if (Auth::user()->hasRole('admin')) {
                    return Report::whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])
                        ->count();
                } else {
                    return Report::where('barangay', Auth::user()->barangay)->whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])
                        ->count();
                }
            case 'month':
                if (Auth::user()->hasRole('admin')) {
                    return Report::whereMonth('created_at', Carbon::now()->subMonth()->month)
                        ->count();
                } else {
                    return Report::where('barangay', Auth::user()->barangay)->whereMonth('created_at', Carbon::now()->subMonth()->month)
                        ->count();
                }
            default: // 'day'
                return Report::whereDate('created_at', Carbon::now()->subDay())
                    ->count();
        }
    }

    /**
     * Get the complaints count change description by report type.
     *
     * @param string $status
     * @param string $period ('day', 'week', 'month')
     * @return array
     */
    public function getComplaintsSummaryByType($status, $period = 'day')
    {
        $currentCount = $this->getCountByStatus($status);
        $previousCount = $this->getPreviousCountByStatus($status, $period);

        $change = $currentCount - $previousCount;

        if ($previousCount > 0) {
            $percentChange = round(($change / $previousCount) * 100, 2);
        } else {
            $percentChange = $change > 0 ? 100 : 0; // If there were no previous records
        }

        $direction = $change > 0 ? '↗︎' : ($change < 0 ? '↘︎' : '→');

        return $formattedSummary = [
            'label' => ucfirst($status) . ' Complaints',
            'value' => number_format($currentCount),
            'change' => $change,
            'percentChange' => $percentChange,
            'direction' => $direction,
            'description' => "{$direction} " . abs($change) . "({$percentChange}%)",
        ];
    }
    public function getComplaintsSummary($period = 'day')
    {
        $currentCount = $this->getCurrentCount();
        $previousCount = $this->getPreviousCount($period);

        $change = $currentCount - $previousCount;

        if ($previousCount > 0) {
            $percentChange = round(($change / $previousCount) * 100, 2);
        } else {
            $percentChange = $change > 0 ? 100 : 0; // If there were no previous records
        }

        $direction = $change > 0 ? '↗︎' : ($change < 0 ? '↘︎' : '→');

        $formattedSummary = [
            'label' => 'Complaints',
            'value' => number_format($currentCount),
            'change' => $change,
            'percentChange' => $percentChange,
            'direction' => $direction,
            'description' => "{$direction} " . abs($change) . "({$percentChange}%)",
        ];

        return $formattedSummary;
    }

    /**
     * Cache the current count by report type for future comparisons.
     *
     * @param string $status
     * @param int $duration Cache duration in minutes.
     */
    public function cacheCurrentCountByType($status, $duration = 1440)
    {
        $currentCount = $this->getCountByStatus($status);
        Cache::put("complaints_count_{$status}", $currentCount, now()->addMinutes($duration));
    }

    /**
     * Get the cached complaints count by report type.
     *
     * @param string $status
     * @return int
     */
    public function getCachedCountByType($status)
    {
        return Cache::get("complaints_count_{$status}", 0);
    }

    /**
     * Get the reports count for each day or month for the chart, including resolved and unresolved statuses.
     *
     * @param string $period ('day', 'month')
     * @param int $range Number of days or months to include (e.g., 7 for days, 12 for months)
     * @return array
     */
    public function getReportsChartData($period = 'day', $range = 7)
    {
        $labels = [];
        $resolvedData = [];
        $unresolvedData = [];

        // Define date ranges based on the period
        if ($period === 'day') {
            // For daily reports (last $range days)
            for ($i = $range - 1; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $labels[] = $date->format('d M Y'); // e.g., "12 Sep 2024"

                // Get the count for resolved reports (where resolved_at is on the given date)
                if (Auth::user()->hasRole('admin')) {
                    $resolvedData[] = Report::whereDate('resolved_at', $date->format('Y-m-d'))
                        ->count();


                    // Get the count for unresolved reports (where resolved_at is null and created_at is the same day)
                    $unresolvedData[] = Report::whereDate('created_at', $date->format('Y-m-d'))
                        ->whereNull('resolved_at')
                        ->count();
                } else {
                    $resolvedData[] = Report::where('barangay', Auth::user()->barangay)->whereDate('resolved_at', $date->format('Y-m-d'))
                        ->count();


                    // Get the count for unresolved reports (where resolved_at is null and created_at is the same day)
                    $unresolvedData[] = Report::where('barangay', Auth::user()->barangay)->whereDate('created_at', $date->format('Y-m-d'))
                        ->whereNull('resolved_at')
                        ->count();
                }

            }
        } elseif ($period === 'month') {
            // For monthly reports (last $range months)
            for ($i = $range - 1; $i >= 0; $i--) {
                $month = Carbon::now()->subMonths($i);
                $labels[] = $month->format('F Y'); // e.g., "September 2024"

                if (Auth::user()->hasRole('admin')) {
                    // Get the count for resolved reports (where resolved_at is in the given month)
                    $resolvedData[] = Report::where('barangay', Auth::user()->barangay)
                        ->whereYear('resolved_at', $month->year)
                        ->whereMonth('resolved_at', $month->month)
                        ->count();

                    // Get the count for unresolved reports (where resolved_at is null and created_at is in the same month)
                    $unresolvedData[] = Report::where('barangay', Auth::user()->barangay)
                        ->whereYear('created_at', $month->year)
                        ->whereMonth('created_at', $month->month)
                        ->whereNull('resolved_at')
                        ->count();
                } else {
                    $resolvedData[] = Report::where('barangay', Auth::user()->barangay)
                        ->whereYear('resolved_at', $month->year)
                        ->whereMonth('resolved_at', $month->month)
                        ->count();

                    // Get the count for unresolved reports (where resolved_at is null and created_at is in the same month)
                    $unresolvedData[] = Report::where('barangay', Auth::user()->barangay)
                        ->whereYear('created_at', $month->year)
                        ->whereMonth('created_at', $month->month)
                        ->whereNull('resolved_at')
                        ->count();
                }
            }
        }

        // Return the data in the format expected by Chart.js
        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Resolved Reports',
                    'data' => $resolvedData,
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'borderWidth' => 1,
                ],
                [
                    'label' => 'Unresolved Reports',
                    'data' => $unresolvedData,
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'borderWidth' => 1,
                ],
            ]
        ];
    }
}
