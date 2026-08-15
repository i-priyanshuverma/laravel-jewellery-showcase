<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VendorStatusUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $vendorId;

    public string $status;

    public string $action;

    public string $message;

    public string $vendorName;

    /**
     * Create a new event instance.
     */
    public function __construct(User $vendor, string $action, string $message)
    {
        $this->vendorId = $vendor->id;
        $this->status = is_string($vendor->status) ? $vendor->status : $vendor->status->value;
        $this->action = $action;
        $this->message = $message;
        $this->vendorName = $vendor->vendorProfile ? $vendor->vendorProfile->business_name : $vendor->name;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('vendor.'.$this->vendorId),
            new PrivateChannel('admin.inventory'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'VendorStatusUpdated';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'vendorId' => $this->vendorId,
            'status' => $this->status,
            'action' => $this->action,
            'message' => $this->message,
            'vendorName' => $this->vendorName,
        ];
    }
}
