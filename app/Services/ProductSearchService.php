<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class ProductSearchService
{
    public function search(array $filters = [], int $perPage = 12, bool $publicOnly = true): LengthAwarePaginator
    {
        $query = Product::query()
            ->with([
                'category',
                'vendor.vendorProfile',
                'images',
                'activeVariants.stones.stoneType',
            ]);

        if ($publicOnly) {
            $query->where('products.status', 'active')
                ->whereHas('vendor', function (Builder $q) {
                    $q->where('role', 'vendor')->where('status', 'approved');
                });
        }

        if (! empty($filters['search'])) {
            $search = trim($filters['search']);
            $query->where(function (Builder $q) use ($search) {
                $q->where('products.name', 'like', "%{$search}%")
                    ->orWhereHas('vendor.vendorProfile', function (Builder $vq) use ($search) {
                        $vq->where('business_name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('vendor', function (Builder $uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('variants', function (Builder $sq) use ($search) {
                        $sq->where('sku', 'like', "%{$search}%");
                    });
            });
        }

        if (! empty($filters['category_id'])) {
            $query->where('products.category_id', $filters['category_id']);
        }

        if (! empty($filters['vendor_id'])) {
            $query->where('products.user_id', $filters['vendor_id']);
        }

        if (! $publicOnly && ! empty($filters['status'])) {
            $query->where('products.status', $filters['status']);
        }

        if (
            ! empty($filters['min_price']) ||
            ! empty($filters['max_price']) ||
            ! empty($filters['metal']) ||
            ! empty($filters['purity']) ||
            ! empty($filters['colour']) ||
            ! empty($filters['size']) ||
            ! empty($filters['stone_type']) ||
            isset($filters['in_stock'])
        ) {
            $query->whereHas('variants', function (Builder $vq) use ($filters, $publicOnly) {
                if ($publicOnly) {
                    $vq->where('status', 'active');
                }

                if (! empty($filters['min_price'])) {
                    $vq->where('price', '>=', (float) $filters['min_price']);
                }

                if (! empty($filters['max_price'])) {
                    $vq->where('price', '<=', (float) $filters['max_price']);
                }

                if (! empty($filters['metal'])) {
                    if (is_array($filters['metal'])) {
                        $vq->whereIn('metal', $filters['metal']);
                    } else {
                        $vq->where('metal', $filters['metal']);
                    }
                }

                if (! empty($filters['purity'])) {
                    if (is_array($filters['purity'])) {
                        $vq->whereIn('purity', $filters['purity']);
                    } else {
                        $vq->where('purity', $filters['purity']);
                    }
                }

                if (! empty($filters['colour'])) {
                    if (is_array($filters['colour'])) {
                        $vq->whereIn('colour', $filters['colour']);
                    } else {
                        $vq->where('colour', $filters['colour']);
                    }
                }

                if (! empty($filters['size'])) {
                    if (is_array($filters['size'])) {
                        $vq->whereIn('size', $filters['size']);
                    } else {
                        $vq->where('size', $filters['size']);
                    }
                }

                if (! empty($filters['stone_type'])) {
                    $vq->whereHas('stones', function (Builder $sq) use ($filters) {
                        if (is_array($filters['stone_type'])) {
                            $sq->whereIn('stone_type_id', $filters['stone_type'])
                                ->orWhereHas('stoneType', function (Builder $stq) use ($filters) {
                                    $stq->whereIn('name', $filters['stone_type']);
                                });
                        } else {
                            $sq->where('stone_type_id', $filters['stone_type'])
                                ->orWhereHas('stoneType', function (Builder $stq) use ($filters) {
                                    $stq->where('name', $filters['stone_type']);
                                });
                        }
                    });
                }

                if (isset($filters['in_stock']) && $filters['in_stock'] !== '') {
                    if ($filters['in_stock'] == '1' || $filters['in_stock'] === true || $filters['in_stock'] === 'true') {
                        $vq->where('stock', '>', 0);
                    }
                }
            });
        }

        $sort = $filters['sort'] ?? 'latest';

        switch ($sort) {
            case 'name_asc':
                $query->orderBy('products.name', 'asc');
                break;

            case 'name_desc':
                $query->orderBy('products.name', 'desc');
                break;

            case 'price_asc':
                $query->orderBy(
                    ProductVariant::select('price')
                        ->whereColumn('product_variants.product_id', 'products.id')
                        ->whereNull('product_variants.deleted_at')
                        ->where('product_variants.status', 'active')
                        ->orderBy('price', 'asc')
                        ->limit(1),
                    'asc'
                );
                break;

            case 'price_desc':
                $query->orderBy(
                    ProductVariant::select('price')
                        ->whereColumn('product_variants.product_id', 'products.id')
                        ->whereNull('product_variants.deleted_at')
                        ->where('product_variants.status', 'active')
                        ->orderBy('price', 'desc')
                        ->limit(1),
                    'desc'
                );
                break;

            case 'stock_desc':
                $query->orderBy(
                    ProductVariant::selectRaw('COALESCE(SUM(stock), 0)')
                        ->whereColumn('product_variants.product_id', 'products.id')
                        ->whereNull('product_variants.deleted_at')
                        ->where('product_variants.status', 'active'),
                    'desc'
                );
                break;

            case 'oldest':
                $query->orderBy('products.created_at', 'asc');
                break;

            case 'latest':
            default:
                $query->orderBy('products.created_at', 'desc');
                break;
        }

        return $query->paginate($perPage)->withQueryString();
    }
}
