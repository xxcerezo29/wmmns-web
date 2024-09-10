<?php

namespace App\Console\Commands;

use App\Models\CollectionSchedule;
use App\Models\Resident;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class NotifyUserSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:notify-user-schedule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notifications to users about today\'s garbage collection schedule';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::now()->format('l');
        $schedules = CollectionSchedule::where('day', $today)->get();

        foreach ($schedules as $schedule) {
            $barangay = $schedule->barangay;

            $residents = Resident::where('barangay', $barangay)->get();

            foreach ($residents as $resident) {
                $deviceTokens = $resident->devices->pluck('token');

                foreach ($deviceTokens as $token) {
                    // Send notification for today's schedule
                    $this->sendNotification($token, 'Garbage Collection Schedule', 'Garbage collection is scheduled for today in your area.');
                }
            }
        }

        $this->info('Users notified about today\'s schedule.');
    }

    private function sendNotification($token, $title, $body)
    {
        $firebase = app('firebase.messaging');
        $message = CloudMessage::withTarget('token', $token)
            ->withNotification(Notification::create($title, $body));

        try {
            $firebase->send($message);
            return ['success' => true];
        } catch (\Exception $e) {
            return ['success' => true, 'error' => $e->getMessage()];
            Log::error('Error sending FCM notification: ' . $e->getMessage());
        }
    }
}
