<!-- resources/views/admin/dashboard.blade.php -->
@extends('layouts.app')

@section('title', 'Dashboard - TokoKita')

@section('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    .stat-card {
        transition: transform 0.2s;
    }
    .stat-card:hover {
        transform: translateY(-5px);
    }
    .low-stock-alert {
        border-left: 4px solid #dc3545;
    }
    .out-of-stock {
        border-left: 4px solid #6c757d;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">
                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                </h4>
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-calendar me-1"></i>
                        <span id="periodLabel">30 Hari Terakhir</span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item period-filter" href="#" data-period="7days">7 Hari Terakhir</a></li>
                        <li><a class="dropdown-item period-filter active" href="#" data-period="30days">30 Hari Terakhir</a></li>
                        <li><a class="dropdown-item period-filter" href="#" data-period="90days">90 Hari Terakhir</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card bg-primary text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Total Pendapatan</h5>
                            <h2 class="mb-0">Rp {{ number_format($stats['monthly_revenue'], 0, ',', '.') }}</h2>
                            <small>Bulan Ini</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-money-bill-wave fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card bg-success text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Transaksi Hari Ini</h5>
                            <h2 class="mb-0">{{ $stats['today_sales'] }}</h2>
                            <small>Rp {{ number_format($stats['today_revenue'], 0, ',', '.') }}</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-shopping-cart fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card bg-warning text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Stok Rendah</h5>
                            <h2 class="mb-0">{{ $stats['low_stock_count'] }}</h2>
                            <small>{{ $stats['out_of_stock_count'] }} Habis</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-exclamation-triangle fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card bg-info text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Total Produk</h5>
                            <h2 class="mb-0">{{ $stats['total_products'] }}</h2>
                            <small>{{ $stats['total_categories'] }} Kategori</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-boxes fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts & Tables Row -->
    <div class="row">
        <!-- Sales Chart -->
        <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-line me-2"></i>Grafik Penjualan
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="salesChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="col-xl-4">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history me-2"></i>Transaksi Terbaru
                    </h5>
                    <a href="#" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($recentTransactions as $transaction)
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">{{ $transaction['code'] }}</h6>
                                <small class="text-success">Rp {{ number_format($transaction['total_amount'], 0, ',', '.') }}</small>
                            </div>
                            <p class="mb-1 small">{{ $transaction['customer_name'] }}</p>
                            <div class="d-flex justify-content-between">
                                <small class="text-muted">{{ $transaction['items_count'] }} items</small>
                                <small class="text-muted">{{ $transaction['created_at'] }}</small>
                            </div>
                        </div>
                        @empty
                        <div class="list-group-item text-center text-muted py-4">
                            <i class="fas fa-receipt fa-2x mb-2"></i>
                            <p>Belum ada transaksi</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Low Stock Alert -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>Peringatan Stok Rendah
                    </h5>
                </div>
                <div class="card-body">
                    @if($lowStockProducts->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>SKU</th>
                                    <th>Kategori</th>
                                    <th>Stok</th>
                                    <th>Min. Stok</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lowStockProducts as $product)
                                <tr class="{{ $product['status'] == 'out_of_stock' ? 'table-danger' : 'table-warning' }}">
                                    <td>{{ $product['name'] }}</td>
                                    <td><code>{{ $product['sku'] }}</code></td>
                                    <td>{{ $product['category'] }}</td>
                                    <td>
                                        <span class="badge bg-{{ $product['status'] == 'out_of_stock' ? 'danger' : 'warning' }}">
                                            {{ $product['stock'] }}
                                        </span>
                                    </td>
                                    <td>{{ $product['min_stock'] }}</td>
                                    <td>
                                        @if($product['status'] == 'out_of_stock')
                                            <span class="badge bg-danger">Habis</span>
                                        @else
                                            <span class="badge bg-warning">Rendah</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <h5>Semua Stok Aman</h5>
                        <p class="text-muted">Tidak ada produk dengan stok rendah</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Initialize Sales Chart
    const salesChart = new Chart(
        document.getElementById('salesChart'),
        {
            type: 'line',
            data: {
                labels: @json($salesChartData['labels']),
                datasets: [
                    {
                        label: 'Pendapatan (Rp)',
                        data: @json($salesChartData['revenues']),
                        borderColor: 'rgb(75, 192, 192)',
                        backgroundColor: 'rgba(75, 192, 192, 0.1)',
                        yAxisID: 'y',
                        tension: 0.4
                    },
                    {
                        label: 'Jumlah Transaksi',
                        data: @json($salesChartData['transactions']),
                        borderColor: 'rgb(255, 99, 132)',
                        backgroundColor: 'rgba(255, 99, 132, 0.1)',
                        yAxisID: 'y1',
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Pendapatan (Rp)'
                        },
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Jumlah Transaksi'
                        },
                        grid: {
                            drawOnChartArea: false,
                        },
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label.includes('Pendapatan')) {
                                    return label + ': Rp ' + context.parsed.y.toLocaleString('id-ID');
                                }
                                return label + ': ' + context.parsed.y;
                            }
                        }
                    }
                }
            }
        }
    );

    // Period Filter
    $('.period-filter').on('click', function(e) {
        e.preventDefault();

        const period = $(this).data('period');
        const periodLabels = {
            '7days': '7 Hari Terakhir',
            '30days': '30 Hari Terakhir',
            '90days': '90 Hari Terakhir'
        };

        $('#periodLabel').text(periodLabels[period]);
        $('.period-filter').removeClass('active');
        $(this).addClass('active');

        // Update chart data
        $.get('{{ route("dashboard.sales-data") }}', { period: period }, function(data) {
            salesChart.data.labels = data.labels;
            salesChart.data.datasets[0].data = data.revenues;
            salesChart.data.datasets[1].data = data.transactions;
            salesChart.update();
        });
    });
});
</script>
@endsection
