<?php

namespace Database\Factories;

use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vendor>
 */
class VendorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'team_id' => Team::factory(),
            'legal_name' => $this->faker->company(),
            'vat_number' => $this->faker->regexify('[ABG][0-9]{8}'),
            'risk_score' => $this->faker->numberBetween(0, 40),
        ];
    }
}
