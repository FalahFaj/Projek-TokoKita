<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    /**
     * Display POS interface
     */
    public function index()
    {
        $products = Product::with('category')
            ->where('is_active', true)
            ->where('is_available', true)
            ->where('stock', '>', 0)
            ->get();

        $categories = Category::where('is_active', true)->get();
        $user = auth()->user();

        return view('pos.index', compact('products', 'categories', 'user'));
    }

    /**
     * Process POS transaction
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'items' => 'required|array|min:1',
                'items.*.id' => 'required|exists:products,id',
                'items.*.quantity' => 'required|integer|min:1',
                'total_amount' => 'required|numeric|min:0',
                'payment_method' => 'required|in:cash,transfer,debit,credit',
                'cash_amount' => 'nullable|numeric|min:0',
            ]);

            // Check stock availability
            foreach ($request->items as $item) {
                $product = Product::find($item['id']);
                if ($product->stock < $item['quantity']) {
                    return response()->json([
                        'success' => false,
                        'message' => "Stok {$product->name} tidak mencukupi. Stok tersedia: {$product->stock}"
                    ], 422);
                }
            }

            // Calculate amounts
            $subtotal = $request->total_amount;
            $taxAmount = 0;
            $discountAmount = 0;
            $totalAmount = $subtotal - $discountAmount + $taxAmount;
            $paidAmount = $request->cash_amount ?? $totalAmount;
            $changeAmount = $paidAmount - $totalAmount;

            // Create transaction
            $transaction = Transaction::create([
                'transaction_code' => Transaction::generateTransactionCode(),
                'user_id' => auth()->id(),
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'discount_amount' => $discountAmount,
                'total_amount' => $totalAmount,
                'paid_amount' => $paidAmount,
                'change_amount' => $changeAmount,
                'payment_method' => $request->payment_method,
                'payment_status' => 'paid',
                'status' => 'completed',
                'customer_name' => $request->customer_name ?? 'Pelanggan Umum',
            ]);

            // Create transaction details and update stock
            foreach ($request->items as $item) {
                $product = Product::find($item['id']);

                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku ?? '', 
                    'unit_price' => $product->selling_price,
                    'quantity' => $item['quantity'],
                    'subtotal' => $product->selling_price * $item['quantity'],
                ]);

            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil diproses',
                'transaction' => $transaction,
                'receipt_url' => route('pos.receipt', $transaction->id)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search products for POS
     */
    public function searchProducts(Request $request)
    {
        $products = Product::with('category')
            ->where('is_active', true)
            ->where('is_available', true)
            ->where(function($query) use ($request) {
                $query->where('name', 'like', "%{$request->term}%")
                      ->orWhere('sku', 'like', "%{$request->term}%")
                      ->orWhere('barcode', 'like', "%{$request->term}%");
            })
            ->where('stock', '>', 0)
            ->select('id', 'name', 'sku', 'selling_price', 'stock', 'unit', 'image', 'category_id')
            ->limit(10)
            ->get();

        return response()->json($products);
    }

    /**
     * Get product details
     */
    public function getProduct($id)
    {
        $product = Product::with('category')
            ->where('is_active', true)
            ->where('is_available', true)
            ->find($id);

        if (!$product) {
            return response()->json(['error' => 'Produk tidak ditemukan'], 404);
        }

        return response()->json($product);
    }

    /**
     * Display receipt
     */
    public function receipt($id)
    {
        $transaction = Transaction::with(['detailTransaksi.product', 'user'])
            ->findOrFail($id);

        return view('pos.receipt', compact('transaction'));
    }

    /**
     * Get today's transactions
     */
    public function todayTransactions()
    {
        $transactions = Transaction::with(['detailTransaksi'])
            ->whereDate('created_at', today())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($transactions);
    }
}
