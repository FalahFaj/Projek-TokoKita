<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Transaction;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    protected $model = Transaction::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {
        $subtotal = $this->faker->numberBetween(10000, 500000);
        $taxAmount = $subtotal * 0.11; // 11% PPN
        $discountAmount = $this->faker->numberBetween(0, $subtotal * 0.2); // max 20% discount
        $totalAmount = $subtotal + $taxAmount - $discountAmount;

        $paymentMethods = ['cash', 'transfer', 'qris', 'debit_card', 'credit_card'];
        return [
            'transaction_code' => 'TRX-' . now()->format('Ymd') . '-' . $this->faker->unique()->numberBetween(1000, 9999),
            'user_id' => User::factory(),
            'customer_id' => null,
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'discount_amount' => $discountAmount,
            'total_amount' => $totalAmount,
            'paid_amount' => $totalAmount,
            'change_amount' => 0,
            'payment_method' => $this->faker->randomElement($paymentMethods),
            'payment_status' => 'paid',
            'payment_reference' => $this->faker->bothify('REF-#####'),
            'customer_name' => $this->faker->name(),
            'customer_phone' => $this->faker->phoneNumber(),
            'status' => 'completed',
            'notes' => $this->faker->optional()->sentence(),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_status' => 'pending',
            'paid_amount' => 0,
            'status' => 'pending',
        ]);
    }

    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_status' => 'failed',
            'status' => 'cancelled',
        ]);
    }

    public function withCustomer(): static
    {
        return $this->state(fn (array $attributes) => [
            'customer_id' => User::factory(),
        ]);
    }
}
