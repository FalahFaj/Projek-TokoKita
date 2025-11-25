@extends('layouts.app')

@section('title', 'Detail Kategori - TokoKita')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">
                    <i class="fas fa-eye me-2"></i>Detail Kategori: {{ $category->name }}
                </h4>
                <div>
                    <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-1"></i> Edit
                    </a>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Category Info -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informasi Kategori</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Nama Kategori</th>
                                    <td>{{ $category->name }}</td>
                                </tr>
                                <tr>
                                    <th>Slug</th>
                                    <td><code>{{ $category->slug }}</code></td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        @if($category->is_active)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Nonaktif</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Total Produk</th>
                                    <td>
                                        <span class="badge bg-primary">{{ $category->products_count }} produk</span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Deskripsi</h6>
                            <p class="text-muted">
                                {{ $category->description ?: 'Tidak ada deskripsi' }}
                            </p>

                            <h6>Informasi Waktu</h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <th>Dibuat</th>
                                    <td>{{ $category->created_at->format('d M Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Diupdate</th>
                                    <td>{{ $category->updated_at->format('d M Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products in this Category -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Produk dalam Kategori Ini</h5>
                    <span class="badge bg-primary">{{ $category->products->count() }} produk</span>
                </div>
                <div class="card-body">
                    @if($category->products->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Nama Produk</th>
                                    <th>SKU</th>
                                    <th>Harga</th>
                                    <th>Stok</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($category->products as $product)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.products.show', $product) }}" class="text-decoration-none">
                                            {{ $product->name }}
                                        </a>
                                    </td>
                                    <td><code>{{ $product->sku }}</code></td>
                                    <td>Rp {{ number_format($product->selling_price, 0, ',', '.') }}</td>
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
                        <p class="text-muted">Belum ada produk dalam kategori ini</p>
                        <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">
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
                        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-1"></i> Edit Kategori
                        </a>
                        <a href="{{ route('admin.products.create') }}?category_id={{ $category->id }}"
                           class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i> Tambah Produk
                        </a>
                        <a href="{{ route('admin.products.index') }}?category_id={{ $category->id }}"
                           class="btn btn-info">
                            <i class="fas fa-list me-1"></i> Lihat Semua Produk
                        </a>
                    </div>
                </div>
            </div>

            <!-- Category Stats -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Statistik Kategori</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Total Produk:</strong>
                        <span class="float-end">{{ $category->products_count }}</span>
                    </div>
                    <div class="mb-3">
                        <strong>Produk Aktif:</strong>
                        <span class="float-end">{{ $category->products->where('is_active', true)->count() }}</span>
                    </div>
                    <div class="mb-3">
                        <strong>Stok Rendah:</strong>
                        <span class="float-end text-warning">
                            {{ $category->products->filter(function($product) {
                                return $product->stock > 0 && $product->stock <= $product->min_stock;
                            })->count() }}
                        </span>
                    </div>
                    <div class="mb-3">
                        <strong>Stok Habis:</strong>
                        <span class="float-end text-danger">
                            {{ $category->products->where('stock', 0)->count() }}
                        </span>
                    </div>
                    <hr>
                    <div class="mb-0">
                        <strong>Nilai Total Stok:</strong>
                        <span class="float-end text-success">
                            Rp {{ number_format($category->products->sum(function($product) {
                                return $product->stock * $product->purchase_price;
                            }), 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
