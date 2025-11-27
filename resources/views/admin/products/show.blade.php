<!-- resources/views/admin/products/show.blade.php -->
@extends('layouts.app')

@section('title', 'Detail Produk - TokoKita')

@section('styles')
<style>
    .detail-card {
        border-left: 4px solid #007bff;
    }
    .status-badge {
        font-size: 0.875rem;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">
                    <i class="fas fa-eye me-2"></i>Detail Produk: {{ $product->name }}
                </h4>
                <div class="btn-group">
                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-1"></i> Edit
                    </a>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <!-- Basic Information -->
                        <div class="col-md-8">
                            <div class="card mb-4 detail-card">
                                <div class="card-header bg-transparent">
                                    <h5 class="card-title mb-0 text-primary">
                                        <i class="fas fa-info-circle me-2"></i>Informasi Dasar
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Nama Produk</label>
                                            <p class="form-control-plaintext">{{ $product->name }}</p>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">SKU</label>
                                            <p class="form-control-plaintext">{{ $product->sku }}</p>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Barcode</label>
                                            <p class="form-control-plaintext">{{ $product->barcode ?? '-' }}</p>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Satuan</label>
                                            <p class="form-control-plaintext">{{ $product->unit }}</p>
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label fw-bold">Deskripsi</label>
                                            <p class="form-control-plaintext">{{ $product->description ?: 'Tidak ada deskripsi' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Pricing & Stock -->
                            <div class="card mb-4 detail-card">
                                <div class="card-header bg-transparent">
                                    <h5 class="card-title mb-0 text-primary">
                                        <i class="fas fa-chart-line me-2"></i>Harga & Stok
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Harga Beli</label>
                                            <p class="form-control-plaintext">Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</p>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Harga Jual</label>
                                            <p class="form-control-plaintext">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</p>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Margin</label>
                                            <p class="form-control-plaintext text-success">{{ number_format($product->profit_margin, 2) }}%</p>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Stok Saat Ini</label>
                                            <p class="form-control-plaintext">
                                                <span class="badge bg-{{ $product->stock <= $product->min_stock ? 'danger' : ($product->stock >= $product->max_stock ? 'warning' : 'success') }}">
                                                    {{ $product->stock }}
                                                </span>
                                            </p>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Stok Minimum</label>
                                            <p class="form-control-plaintext">{{ $product->min_stock }}</p>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Stok Maksimum</label>
                                            <p class="form-control-plaintext">{{ $product->max_stock }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sidebar -->
                        <div class="col-md-4">
                            <!-- Categories & Suppliers -->
                            <div class="card mb-4 detail-card">
                                <div class="card-header bg-transparent">
                                    <h5 class="card-title mb-0 text-primary">
                                        <i class="fas fa-tags me-2"></i>Klasifikasi
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Kategori</label>
                                        <p class="form-control-plaintext">
                                            @if($product->category)
                                                <span class="badge bg-primary">{{ $product->category->name }}</span>
                                            @else
                                                <span class="text-muted">Tidak ada kategori</span>
                                            @endif
                                        </p>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Supplier</label>
                                        <p class="form-control-plaintext">
                                            @if($product->supplier)
                                                <span class="badge bg-info">{{ $product->supplier->name }}</span>
                                            @else
                                                <span class="text-muted">Tidak ada supplier</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Status Information -->
                            <div class="card mb-4 detail-card">
                                <div class="card-header bg-transparent">
                                    <h5 class="card-title mb-0 text-primary">
                                        <i class="fas fa-toggle-on me-2"></i>Status
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Status Produk</label>
                                        <p>
                                            @if($product->is_active)
                                                <span class="badge bg-success status-badge">
                                                    <i class="fas fa-check me-1"></i> Aktif
                                                </span>
                                            @else
                                                <span class="badge bg-danger status-badge">
                                                    <i class="fas fa-times me-1"></i> Non-Aktif
                                                </span>
                                            @endif
                                        </p>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Ketersediaan</label>
                                        <p>
                                            @if($product->is_available)
                                                <span class="badge bg-success status-badge">
                                                    <i class="fas fa-check me-1"></i> Tersedia untuk Dijual
                                                </span>
                                            @else
                                                <span class="badge bg-secondary status-badge">
                                                    <i class="fas fa-pause me-1"></i> Tidak Tersedia
                                                </span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Product Image -->
                            <div class="card mb-4 detail-card">
                                <div class="card-header bg-transparent">
                                    <h5 class="card-title mb-0 text-primary">
                                        <i class="fas fa-image me-2"></i>Gambar Produk
                                    </h5>
                                </div>
                                <div class="card-body text-center">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}"
                                             alt="{{ $product->name }}"
                                             class="img-thumbnail mb-3"
                                             style="max-height: 250px; width: auto;">
                                        <p class="text-muted small">Gambar Produk</p>
                                    @else
                                        <div class="text-muted py-4">
                                            <i class="fas fa-image fa-4x mb-3"></i>
                                            <p>Belum ada gambar</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Additional Info -->
                            <div class="card detail-card">
                                <div class="card-header bg-transparent">
                                    <h5 class="card-title mb-0 text-primary">
                                        <i class="fas fa-history me-2"></i>Informasi Tambahan
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <small class="text-muted">Dibuat:</small>
                                        <p class="small mb-0">{{ $product->created_at->format('d M Y H:i') }}</p>
                                    </div>
                                    <div class="mb-2">
                                        <small class="text-muted">Diupdate:</small>
                                        <p class="small mb-0">{{ $product->updated_at->format('d M Y H:i') }}</p>
                                    </div>
                                    @if($product->deleted_at)
                                    <div class="mb-2">
                                        <small class="text-muted">Dihapus:</small>
                                        <p class="small mb-0 text-danger">{{ $product->deleted_at->format('d M Y H:i') }}</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
