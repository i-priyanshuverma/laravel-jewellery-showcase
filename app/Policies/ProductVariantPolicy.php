<?php

namespace App\Policies;

use App\Models\ProductVariant;
use App\Models\User;

class ProductVariantPolicy
{
    public function view(User $user, ProductVariant $variant): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isVendor() && $variant->product->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->isVendor() && $user->isApproved();
    }

    public function update(User $user, ProductVariant $variant): bool
    {
        return $user->isVendor() && $user->isApproved() && $variant->product->user_id === $user->id;
    }

    public function delete(User $user, ProductVariant $variant): bool
    {
        return $user->isVendor() && $user->isApproved() && $variant->product->user_id === $user->id;
    }
}
