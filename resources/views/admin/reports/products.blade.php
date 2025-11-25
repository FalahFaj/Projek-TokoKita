<!-- resources/views/admin/reports/products.blade.php -->
@extends('layouts.app')

@section('title', 'Laporan Produk - TokoKita')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">
                    <i class="fas fa-boxes me-2"></i>Laporan Produk & Kinerja
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
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.reports.products') }}" method="GET" class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label for="start_date" class="form-label">Dari Tanggal</label>
                            <input type="date" class="form-control" id="start_date" name="start_date"
                                   value="{{ $dateRange['start'] }}">
                        </div>
                        <div class="col-md-4">
                            <label for="end_date" class="form-label">Sampai Tanggal</label>
                            <input type="date" class="form-control" id="end_date" name="end_date"
                                   value="{{ $dateRange['end'] }}">
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-filter me-1"></i> Terapkan Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Performance -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Kinerja Produk
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Kategori</th>
                                    <th>Supplier</th>
                                    <th>Terjual</th>
                                    <th>Pendapatan</th>
                                    <th>Stok Saat Ini</th>
                                    <th>Status Stok</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($productStats as $product)
                                <tr>
                                    <td>
                                        <strong>{{ $product->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $product->sku }}</small>
                                    </td>
                                    <td>{{ $product->category->name }}</td>
                                    <td>{{ $product->supplier->name }}</td>
                                    <td>
                                        <span class="badge bg-{{ $product->total_sold > 0 ? 'primary' : 'secondary' }}">
                                            {{ $product->total_sold }}
                                        </span>
                                    </td>
                                    <td>
                                        <strong>Rp {{ number_format($product->total_revenue, 0, ',', '.') }}</strong>
                                    </td>
                                    <td>{{ $product->stock }}</td>
                                    <td>
                                        @if($product->stock == 0)
                                            <span class="badge bg-danger">Habis</span>
                                        @elseif($product->stock <= $product->min_stock)
                                            <span class="badge bg-warning text-dark">Rendah</span>
                                        @else
                                            <span class="badge bg-success">Aman</span>
                                        @endif
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

    <!-- Category & Supplier Performance -->
    <div class="row">
        <!-- Category Performance -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-tags me-2"></i>Kinerja per Kategori
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Kategori</th>
                                    <th>Produk</th>
                                    <th>Terjual</th>
                                    <th>Nilai Stok</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categoryStats as $category)
                                <tr>
                                    <td>{{ $category->name }}</td>
                                    <td>{{ $category->product_count }}</td>
                                    <td>{{ $category->sold_count }}</td>
                                    <td>Rp {{ number_format($category->total_stock_value, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Supplier Performance -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-truck me-2"></i>Kinerja per Supplier
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Supplier</th>
                                    <th>Produk</th>
                                    <th>Terjual</th>
                                    <th>Nilai Stok</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($supplierStats as $supplier)
                                <tr>
                                    <td>{{ $supplier->name }}</td>
                                    <td>{{ $supplier->product_count }}</td>
                                    <td>{{ $supplier->sold_count }}</td>
                                    <td>Rp {{ number_format($supplier->total_stock_value, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Alerts -->
    <div class="row">
        <div class="col-12">
            <div class="card border-warning">
                <div class="card-header bg-warning text-dark">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>Peringatan Stok
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4">
                            <div class="alert alert-danger">
                                <h4>{{ $stockAlerts['out_of_stock'] }}</h4>
                                <p class="mb-0">Produk Habis</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="alert alert-warning">
                                <h4>{{ $stockAlerts['low_stock'] }}</h4>
                                <p class="mb-0">Stok Rendah</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="alert alert-info">
                                <h4>{{ $stockAlerts['over_stock'] }}</h4>
                                <p class="mb-0">Stok Berlebih</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
