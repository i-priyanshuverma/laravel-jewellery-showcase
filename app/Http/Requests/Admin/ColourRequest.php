<?php

namespace App\Http\Requests\Admin;

use App\Models\Colour;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ColourRequest extends FormRequest
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
        /** @var Colour|null $colour */
        $colour = $this->route('colour');
        $colourId = $colour instanceof Colour ? $colour->id : $colour;

        return [
            'name' => [
                'required',
                'string',
                'max:100',
                $colourId ? Rule::unique('colours', 'name')->ignore($colourId) : 'unique:colours,name',
            ],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'in:active,inactive'],
        ];
    }
}
