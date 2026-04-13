<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'team_id' => 1,
            'vendor_id' => \App\Models\Vendor::factory(),
            'invoice_number' => $this->faker->regexify('INV-[0-9]{5}'),
            'issue_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'total_amount' => $this->faker->randomFloat(2, 100, 5000),
            'tax_amount' => $this->faker->randomFloat(2, 20, 1000),
            'description' => $this->faker->sentence(),
            'status' => 'processed',
        ];
    }
}
