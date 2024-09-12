<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TrackGarbageTruckWeb
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $location;
    public $barangay;
    public $user;
    public $truck;

    /**
     * Create a new event instance.
     */
    public function __construct($location, $barangay, $user, $truck)
    {
        $this->location = $location;
        $this->barangay = $barangay;
        $this->user = $user;
        $this->truck = $truck;
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('track-garbage-truck'),
        ];
    }

    public function broadcastAs()
    {
        return 'TrackGarbageTruckWeb';
    }
}
