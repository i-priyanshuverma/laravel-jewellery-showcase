<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductVariantRequest extends FormRequest
{
    public function authorize(): bool
    {
        $product = $this->route('product');

        return $this->user() && $this->user()->isVendor() && $this->user()->isApproved() && $product && $product->user_id === $this->user()->id;
    }

    public function rules(): array
    {
        $vendorId = $this->user()->id;

        return [
            'sku' => [
                'required',
                'string',
                'max:100',
                'regex:/^[A-Za-z0-9\-\_]+$/',
                Rule::unique('product_variants', 'sku')->where(function ($query) use ($vendorId) {
                    return $query->whereIn('product_id', function ($subQuery) use ($vendorId) {
                        $subQuery->select('id')->from('products')->where('user_id', $vendorId)->whereNull('deleted_at');
                    });
                }),
            ],
            'price' => ['required', 'numeric', 'gt:0', 'max:99999999.99'],
            'stock' => ['required', 'integer', 'min:0', 'max:100000'],
            'metal' => ['nullable', 'string', 'exists:metals,name'],
            'purity' => ['nullable', 'string', 'exists:purities,value'],
            'colour' => ['nullable', 'string', 'exists:colours,name'],
            'size' => ['nullable', 'string', 'exists:jewellery_sizes,value'],
            'weight' => ['nullable', 'numeric', 'gt:0', 'max:10000.00'],
            'status' => ['required', 'in:active,inactive'],
            'stones' => ['nullable', 'array'],
            'stones.*.stone_type_id' => ['nullable', 'exists:stone_types,id'],
            'stones.*.carat_weight' => ['nullable', 'numeric', 'min:0.001', 'max:1000.00'],
            'stones.*.clarity' => ['nullable', 'string', 'max:50'],
            'stones.*.setting_type' => ['nullable', 'string', 'max:50'],
        ];
    }

    public function messages(): array
    {
        return [
            'sku.regex' => 'SKU may only contain letters, numbers, hyphens, and underscores.',
            'sku.unique' => 'You already have a product variant registered with this SKU.',
            'price.gt' => 'Price must be greater than ₹0.00.',
            'weight.gt' => 'Weight must be greater than 0 grams.',
            'metal.exists' => 'Selected metal is invalid.',
            'purity.exists' => 'Selected purity is invalid.',
            'colour.exists' => 'Selected colour is invalid.',
            'size.exists' => 'Selected size is invalid.',
            'stones.*.stone_type_id.exists' => 'Selected stone type is invalid.',
        ];
    }
}
