<!-- resources/views/admin/reports/sales.blade.php -->
@extends('layouts.app')

@section('title', 'Laporan Penjualan - TokoKita')

@section('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    .stat-card {
        transition: transform 0.2s;
    }
    .stat-card:hover {
        transform: translateY(-2px);
    }
    .date-filter {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1.5rem;
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
                    <i class="fas fa-chart-line me-2"></i>Laporan Penjualan
                </h4>
                <div class="dropdown">
                    <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-download me-1"></i> Export
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-file-pdf me-2"></i> PDF</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-file-excel me-2"></i> Excel</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Date Filter -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card date-filter">
                <div class="card-body">
                    <form action="{{ route('admin.reports.sales') }}" method="GET" class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label for="period" class="form-label">Periode Cepat</label>
                            <select class="form-select" id="period" name="period" onchange="this.form.submit()">
                                <option value="today" {{ $dateRange['period'] == 'today' ? 'selected' : '' }}>Hari Ini</option>
                                <option value="yesterday" {{ $dateRange['period'] == 'yesterday' ? 'selected' : '' }}>Kemarin</option>
                                <option value="7days" {{ $dateRange['period'] == '7days' ? 'selected' : '' }}>7 Hari Terakhir</option>
                                <option value="30days" {{ $dateRange['period'] == '30days' ? 'selected' : '' }}>30 Hari Terakhir</option>
                                <option value="90days" {{ $dateRange['period'] == '90days' ? 'selected' : '' }}>90 Hari Terakhir</option>
                                <option value="custom" {{ $dateRange['period'] == 'custom' ? 'selected' : '' }}>Kustom</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="start_date" class="form-label">Dari Tanggal</label>
                            <input type="date" class="form-control" id="start_date" name="start_date"
                                   value="{{ $dateRange['start'] }}" {{ $dateRange['period'] != 'custom' ? 'disabled' : '' }}>
                        </div>
                        <div class="col-md-3">
                            <label for="end_date" class="form-label">Sampai Tanggal</label>
                            <input type="date" class="form-control" id="end_date" name="end_date"
                                   value="{{ $dateRange['end'] }}" {{ $dateRange['period'] != 'custom' ? 'disabled' : '' }}>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-filter me-1"></i> Terapkan Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Sales Overview Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card bg-primary text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Total Pendapatan</h5>
                            <h2 class="mb-0">Rp {{ number_format($salesData['total_revenue'], 0, ',', '.') }}</h2>
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
                            <h5 class="card-title">Total Transaksi</h5>
                            <h2 class="mb-0">{{ number_format($salesData['total_transactions']) }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-shopping-cart fa-2x opacity-50"></i>
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
                            <h5 class="card-title">Rata-rata Transaksi</h5>
                            <h2 class="mb-0">Rp {{ number_format($salesData['average_transaction'], 0, ',', '.') }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-chart-bar fa-2x opacity-50"></i>
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
                            <h5 class="card-title">Total Diskon</h5>
                            <h2 class="mb-0">Rp {{ number_format($salesData['total_discount'], 0, ',', '.') }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-tag fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <!-- Sales Trend Chart -->
        <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-line me-2"></i>Trend Penjualan Harian
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="salesTrendChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Payment Method Chart -->
        <div class="col-xl-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-credit-card me-2"></i>Metode Pembayaran
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="paymentMethodChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Charts Row -->
    <div class="row mb-4">
        <!-- Sales by Hour -->
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-clock me-2"></i>Penjualan per Jam
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="salesByHourChart" height="250"></canvas>
                </div>
            </div>
        </div>

        <!-- Top Products -->
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-star me-2"></i>10 Produk Terlaris
                    </h5>
                    <span class="badge bg-primary">{{ $topProducts->count() }} produk</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Terjual</th>
                                    <th>Pendapatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topProducts as $product)
                                <tr>
                                    <td>
                                        <strong>{{ $product->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $product->sku }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $product->total_sold }}</span>
                                    </td>
                                    <td>
                                        <strong>Rp {{ number_format($product->total_revenue, 0, ',', '.') }}</strong>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Daily Sales Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-table me-2"></i>Detail Penjualan Harian
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Transaksi</th>
                                    <th>Pendapatan</th>
                                    <th>Pajak</th>
                                    <th>Diskon</th>
                                    <th>Rata-rata</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($salesData['daily_data'] as $daily)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($daily->date)->format('d M Y') }}</td>
                                    <td>{{ $daily->transactions_count }}</td>
                                    <td>Rp {{ number_format($daily->revenue, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($daily->tax, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($daily->discount, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($daily->revenue / $daily->transactions_count, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="table-primary">
                                    <th>TOTAL</th>
                                    <th>{{ $salesData['total_transactions'] }}</th>
                                    <th>Rp {{ number_format($salesData['total_revenue'], 0, ',', '.') }}</th>
                                    <th>Rp {{ number_format($salesData['total_tax'], 0, ',', '.') }}</th>
                                    <th>Rp {{ number_format($salesData['total_discount'], 0, ',', '.') }}</th>
                                    <th>Rp {{ number_format($salesData['average_transaction'], 0, ',', '.') }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Data from controller
    const salesDailyData = @json($salesData['daily_data'] ?? []);
    const salesByPaymentData = @json($salesByPayment ?? []);
    const salesByHourData = @json($salesByHour ?? []);

    // Sales Trend Chart
    const salesTrendCtx = document.getElementById('salesTrendChart').getContext('2d');
    const salesTrendLabels = salesDailyData.map(item => {
        const date = new Date(item.date);
        return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
    });

    const salesTrendChart = new Chart(salesTrendCtx, {
        type: 'line',
        data: {
            labels: salesTrendLabels,
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: [
                    @foreach($salesData['daily_data'] as $daily)
                        {{ $daily->revenue }},
                    @endforeach
                ],
                borderColor: 'rgb(54, 162, 235)',
                backgroundColor: 'rgba(54, 162, 235, 0.1)',
                yAxisID: 'y',
                tension: 0.4
            }, {
                label: 'Jumlah Transaksi',
                data: salesDailyData.map(item => item.transactions_count),
                borderColor: 'rgb(255, 99, 132)',
                backgroundColor: 'rgba(255, 99, 132, 0.1)',
                yAxisID: 'y1',
                tension: 0.4
            }]
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
            }
        }
    });

    // Payment Method Chart
    const paymentMethodCtx = document.getElementById('paymentMethodChart').getContext('2d');
    const paymentMethodChart = new Chart(paymentMethodCtx, {
        type: 'doughnut',
        data: {
            labels: salesByPaymentData.map(item => item.payment_method.charAt(0).toUpperCase() + item.payment_method.slice(1)),
            datasets: [{
                data: salesByPaymentData.map(item => item.revenue),
                backgroundColor: [
                    '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = Math.round((value / total) * 100);
                            return `${label}: Rp ${value.toLocaleString('id-ID')} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });

    // Sales by Hour Chart
    const salesByHourCtx = document.getElementById('salesByHourChart').getContext('2d');

    // Prepare hourly data (fill missing hours with 0)
    const hourlyData = Array.from({length: 24}, (_, i) => {
        const found = salesByHourData.find(item => item.hour === i);
        return found ? found.revenue : 0;
    });

    const salesByHourChart = new Chart(salesByHourCtx, {
        type: 'bar',
        data: {
            labels: Array.from({length: 24}, (_, i) => `${i}:00`),
            datasets: [{
                label: 'Pendapatan per Jam',
                data: hourlyData,
                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });

    // Date filter logic
    const periodSelect = document.getElementById('period');
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');

    periodSelect.addEventListener('change', function() {
        if (this.value === 'custom') {
            startDateInput.disabled = false;
            endDateInput.disabled = false;
        } else {
            startDateInput.disabled = true;
            endDateInput.disabled = true;
        }
    });
});
</script>
@endsection
