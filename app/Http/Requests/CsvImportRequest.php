<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CsvImportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isVendor() && $this->user()->isApproved();
    }

    public function rules(): array
    {
        return [
            'csv_file' => ['required', 'file', 'mimes:csv,txt', 'max:20480'], // Max 20MB
        ];
    }
}
