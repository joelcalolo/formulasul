<?php

namespace App\Events;

use App\Models\Transfer;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TransferStatusChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $transfer;
    public $action;
    public $adminName;

    /**
     * Create a new event instance.
     */
    public function __construct(Transfer $transfer, $action, $adminName = null)
    {
        $this->transfer = $transfer;
        $this->action = $action;
        $this->adminName = $adminName;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('transfers'),
            new PrivateChannel('admin.transfers'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'transfer.status.changed';
    }

    public function broadcastWith(): array
    {
        return [
            'transfer_id' => $this->transfer->id,
            'status' => $this->transfer->status,
            'action' => $this->action,
            'admin_name' => $this->adminName,
            'timestamp' => now()->toISOString(),
            'user_id' => $this->transfer->user_id,
        ];
    }
}
