<?php
namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ResetLocalStorage implements ShouldBroadcast
{
use Dispatchable, InteractsWithSockets;

public function broadcastOn()
{
return new PresenceChannel('reset-channel');
}
}
