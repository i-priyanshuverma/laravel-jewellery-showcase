<?php

namespace App\Events;

use App\Models\ProductVariant;
use App\Models\StockReservation;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProductStockUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $productId;

    public int $vendorId;

    public int $variantId;

    public int $stock;

    public int $activeHoldsCount;

    public int $vendorActiveHoldsTotal;

    public string $status;

    public function __construct(ProductVariant $variant)
    {
        $variant->loadMissing('product.vendor');

        $this->productId = $variant->product_id;
        $this->vendorId = (int) ($variant->product ? $variant->product->user_id : 0);
        $this->variantId = $variant->id;
        $this->stock = $variant->stock;
        $this->status = is_string($variant->status) ? $variant->status : $variant->status->value;

        $this->activeHoldsCount = (int) $variant->activeReservations()->sum('quantity');

        $this->vendorActiveHoldsTotal = (int) StockReservation::whereHas('variant.product', function ($query) {
            $query->where('user_id', $this->vendorId);
        })
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->sum('quantity');
    }

    public function broadcastOn(): array
    {
        $channels = [
            new Channel('products.'.$this->productId),
        ];

        if ($this->vendorId > 0) {
            $channels[] = new PrivateChannel('vendor.'.$this->vendorId);
        }

        $channels[] = new PrivateChannel('admin.inventory');

        return $channels;
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'ProductStockUpdated';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'productId' => $this->productId,
            'vendorId' => $this->vendorId,
            'variantId' => $this->variantId,
            'stock' => $this->stock,
            'activeHoldsCount' => $this->activeHoldsCount,
            'vendorActiveHoldsTotal' => $this->vendorActiveHoldsTotal,
            'status' => $this->status,
        ];
    }
}
