<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        $product = $this->route('product');

        return $this->user() && $this->user()->isVendor() && $this->user()->isApproved() && $product && $product->user_id === $this->user()->id;
    }

    public function rules(): array
    {
        $product = $this->route('product');
        $existingCount = $product ? $product->images()->count() : 0;
        $remainingSlots = max(0, 5 - $existingCount);

        return [
            'images' => [
                'required',
                'array',
                'min:1',
                "max:{$remainingSlots}",
            ],
            'images.*' => ['required', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        $product = $this->route('product');
        $existingCount = $product ? $product->images()->count() : 0;
        $remainingSlots = max(0, 5 - $existingCount);

        return [
            'images.required' => 'Please select at least one image to upload.',
            'images.max' => "A product can have a maximum of 5 images. You currently have {$existingCount} image(s), so you can only upload up to {$remainingSlots} more.",
            'images.*.image' => 'Uploaded file must be an image (JPEG, PNG, JPG, WEBP, SVG).',
            'images.*.max' => 'Each image must not exceed 5MB.',
        ];
    }
}
