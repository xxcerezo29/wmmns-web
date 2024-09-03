<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('{barangay}-track-garbage-truck', function ($user, $barangay) {
    // Example authorization: only allow users associated with the barangay
    return $user->barangay === $barangay;
});