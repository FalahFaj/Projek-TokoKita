<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;

class PurchaseOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = Supplier::where('is_active', true)->get();
        $adminUsers = User::whereIn('role', ['owner', 'admin'])->get();
        $products = Product::all();

        // Purchase Order dengan status berbeda
        PurchaseOrder::factory(5)->draft()->create([
            'supplier_id' => function() use ($suppliers) {
                return $suppliers->random();
            },
            'user_id' => function() use ($adminUsers) {
                return $adminUsers->random();
            },
        ]);

        PurchaseOrder::factory(8)->ordered()->create([
            'supplier_id' => function() use ($suppliers) {
                return $suppliers->random();
            },
            'user_id' => function() use ($adminUsers) {
                return $adminUsers->random();
            },
        ]);

        PurchaseOrder::factory(12)->received()->create([
            'supplier_id' => function() use ($suppliers) {
                return $suppliers->random();
            },
            'user_id' => function() use ($adminUsers) {
                return $adminUsers->random();
            },
        ]);

        PurchaseOrder::factory(2)->cancelled()->create([
            'supplier_id' => function() use ($suppliers) {
                return $suppliers->random();
            },
            'user_id' => function() use ($adminUsers) {
                return $adminUsers->random();
            },
        ]);

        // Buat Purchase Order Items untuk setiap PO
        $purchaseOrders = PurchaseOrder::all();

        foreach ($purchaseOrders as $purchaseOrder) {
            $poProducts = $products->random(rand(3, 8));
            $totalAmount = 0;

            foreach ($poProducts as $product) {
                $quantity = rand(10, 50);
                $unitPrice = $product->purchase_price * (1 - rand(5, 15) / 100); // Diskon dari supplier
                $subtotal = $quantity * $unitPrice;

                $receivedQuantity = match($purchaseOrder->status) {
                    'received' => $quantity,
                    'ordered' => rand(0, $quantity),
                    default => 0
                };

                PurchaseOrderItem::create([
                    'purchase_order_id' => $purchaseOrder->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'received_quantity' => $receivedQuantity,
                    'unit_price' => $unitPrice,
                    'subtotal' => $subtotal,
                ]);

                $totalAmount += $subtotal;
            }

            // Update total amount PO
            $purchaseOrder->update(['total_amount' => $totalAmount]);
        }
    }
}
