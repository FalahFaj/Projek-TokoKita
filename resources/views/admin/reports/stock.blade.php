<!-- resources/views/admin/reports/stock.blade.php -->
@extends('layouts.app')

@section('title', 'Laporan Stok - TokoKita')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">
                    <i class="fas fa-box me-2"></i>Laporan Stok & Inventori
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

    <!-- Stock Overview -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Total Produk</h5>
                            <h2 class="mb-0">{{ $stockStatus['total_products'] }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-boxes fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Total Stok</h5>
                            <h2 class="mb-0">{{ number_format($stockStatus['total_stock']) }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-cubes fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Nilai Investasi</h5>
                            <h2 class="mb-0">Rp {{ number_format($stockValuation['purchase_value'], 0, ',', '.') }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-money-bill-wave fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Profit Potensial</h5>
                            <h2 class="mb-0">Rp {{ number_format($stockValuation['potential_profit'], 0, ',', '.') }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-chart-line fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Low Stock Products -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-warning">
                <div class="card-header bg-warning text-dark">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>Produk Stok Rendah & Habis
                    </h5>
                </div>
                <div class="card-body">
                    @if($lowStockProducts->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Kategori</th>
                                    <th>Supplier</th>
                                    <th>Stok Saat Ini</th>
                                    <th>Stok Minimum</th>
                                    <th>Status</th>
                                    <th>Harga Beli</th>
                                    <th>Nilai Stok</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lowStockProducts as $product)
                                <tr class="{{ $product->stock == 0 ? 'table-danger' : 'table-warning' }}">
                                    <td>
                                        <strong>{{ $product->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $product->sku }}</small>
                                    </td>
                                    <td>{{ $product->category->name }}</td>
                                    <td>{{ $product->supplier->name }}</td>
                                    <td>
                                        <span class="badge bg-{{ $product->stock == 0 ? 'danger' : 'warning' }}">
                                            {{ $product->stock }}
                                        </span>
                                    </td>
                                    <td>{{ $product->min_stock }}</td>
                                    <td>
                                        @if($product->stock == 0)
                                            <span class="badge bg-danger">Habis</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Rendah</span>
                                        @endif
                                    </td>
                                    <td>Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($product->stock * $product->purchase_price, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <h5>Semua Stok Aman</h5>
                        <p class="text-muted">Tidak ada produk dengan stok rendah atau habis</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Valuation -->
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calculator me-2"></i>Valuasi Stok
                    </h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Nilai Pembelian (HPP)</span>
                            <strong class="text-primary">Rp {{ number_format($stockValuation['purchase_value'], 0, ',', '.') }}</strong>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Nilai Penjualan Potensial</span>
                            <strong class="text-success">Rp {{ number_format($stockValuation['selling_value'], 0, ',', '.') }}</strong>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Profit Potensial</span>
                            <strong class="text-warning">Rp {{ number_format($stockValuation['potential_profit'], 0, ',', '.') }}</strong>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Margin Potensial</span>
                            <strong class="text-info">
                                {{ $stockValuation['purchase_value'] > 0 ?
                                    number_format(($stockValuation['potential_profit'] / $stockValuation['purchase_value']) * 100, 2) : 0 }}%
                            </strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-pie me-2"></i>Ringkasan Stok
                    </h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Total Produk</span>
                            <span class="badge bg-primary rounded-pill">{{ $stockStatus['total_products'] }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Produk dengan Stok</span>
                            <span class="badge bg-success rounded-pill">{{ $stockStatus['products_with_stock'] }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Rata-rata Stok per Produk</span>
                            <span class="badge bg-info rounded-pill">{{ number_format($stockStatus['average_stock'], 1) }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Total Unit Stok</span>
                            <span class="badge bg-warning rounded-pill">{{ number_format($stockStatus['total_stock']) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
