<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\StockHistory;
use App\Models\Product;
use App\Models\User;
use App\Models\Transaction;
use App\Models\PurchaseOrder;
use Illuminate\Database\Seeder;

class StockHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();
        $users = User::all();
        $transactions = Transaction::where('payment_status', 'paid')->get();
        $purchaseOrders = PurchaseOrder::where('status', 'received')->get();

        // Stock history dari transaksi penjualan
        foreach ($transactions as $transaction) {
            $transactionDetails = $transaction->transactionDetails;

            if ($transactionDetails) {
                foreach ($transactionDetails as $detail) {
                    $product = $detail->product;
                    $oldStock = $product->stock + $detail->quantity; // Kembalikan ke stok sebelum penjualan

                    StockHistory::create([
                        'product_id' => $product->id,
                        'user_id' => $transaction->user_id,
                        'transaction_id' => $transaction->id,
                        'type' => 'out',
                        'quantity' => $detail->quantity,
                        'old_stock' => $oldStock,
                        'new_stock' => $product->stock,
                        'note' => 'Penjualan - ' . $transaction->transaction_code,
                        'created_at' => $transaction->created_at,
                    ]);
                }
            }
        }

        // Stock history dari purchase order
        foreach ($purchaseOrders as $purchaseOrder) {
            $poItems = $purchaseOrder->items;

            if ($poItems) {
                foreach ($poItems as $item) {
                    if ($item->received_quantity > 0) {
                        $product = $item->product;
                        $oldStock = $product->stock - $item->received_quantity; // Stok sebelum penerimaan

                        StockHistory::create([
                            'product_id' => $product->id,
                            'user_id' => $purchaseOrder->user_id,
                            'transaction_id' => null,
                            'type' => 'in',
                            'quantity' => $item->received_quantity,
                            'old_stock' => $oldStock,
                            'new_stock' => $product->stock,
                            'note' => 'Pembelian - ' . $purchaseOrder->po_number,
                            'created_at' => $purchaseOrder->order_date,
                        ]);
                    }
                }
            }
        }

        // Stock adjustment manual
        foreach ($products->random(20) as $product) {
            $adjustmentType = rand(0, 1) ? 'in' : 'out';
            $quantity = rand(1, 10);
            $oldStock = $product->stock;

            $newStock = match($adjustmentType) {
                'in' => $oldStock + $quantity,
                'out' => max(0, $oldStock - $quantity)
            };

            StockHistory::create([
                'product_id' => $product->id,
                'user_id' => $users->random()->id,
                'transaction_id' => null,
                'type' => 'adjustment',
                'quantity' => $quantity,
                'old_stock' => $oldStock,
                'new_stock' => $newStock,
                'note' => 'Penyesuaian stok fisik',
                'created_at' => now()->subDays(rand(1, 60)),
            ]);
        }
    }
}
