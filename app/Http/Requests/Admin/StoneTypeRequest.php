<?php

namespace App\Http\Requests\Admin;

use App\Models\StoneType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoneTypeRequest extends FormRequest
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
        /** @var StoneType|null $stoneType */
        $stoneType = $this->route('stone_type');
        $stoneTypeId = $stoneType instanceof StoneType ? $stoneType->id : $stoneType;

        return [
            'name' => [
                'required',
                'string',
                'max:100',
                $stoneTypeId ? Rule::unique('stone_types', 'name')->ignore($stoneTypeId) : 'unique:stone_types,name',
            ],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'in:active,inactive'],
        ];
    }
}
