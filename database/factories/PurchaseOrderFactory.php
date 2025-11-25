<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PurchaseOrder>
 */
class PurchaseOrderFactory extends Factory
{
    protected $model = PurchaseOrder::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = ['draft', 'ordered', 'received', 'cancelled'];
        return [
            'po_number' => 'PO-' . now()->format('Ymd') . '-' . $this->faker->unique()->numberBetween(1000, 9999),
            'supplier_id' => Supplier::factory(),
            'user_id' => User::factory(),
            'order_date' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'expected_date' => $this->faker->dateTimeBetween('now', '+14 days'),
            'status' => $this->faker->randomElement($statuses),
            'total_amount' => $this->faker->numberBetween(100000, 5000000),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
        ]);
    }

    public function ordered(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'ordered',
        ]);
    }

    public function received(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'received',
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled',
        ]);
    }
}
