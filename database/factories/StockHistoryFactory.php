<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\StockHistory;
use App\Models\Product;
use App\Models\User;
use App\Models\Transaction;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StockHistory>
 */
class StockHistoryFactory extends Factory
{
    protected $model = StockHistory::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = ['in', 'out', 'adjustment'];
        $quantity = $this->faker->numberBetween(1, 50);
        $oldStock = $this->faker->numberBetween(0, 100);

        $newStock = match($this->faker->randomElement($types)) {
            'in' => $oldStock + $quantity,
            'out' => max(0, $oldStock - $quantity),
            'adjustment' => $this->faker->numberBetween(0, 200)
        };

        return [
            'product_id' => Product::factory(),
            'user_id' => User::factory(),
            'transaction_id' => $this->faker->optional()->passthrough(Transaction::factory()),
            'type' => $this->faker->randomElement($types),
            'quantity' => $quantity,
            'old_stock' => $oldStock,
            'new_stock' => $newStock,
            'note' => $this->faker->sentence(),
        ];
    }

    public function stockIn(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'in',
            'quantity' => $this->faker->numberBetween(10, 100),
            'note' => 'Penambahan stok dari supplier',
        ]);
    }

    public function stockOut(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'out',
            'quantity' => $this->faker->numberBetween(1, 20),
            'note' => 'Penjualan kepada customer',
        ]);
    }

    public function adjustment(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'adjustment',
            'note' => 'Penyesuaian stok fisik',
        ]);
    }
}
