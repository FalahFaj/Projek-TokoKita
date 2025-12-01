<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Category;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isKasir() && request()->is('admin/*')) {
            return redirect()->route('pos.index')
                ->with('error', 'Anda tidak memiliki akses ke halaman admin.');
        }

        // Redirect kasir ke POS
        if ($user->isKasir() && !request()->is('admin/*')) {
            return redirect()->route('pos.index');
        }

        $stats = $this->getDashboardStats();
        $recentTransactions = $this->getRecentTransactions();
        $lowStockProducts = $this->getLowStockProducts();
        $salesChartData = $this->getSalesChartData();

        return view('admin.dashboard', compact(
            'stats',
            'recentTransactions',
            'lowStockProducts',
            'salesChartData',
            'user'
        ));
    }

    private function getDashboardStats()
    {
        return [
            'total_products' => Product::totalCount(),
            'total_categories' => Category::totalCount(),
            'total_suppliers' => Supplier::totalCount(),
            'total_users' => User::totalCount(),

            'today_sales' => Transaction::todaySalesCount(),
            'today_revenue' => Transaction::todayRevenue(),
            'period_revenue' => Transaction::getRevenueForPeriod('30days'), // Default 30 hari
            'all_time_revenue' => Transaction::allRevenue(),

            'low_stock_count' => Product::lowStockCount(),
            'out_of_stock_count' => Product::outOfStockCount(),

            'total_transactions' => Transaction::totalPaidCount(),
        ];
    }

    private function getRecentTransactions($limit = 10)
    {
        return Transaction::with(['user', 'detailTransaksi.product'])
            ->where('payment_status', 'paid')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($transaction) {
                return [
                    'id' => $transaction->id,
                    'code' => $transaction->transaction_code,
                    'customer_name' => $transaction->customer_name ?: 'Walk-in Customer',
                    'cashier_name' => $transaction->user->name,
                    'total_amount' => $transaction->total_amount,
                    'payment_method' => $transaction->payment_method,
                    'created_at' => $transaction->created_at->format('d M Y H:i'),
                    'items_count' => $transaction->detailTransaksi->sum('quantity'),
                ];
            });
    }

    private function getLowStockProducts($limit = 5)
    {
        return Product::with(['category', 'supplier'])
            ->lowStock()
            ->orderBy('stock', 'asc')
            ->limit($limit)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'stock' => $product->stock,
                    'min_stock' => $product->min_stock,
                    'category' => $product->category->name,
                    'status' => $product->stock == 0 ? 'out_of_stock' : 'low_stock',
                ];
            });
    }

    private function getSalesChartData($period = '30days')
    {
        $days = match($period) {
            'today' => 0,
            '7days' => 6,
            '90days' => 89,
            default => 29,
        };
        $startDate = now()->subDays($days)->startOfDay();

        $salesData = Transaction::where('payment_status', 'paid')
            ->where('created_at', '>=', $startDate)
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as transactions_count'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $labels = [];
        $transactions = [];
        $revenues = [];

        for ($i = $days; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $sale = $salesData->firstWhere('date', $date);

            $labels[] = now()->subDays($i)->format('d M');
            $transactions[] = $sale ? $sale->transactions_count : 0;
            $revenues[] = $sale ? (float) $sale->revenue : 0;
        }

        return [
            'labels' => $labels,
            'transactions' => $transactions,
            'revenues' => $revenues,
        ];
    }

    public function getSalesData(Request $request)
    {
        $period = $request->get('period', '30days');

        $chartData = $this->getSalesChartData($period);
        $totalRevenue = Transaction::getRevenueForPeriod($period);

        return response()->json(array_merge($chartData, [
            'total_revenue_formatted' => 'Rp ' . number_format($totalRevenue, 0, ',', '.')
        ]));
    }

    public function getTopProducts()
    {
        $topProducts = DB::table('transaction_details')
            ->join('products', 'transaction_details.product_id', '=', 'products.id')
            ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->where('transactions.payment_status', 'paid')
            ->where('transactions.created_at', '>=', now()->subDays(30))
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

        return response()->json($topProducts);
    }
}
