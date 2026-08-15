<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        $product = $this->route('product');

        return $this->user() && $this->user()->isVendor() && $this->user()->isApproved() && $product && $product->user_id === $this->user()->id;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:150', 'regex:/^[A-Za-z0-9\s\,\-\&\.\'\(\)]+$/'],
            'category_id' => ['required', 'exists:categories,id'],
            'description' => ['required', 'string', 'min:10', 'max:5000'],
            'status' => [
                'required',
                $this->route('product') && $this->route('product')->status === 'draft'
                    ? 'in:draft,active'
                    : 'in:active,inactive',
            ],
            'is_featured' => ['nullable', 'boolean'],
            'images' => ['nullable', 'array', 'max:5'],
            'images.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.min' => 'Product name must be at least 3 characters long.',
            'name.regex' => 'Product name contains invalid characters. Only letters, numbers, spaces, and standard punctuation are allowed.',
            'description.required' => 'Please provide a detailed product description.',
            'description.min' => 'Product description must be at least 10 characters long.',
            'status.in' => 'The selected status transition is invalid for this product.',
            'images.max' => 'You can upload a maximum of 5 images per product.',
            'images.*.max' => 'Each product image must not exceed 5MB.',
            'images.*.image' => 'Uploaded file must be a valid image (JPG, PNG, WEBP, SVG).',
        ];
    }
}
