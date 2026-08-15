<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\VendorProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

class VendorProfileFactory extends Factory
{
    protected $model = VendorProfile::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'business_name' => $this->faker->company().' Fine Jewellers',
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'description' => $this->faker->paragraph(),
        ];
    }
}
