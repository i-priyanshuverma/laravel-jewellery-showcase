<?php

namespace Database\Factories;

use App\Models\CsvImport;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CsvImportFactory extends Factory
{
    protected $model = CsvImport::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'filename' => 'products_import.csv',
            'path' => 'csv-imports/sample.csv',
            'total_rows' => 10,
            'processed_rows' => 10,
            'successful_rows' => 9,
            'failed_rows' => 1,
            'status' => 'completed',
        ];
    }
}
