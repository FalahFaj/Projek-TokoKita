<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\PurchaseOrderItem;
use App\Models\PurchaseOrder;
use App\Models\Product;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PurchaseOrderItem>
 */
class PurchaseOrderItemFactory extends Factory
{
    protected $model = PurchaseOrderItem::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $quantity = $this->faker->numberBetween(10, 100);
        $unitPrice = $this->faker->numberBetween(5000, 50000);
        $subtotal = $quantity * $unitPrice;
        return [
            'purchase_order_id' => PurchaseOrder::factory(),
            'product_id' => Product::factory(),
            'quantity' => $quantity,
            'received_quantity' => $this->faker->numberBetween(0, $quantity),
            'unit_price' => $unitPrice,
            'subtotal' => $subtotal,
        ];
    }

    public function fullyReceived(): static
    {
        return $this->state(fn (array $attributes) => [
            'received_quantity' => $attributes['quantity'],
        ]);
    }

    public function partiallyReceived(): static
    {
        return $this->state(fn (array $attributes) => [
            'received_quantity' => $this->faker->numberBetween(1, $attributes['quantity'] - 1),
        ]);
    }

    public function notReceived(): static
    {
        return $this->state(fn (array $attributes) => [
            'received_quantity' => 0,
        ]);
    }
}
