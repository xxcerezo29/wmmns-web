<?php

namespace App\Observers\V1;

use App\Models\CollectionSchedule;
use App\Models\Resident;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Log;

class ScheduleObserver
{
    /**
     * Handle the CollectionSchedule "created" event.
     */
    public function created(CollectionSchedule $collectionSchedule): void
    {

        $residents = Resident::where('barangay', $collectionSchedule->barangay)->get();

        foreach ($residents as $resident) {
            $deviceTokens = $resident->devices->pluck('token');

            foreach ($deviceTokens as $token) {
                $firebase = app('firebase.messaging');
                $message = CloudMessage::withTarget('token', $token)
                    ->withNotification(Notification::create(
                        'WMMNS Notification',
                        'A New Collection Schedule Created.'
                    ));

                try {
                    $firebase->send($message);
                    Log::info('FCM notification: Sent');
                } catch (\Exception $e) {
                    Log::error('Error sending FCM notification: ' . $e->getMessage());
                }
            }
        }

    }

    /**
     * Handle the CollectionSchedule "updated" event.
     */
    public function updated(CollectionSchedule $collectionSchedule): void
    {
        //
    }

    /**
     * Handle the CollectionSchedule "deleted" event.
     */
    public function deleted(CollectionSchedule $collectionSchedule): void
    {
        //
    }

    /**
     * Handle the CollectionSchedule "restored" event.
     */
    public function restored(CollectionSchedule $collectionSchedule): void
    {
        //
    }

    /**
     * Handle the CollectionSchedule "force deleted" event.
     */
    public function forceDeleted(CollectionSchedule $collectionSchedule): void
    {
        //
    }
}
