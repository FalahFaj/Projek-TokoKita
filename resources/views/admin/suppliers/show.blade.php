@extends('layouts.app')

@section('title', 'Detail Supplier - TokoKita')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">
                    <i class="fas fa-eye me-2"></i>Detail Supplier: {{ $supplier->name }}
                </h4>
                <div>
                    <a href="{{ route('admin.suppliers.edit', $supplier) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-1"></i> Edit
                    </a>
                    <a href="{{ route('admin.suppliers.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Supplier Info -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informasi Supplier</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Nama Supplier</th>
                                    <td>{{ $supplier->name }}</td>
                                </tr>
                                <tr>
                                    <th>Perusahaan</th>
                                    <td>{{ $supplier->company_name ?: '-' }}</td>
                                </tr>
                                <tr>
                                    <th>NPWP</th>
                                    <td>{{ $supplier->tax_number ?: '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        @if($supplier->is_active)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Nonaktif</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Informasi Kontak</h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <th><i class="fas fa-envelope text-muted me-2"></i>Email</th>
                                    <td>{{ $supplier->email ?: '-' }}</td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-phone text-muted me-2"></i>Telepon</th>
                                    <td>{{ $supplier->phone }}</td>
                                </tr>
                            </table>

                            <h6>Informasi Alamat</h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <th><i class="fas fa-map-marker-alt text-muted me-2"></i>Kota</th>
                                    <td>{{ $supplier->city }}</td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-home text-muted me-2"></i>Alamat</th>
                                    <td>{{ $supplier->address }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <h6>Informasi Waktu</h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <th>Dibuat</th>
                                    <td>{{ $supplier->created_at->format('d M Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Diupdate</th>
                                    <td>{{ $supplier->updated_at->format('d M Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products from this Supplier -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Produk dari Supplier Ini</h5>
                    <span class="badge bg-primary">{{ $supplier->products->count() }} produk</span>
                </div>
                <div class="card-body">
                    @if($supplier->products->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Nama Produk</th>
                                    <th>SKU</th>
                                    <th>Kategori</th>
                                    <th>Harga Beli</th>
                                    <th>Stok</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($supplier->products as $product)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.products.show', $product) }}" class="text-decoration-none">
                                            {{ $product->name }}
                                        </a>
                                    </td>
                                    <td><code>{{ $product->sku }}</code></td>
                                    <td>{{ $product->category->name }}</td>
                                    <td>Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</td>
                                    <td>
                                        @if($product->stock == 0)
                                            <span class="badge bg-danger">Habis</span>
                                        @elseif($product->stock <= $product->min_stock)
                                            <span class="badge bg-warning text-dark">{{ $product->stock }}</span>
                                        @else
                                            <span class="badge bg-success">{{ $product->stock }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($product->is_active)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Nonaktif</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-boxes fa-2x text-muted mb-2"></i>
                        <p class="text-muted">Belum ada produk dari supplier ini</p>
                        <a href="{{ route('admin.products.create') }}?supplier_id={{ $supplier->id }}"
                           class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i> Tambah Produk
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Quick Actions -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Aksi Cepat</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.suppliers.edit', $supplier) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-1"></i> Edit Supplier
                        </a>
                        <a href="{{ route('admin.products.create') }}?supplier_id={{ $supplier->id }}"
                           class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i> Tambah Produk
                        </a>
                        <a href="{{ route('admin.products.index') }}?supplier_id={{ $supplier->id }}"
                           class="btn btn-info">
                            <i class="fas fa-list me-1"></i> Lihat Semua Produk
                        </a>
                    </div>
                </div>
            </div>

            <!-- Supplier Stats -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Statistik Supplier</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Total Produk:</strong>
                        <span class="float-end">{{ $supplier->products_count }}</span>
                    </div>
                    <div class="mb-3">
                        <strong>Produk Aktif:</strong>
                        <span class="float-end">{{ $supplier->products->where('is_active', true)->count() }}</span>
                    </div>
                    <div class="mb-3">
                        <strong>Stok Rendah:</strong>
                        <span class="float-end text-warning">
                            {{ $supplier->products->filter(function($product) {
                                return $product->stock > 0 && $product->stock <= $product->min_stock;
                            })->count() }}
                        </span>
                    </div>
                    <div class="mb-3">
                        <strong>Stok Habis:</strong>
                        <span class="float-end text-danger">
                            {{ $supplier->products->where('stock', 0)->count() }}
                        </span>
                    </div>
                    <hr>
                    <div class="mb-0">
                        <strong>Total Nilai Investasi:</strong>
                        <span class="float-end text-success">
                            Rp {{ number_format($supplier->products->sum(function($product) {
                                return $product->stock * $product->purchase_price;
                            }), 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Contact Card -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Kontak Supplier</h5>
                </div>
                <div class="card-body">
                    @if($supplier->email || $supplier->phone)
                    <div class="d-grid gap-2">
                        @if($supplier->email)
                        <a href="mailto:{{ $supplier->email }}" class="btn btn-outline-primary">
                            <i class="fas fa-envelope me-1"></i> Email
                        </a>
                        @endif
                        @if($supplier->phone)
                        <a href="tel:{{ $supplier->phone }}" class="btn btn-outline-success">
                            <i class="fas fa-phone me-1"></i> Telepon
                        </a>
                        @endif
                    </div>
                    @else
                    <p class="text-muted text-center mb-0">Tidak ada informasi kontak</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
