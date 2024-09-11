<?php

namespace App\Repositories;

use App\Models\Driver;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class DriverRepository
{
    /**
     * Get the current total drivers count.
     *
     * @return int
     */
    public function getCurrentCount()
    {
        return Driver::count();
    }

    /**
     * Get the drivers count from a previous day, week, or month.
     *
     * @param string $period ('day', 'week', 'month')
     * @return int
     */
    public function getPreviousCount($period = 'day')
    {
        switch ($period) {
            case 'week':
                return Driver::whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])->count();
            case 'month':
                return Driver::whereMonth('created_at', Carbon::now()->subMonth()->month)->count();
            default: // 'day'
                return Driver::whereDate('created_at', Carbon::now()->subDay())->count();
        }
    }

    /**
     * Get the drivers count change description.
     *
     * @param string $period ('day', 'week', 'month')
     * @return array
     */
    public function getDriversSummary($period = 'day')
    {
        $currentCount = $this->getCurrentCount();
        $previousCount = $this->getPreviousCount($period);

        $change = $currentCount - $previousCount;

        if ($change > 0) {
            $description = "Increased by {$change} compared to the previous {$period}.";
        } elseif ($change < 0) {
            $description = "Decreased by " . abs($change) . " compared to the previous {$period}.";
        } else {
            $description = "No change compared to the previous {$period}.";
        }

        return [
            'label' => 'Drivers',
            'value' => $currentCount,
            'description' => $description,
        ];
    }

    /**
     * Cache the current driver count for future comparisons.
     *
     * @param int $duration Cache duration in minutes.
     */
    public function cacheCurrentCount($duration = 1440)
    {
        $currentCount = $this->getCurrentCount();
        Cache::put('drivers_count', $currentCount, now()->addMinutes($duration));
    }

    /**
     * Get the cached driver count.
     *
     * @return int
     */
    public function getCachedCount()
    {
        return Cache::get('drivers_count', 0);
    }

    /**
     * Get drivers by barangay with caching.
     *
     * @param string $barangay
     * @param int $duration Cache duration in minutes.
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getDriversByBarangay($barangay, $duration = 1440)
    {
        $cacheKey = "drivers_by_barangay_{$barangay}";
        return Cache::remember($cacheKey, now()->addMinutes($duration), function () use ($barangay) {
            return Driver::where('barangay', $barangay)->get();
        });
    }
}