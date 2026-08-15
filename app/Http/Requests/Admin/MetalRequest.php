<?php

namespace App\Http\Requests\Admin;

use App\Models\Metal;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MetalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        /** @var Metal|null $metal */
        $metal = $this->route('metal');
        $metalId = $metal instanceof Metal ? $metal->id : $metal;

        return [
            'name' => [
                'required',
                'string',
                'max:100',
                $metalId ? Rule::unique('metals', 'name')->ignore($metalId) : 'unique:metals,name',
            ],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'in:active,inactive'],
            'purities' => ['nullable', 'array'],
            'purities.*.id' => ['nullable', 'integer'],
            'purities.*.name' => ['required_with:purities', 'string', 'max:100'],
            'purities.*.value' => ['required_with:purities', 'string', 'max:50'],
            'purities.*.sort_order' => ['nullable', 'integer', 'min:0'],
            'purities.*.status' => ['nullable', 'in:active,inactive'],
        ];
    }
}
