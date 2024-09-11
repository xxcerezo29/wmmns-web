<?php

namespace App\Repositories;

use App\Models\Roam;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class RoamRepository
{
    /**
     * Get the current total roams count.
     *
     * @return int
     */
    public function getCurrentCount()
    {
        return Roam::count();
    }

    /**
     * Get the roams count from a previous day, week, or month.
     *
     * @param string $period ('day', 'week', 'month')
     * @return int
     */
    public function getPreviousCount($period = 'day')
    {
        switch ($period) {
            case 'week':
                return Roam::whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])->count();
            case 'month':
                return Roam::whereMonth('created_at', Carbon::now()->subMonth()->month)->count();
            default: // 'day'
                return Roam::whereDate('created_at', Carbon::now()->subDay())->count();
        }
    }

    /**
     * Get the roams count change description.
     *
     * @param string $period ('day', 'week', 'month')
     * @return array
     */
    public function getRoamsSummary($period = 'day')
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
            'label' => 'Roams',
            'value' => $currentCount,
            'description' => $description,
        ];
    }

    /**
     * Cache the current roam count for future comparisons.
     *
     * @param int $duration Cache duration in minutes.
     */
    public function cacheCurrentCount($duration = 1440)
    {
        $currentCount = $this->getCurrentCount();
        Cache::put('roams_count', $currentCount, now()->addMinutes($duration));
    }

    /**
     * Get the cached roam count.
     *
     * @return int
     */
    public function getCachedCount()
    {
        return Cache::get('roams_count', 0);
    }

    /**
     * Get roams by driver ID.
     *
     * @param int $driverId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRoamsByDriver($driverId)
    {
        return Roam::where('driver_id', $driverId)->get();
    }

    /**
     * Get roams by schedule ID.
     *
     * @param int $scheduleId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRoamsBySchedule($scheduleId)
    {
        return Roam::where('schedule_id', $scheduleId)->get();
    }

    /**
     * Get the latest roam record.
     *
     * @return \App\Models\Roam|null
     */
    public function getLatestRoam()
    {
        return Roam::latest('started')->first();
    }
}