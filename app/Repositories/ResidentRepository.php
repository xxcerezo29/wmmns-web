<?php

namespace App\Repositories;

use App\Models\Resident;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class ResidentRepository
{
    /**
     * Get the current total residents count.
     *
     * @return int
     */
    public function getCurrentCount()
    {
        return Resident::count();
    }

    /**
     * Get the residents count from a previous day, week, or month.
     *
     * @param string $period ('day', 'week', 'month')
     * @return int
     */
    public function getPreviousCount($period = 'day')
    {
        switch ($period) {
            case 'week':
                return Resident::whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])->count();
            case 'month':
                return Resident::whereMonth('created_at', Carbon::now()->subMonth()->month)->count();
            default: // 'day'
                return Resident::whereDate('created_at', Carbon::now()->subDay())->count();
        }
    }

    /**
     * Get the residents count change description.
     *
     * @param string $period ('day', 'week', 'month')
     * @return array
     */
    public function getResidentsSummary($period = 'day')
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
            'label' => 'Residents',
            'value' => $currentCount,
            'description' => $description,
        ];
    }

    /**
     * Cache the current resident count for future comparisons.
     *
     * @param int $duration Cache duration in minutes.
     */
    public function cacheCurrentCount($duration = 1440)
    {
        $currentCount = $this->getCurrentCount();
        Cache::put('residents_count', $currentCount, now()->addMinutes($duration));
    }

    /**
     * Get the cached resident count.
     *
     * @return int
     */
    public function getCachedCount()
    {
        return Cache::get('residents_count', 0);
    }

    /**
     * Get residents by barangay with caching.
     *
     * @param string $barangay
     * @param int $duration Cache duration in minutes.
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getResidentsByBarangay($barangay, $duration = 1440)
    {
        $cacheKey = "residents_by_barangay_{$barangay}";
        return Cache::remember($cacheKey, now()->addMinutes($duration), function () use ($barangay) {
            return Resident::where('barangay', $barangay)->get();
        });
    }

    /**
     * Get a resident by ID.
     *
     * @param int $id
     * @return \App\Models\Resident|null
     */
    public function getResidentById($id)
    {
        return Resident::find($id);
    }
}