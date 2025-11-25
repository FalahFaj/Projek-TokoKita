<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Product;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
         $purchasePrice = $this->faker->numberBetween(5000, 50000);
        $profitMargin = $this->faker->numberBetween(20, 50);
        $sellingPrice = $purchasePrice * (1 + ($profitMargin / 100));

        return [
            'name' => $this->faker->words(3, true),
            'sku' => 'SKU-' . $this->faker->unique()->numberBetween(1000, 9999),
            'barcode' => 'BC-' . $this->faker->unique()->numberBetween(100000, 999999),
            'description' => $this->faker->sentence(),
            'category_id' => Category::factory(),
            'supplier_id' => Supplier::factory(),
            'purchase_price' => $purchasePrice,
            'selling_price' => $sellingPrice,
            'profit_margin' => $profitMargin,
            'stock' => $this->faker->numberBetween(0, 100),
            'min_stock' => 5,
            'max_stock' => 100,
            'unit' => 'pcs',
            'is_active' => true,
            'is_available' => true,
        ];
    }

    public function lowStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock' => $this->faker->numberBetween(1, 5),
        ]);
    }

    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock' => 0,
        ]);
    }

    public function highStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock' => $this->faker->numberBetween(50, 200),
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

}
