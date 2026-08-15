<?php

namespace App\Services;

use App\Enums\ReservationStatus;
use App\Events\ProductStockUpdated;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\StockReservation;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class StockReservationService
{
    public function reserve(ProductVariant $variant, int $quantity, string $sessionId, string $idempotencyKey): StockReservation
    {
        if ($quantity <= 0) {
            throw new InvalidArgumentException('Quantity must be greater than zero.');
        }

        $reservation = DB::transaction(function () use ($variant, $quantity, $sessionId, $idempotencyKey) {
            $existing = StockReservation::where('idempotency_key', $idempotencyKey)->first();
            if ($existing) {
                return $existing;
            }

            $lockedVariant = ProductVariant::where('id', $variant->id)
                ->lockForUpdate()
                ->first();

            if (! $lockedVariant) {
                throw new Exception('Product variant not found.');
            }

            if (! $lockedVariant->isActive()) {
                throw new Exception('This product variant is currently unavailable.');
            }

            $product = $lockedVariant->product;
            if (! ($product instanceof Product) || ! $product->isActive() || $product->trashed()) {
                throw new Exception('This product is currently unavailable.');
            }

            $vendor = $product->vendor;
            if (! ($vendor instanceof User) || ! $vendor->isApprovedVendor()) {
                throw new Exception('This vendor is currently unavailable.');
            }

            $activeVariantReservation = StockReservation::where('session_id', $sessionId)
                ->where('product_variant_id', $lockedVariant->id)
                ->where('status', 'active')
                ->where('expires_at', '>', now())
                ->first();

            if ($activeVariantReservation) {
                throw new Exception('Active hold exists for this variant. Please release or wait for expiry.');
            }

            if ($lockedVariant->stock < $quantity) {
                throw new Exception("Insufficient stock available. Only {$lockedVariant->stock} item(s) left.");
            }

            $lockedVariant->decrement('stock', $quantity);

            return StockReservation::create([
                'product_variant_id' => $lockedVariant->id,
                'session_id' => $sessionId,
                'idempotency_key' => $idempotencyKey,
                'quantity' => $quantity,
                'expires_at' => now()->addMinutes(15),
                'status' => 'active',
            ]);
        });

        rescue(fn () => ProductStockUpdated::dispatch($variant->fresh()), report: false);

        return $reservation;
    }

    public function releaseReservation(StockReservation $reservation): bool
    {
        if ($reservation->status !== ReservationStatus::Active) {
            return false;
        }

        $releasedVariant = null;

        $success = DB::transaction(function () use ($reservation, &$releasedVariant) {
            $lockedReservation = StockReservation::where('id', $reservation->id)
                ->lockForUpdate()
                ->first();

            if (! $lockedReservation || $lockedReservation->status !== ReservationStatus::Active) {
                return false;
            }

            $variant = ProductVariant::where('id', $lockedReservation->product_variant_id)
                ->lockForUpdate()
                ->first();

            if ($variant) {
                $variant->increment('stock', $lockedReservation->quantity);
                $releasedVariant = $variant;
            }

            $lockedReservation->update(['status' => 'released']);

            return true;
        });

        if ($success && $releasedVariant) {
            rescue(fn () => ProductStockUpdated::dispatch($releasedVariant->fresh()), report: false);
        }

        return $success;
    }

    public function releaseForVariant(int $variantId): int
    {
        $activeReservations = StockReservation::where('product_variant_id', $variantId)
            ->where('status', 'active')
            ->get();

        $releasedCount = 0;
        foreach ($activeReservations as $reservation) {
            if ($this->releaseReservation($reservation)) {
                $releasedCount++;
            }
        }

        return $releasedCount;
    }

    public function releaseForProduct(int $productId): int
    {
        $variantIds = ProductVariant::withTrashed()
            ->where('product_id', $productId)
            ->pluck('id');

        $releasedCount = 0;
        foreach ($variantIds as $variantId) {
            $releasedCount += $this->releaseForVariant($variantId);
        }

        return $releasedCount;
    }

    public function releaseForVendor(int $userId): int
    {
        $productIds = Product::withTrashed()
            ->where('user_id', $userId)
            ->pluck('id');

        $releasedCount = 0;
        foreach ($productIds as $productId) {
            $releasedCount += $this->releaseForProduct($productId);
        }

        return $releasedCount;
    }

    public function releaseExpired(): int
    {
        $expiredReservations = StockReservation::where('status', 'active')
            ->where('expires_at', '<=', now())
            ->get();

        $count = 0;
        $updatedVariantIds = [];

        foreach ($expiredReservations as $reservation) {
            DB::transaction(function () use ($reservation, &$count, &$updatedVariantIds) {
                $lockedReservation = StockReservation::where('id', $reservation->id)
                    ->lockForUpdate()
                    ->first();

                if ($lockedReservation && $lockedReservation->status === ReservationStatus::Active) {
                    $variant = ProductVariant::where('id', $lockedReservation->product_variant_id)
                        ->lockForUpdate()
                        ->first();

                    if ($variant) {
                        $variant->increment('stock', $lockedReservation->quantity);
                        $updatedVariantIds[] = $variant->id;
                    }

                    $lockedReservation->update(['status' => 'expired']);
                    $count++;
                }
            });
        }

        $uniqueVariantIds = array_unique($updatedVariantIds);
        foreach ($uniqueVariantIds as $varId) {
            $v = ProductVariant::find($varId);
            if ($v) {
                rescue(fn () => ProductStockUpdated::dispatch($v), report: false);
            }
        }

        return $count;
    }
}
