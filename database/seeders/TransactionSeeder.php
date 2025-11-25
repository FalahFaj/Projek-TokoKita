<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kasirUsers = User::where('role', 'kasir')->get();
        $products = Product::where('is_active', true)->where('is_available', true)->get();

        // Buat transaksi untuk 30 hari terakhir
        for ($i = 0; $i < 100; $i++) {
            $transaction = Transaction::factory()->create([
                'user_id' => $kasirUsers->random(),
                'created_at' => now()->subDays(rand(0, 30))->subHours(rand(0, 23))->subMinutes(rand(0, 59)),
            ]);

            // Buat detail transaksi (1-5 produk per transaksi)
            $transactionProducts = $products->random(rand(1, 5));

            foreach ($transactionProducts as $product) {
                $quantity = rand(1, 3);
                $unitPrice = $product->selling_price;
                $subtotal = $quantity * $unitPrice;

                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'unit_price' => $unitPrice,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal,
                ]);

                // Update subtotal dan total amount transaksi
                $transaction->subtotal += $subtotal;
            }

            // Hitung ulang total amount
            $transaction->tax_amount = $transaction->subtotal * 0.11;
            $transaction->total_amount = $transaction->subtotal + $transaction->tax_amount - $transaction->discount_amount;
            $transaction->paid_amount = $transaction->total_amount;
            $transaction->save();

            // Kurangi stok produk
            foreach ($transactionProducts as $product) {
                $quantity = TransactionDetail::where('transaction_id', $transaction->id)
                    ->where('product_id', $product->id)
                    ->value('quantity');

                $product->decrement('stock', $quantity);
            }
        }

        // Beberapa transaksi pending
        Transaction::factory(5)->pending()->create([
            'user_id' => $kasirUsers->random(),
        ]);

        // Beberapa transaksi failed
        Transaction::factory(3)->failed()->create([
            'user_id' => $kasirUsers->random(),
        ]);
    }
}
