<?php

namespace App\Repositories;

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
        return Report::count();
    }

    /**
     * Get the complaints count by report type.
     *
     * @param string $reportType
     * @return int
     */
    public function getCountByStatus($status)
    {
        return Report::where('status', $status)->count();
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
                return Report::where('status', $status)
                    ->whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])
                    ->count();
            case 'month':
                return Report::where('status', $status)
                    ->whereMonth('created_at', Carbon::now()->subMonth()->month)
                    ->count();
            default: // 'day'
                return Report::where('status', $status)
                    ->whereDate('created_at', Carbon::now()->subDay())
                    ->count();
        }
    }

    public function getPreviousCount($period = 'day')
    {
        switch ($period) {
            case 'week':
                return Report::whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])
                    ->count();
            case 'month':
                return Report::whereMonth('created_at', Carbon::now()->subMonth()->month)
                    ->count();
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
            'label' => ucfirst($status).' Complaints',
            'value' => number_format($currentCount),
            'change' => $change,
            'percentChange' => $percentChange,
            'direction' => $direction,
            'description' => "{$direction} " . abs($change). "({$percentChange}%)",
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
            'description' => "{$direction} " . abs($change). "({$percentChange}%)",
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
}
