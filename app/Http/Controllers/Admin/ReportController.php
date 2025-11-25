<?php
// app/Http/Controllers/Admin/ReportController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Transaksi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function sales(Request $request)
    {
        $dateRange = $this->getDateRange($request);

        $salesData = $this->getSalesData($dateRange);
        $topProducts = $this->getTopProducts($dateRange);
        $salesByPayment = $this->getSalesByPaymentMethod($dateRange);
        $salesByHour = $this->getSalesByHour($dateRange);

        return view('admin.reports.sales', compact(
            'salesData',
            'topProducts',
            'salesByPayment',
            'salesByHour',
            'dateRange'
        ));
    }

    public function products(Request $request)
    {
        $dateRange = $this->getDateRange($request);

        $productStats = $this->getProductStats($dateRange);
        $categoryStats = $this->getCategoryStats($dateRange);
        $supplierStats = $this->getSupplierStats($dateRange);
        $stockAlerts = $this->getStockAlerts();

        return view('admin.reports.products', compact(
            'productStats',
            'categoryStats',
            'supplierStats',
            'stockAlerts',
            'dateRange'
        ));
    }

    public function stock(Request $request)
    {
        $stockStatus = $this->getStockStatus();
        $lowStockProducts = $this->getLowStockProducts();
        $stockValuation = $this->getStockValuation();
        $stockMovement = $this->getStockMovement($request);

        return view('admin.reports.stock', compact(
            'stockStatus',
            'lowStockProducts',
            'stockValuation',
            'stockMovement'
        ));
    }

    private function getDateRange($request)
    {
        $startDate = $request->get('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));

        return [
            'start' => $startDate,
            'end' => $endDate,
            'period' => $request->get('period', '30days')
        ];
    }

    private function getSalesData($dateRange)
    {
        $transactions = Transaction::where('payment_status', 'paid')
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end'] . ' 23:59:59'])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as transactions_count'),
                DB::raw('SUM(total_amount) as revenue'),
                DB::raw('SUM(tax_amount) as tax'),
                DB::raw('SUM(discount_amount) as discount')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'total_transactions' => $transactions->sum('transactions_count'),
            'total_revenue' => $transactions->sum('revenue'),
            'total_tax' => $transactions->sum('tax'),
            'total_discount' => $transactions->sum('discount'),
            'daily_data' => $transactions,
            'average_transaction' => $transactions->count() > 0 ? $transactions->sum('revenue') / $transactions->sum('transactions_count') : 0
        ];
    }

    private function getTopProducts($dateRange)
    {
        return DB::table('transaction_details')
            ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->join('products', 'transaction_details.product_id', '=', 'products.id')
            ->where('transactions.payment_status', 'paid')
            ->whereBetween('transactions.created_at', [$dateRange['start'], $dateRange['end'] . ' 23:59:59'])
            ->select(
                'products.name',
                'products.sku',
                DB::raw('SUM(transaction_details.quantity) as total_sold'),
                DB::raw('SUM(transaction_details.subtotal) as total_revenue')
            )
            ->groupBy('products.id', 'products.name', 'products.sku')
            ->orderBy('total_sold', 'desc')
            ->limit(10)
            ->get();
    }

    private function getSalesByPaymentMethod($dateRange)
    {
        return Transaction::where('payment_status', 'paid')
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end'] . ' 23:59:59'])
            ->select(
                'payment_method',
                DB::raw('COUNT(*) as transactions_count'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->groupBy('payment_method')
            ->get();
    }

    private function getSalesByHour($dateRange)
    {
        return Transaction::where('payment_status', 'paid')
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end'] . ' 23:59:59'])
            ->select(
                DB::raw('EXTRACT(HOUR FROM created_at) as hour'),
                DB::raw('COUNT(*) as transactions_count'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();
    }

    private function getProductStats($dateRange)
    {
        return Product::with(['category', 'supplier'])
            ->select('products.*') // Memastikan semua kolom dari tabel products terpilih
            ->addSelect(DB::raw("SUM(CASE WHEN transactions.payment_status = 'paid' THEN transaction_details.quantity ELSE 0 END) as total_sold"))
            ->addSelect(DB::raw("SUM(CASE WHEN transactions.payment_status = 'paid' THEN transaction_details.subtotal ELSE 0 END) as total_revenue"))
            ->leftJoin('transaction_details', 'products.id', '=', 'transaction_details.product_id')
            ->leftJoin('transactions', function ($join) use ($dateRange) {
                $join->on('transaction_details.transaction_id', '=', 'transactions.id')
                     ->where('transactions.payment_status', 'paid')
                     ->whereBetween('transactions.created_at', [
                         $dateRange['start'] . ' 00:00:00',
                         $dateRange['end'] . ' 23:59:59'
                     ]);
            })
            ->groupBy('products.id', 'products.name', 'products.sku', 'products.stock', 'products.purchase_price', 'products.selling_price', 'products.category_id', 'products.supplier_id', 'products.created_at', 'products.updated_at', 'products.min_stock', 'products.max_stock', 'products.image') // Group by semua kolom product
            ->orderBy('total_sold', 'desc')
            ->limit(20)
            ->get();
    }

    private function getCategoryStats($dateRange)
    {
        return Category::withCount(['products as product_count'])
            ->addSelect(['total_stock_value' => Product::select(DB::raw('SUM(stock * purchase_price)'))
                ->whereColumn('category_id', 'categories.id')
            ])
            ->withCount(['products as sold_count' => function($query) use ($dateRange) {
                $query->select(DB::raw('SUM(transaction_details.quantity)'))
                      ->join('transaction_details', 'products.id', '=', 'transaction_details.product_id')
                      ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
                      ->where('transactions.payment_status', 'paid')
                      ->whereBetween('transactions.created_at', [$dateRange['start'], $dateRange['end'] . ' 23:59:59']);
            }])
            ->get();
    }

    private function getSupplierStats($dateRange)
    {
        return Supplier::withCount(['products as product_count'])
            ->addSelect(['total_stock_value' => Product::select(DB::raw('SUM(stock * purchase_price)'))
                ->whereColumn('supplier_id', 'suppliers.id')
            ])
            ->withCount(['products as sold_count' => function($query) use ($dateRange) {
                $query->select(DB::raw('SUM(transaction_details.quantity)'))
                      ->join('transaction_details', 'products.id', '=', 'transaction_details.product_id')
                      ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
                      ->where('transactions.payment_status', 'paid')
                      ->whereBetween('transactions.created_at', [$dateRange['start'], $dateRange['end'] . ' 23:59:59']);
            }])
            ->get();
    }

    private function getStockAlerts()
    {
        return [
            'low_stock' => Product::lowStock()->count(),
            'out_of_stock' => Product::where('stock', 0)->count(),
            'over_stock' => Product::whereRaw('stock > max_stock')->count()
        ];
    }

    private function getStockStatus()
    {
        return [
            'total_products' => Product::count(),
            'total_stock' => Product::sum('stock'),
            'total_value' => Product::sum(DB::raw('stock * purchase_price')),
            'average_stock' => Product::avg('stock'),
            'products_with_stock' => Product::where('stock', '>', 0)->count()
        ];
    }

    private function getLowStockProducts()
    {
        return Product::with(['category', 'supplier'])
            ->lowStock()
            ->orWhere('stock', 0)
            ->orderBy('stock')
            ->limit(15)
            ->get();
    }

    private function getStockValuation()
    {
        return [
            'purchase_value' => Product::sum(DB::raw('stock * purchase_price')),
            'selling_value' => Product::sum(DB::raw('stock * selling_price')),
            'potential_profit' => Product::sum(DB::raw('stock * (selling_price - purchase_price)'))
        ];
    }

    private function getStockMovement($request)
    {
        $days = $request->get('days', 30);

        return DB::table('stock_histories')
            ->join('products', 'stock_histories.product_id', '=', 'products.id')
            ->where('stock_histories.created_at', '>=', Carbon::now()->subDays($days))
            ->select(
                DB::raw('DATE(stock_histories.created_at) as date'),
                DB::raw("SUM(CASE WHEN type = 'in' THEN quantity ELSE 0 END) as stock_in"),
                DB::raw("SUM(CASE WHEN type = 'out' THEN quantity ELSE 0 END) as stock_out")
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    public function exportSales(Request $request)
    {
        // Untuk export PDF/Excel (bisa dikembangkan nanti)
        return response()->json(['message' => 'Export feature coming soon']);
    }
}
