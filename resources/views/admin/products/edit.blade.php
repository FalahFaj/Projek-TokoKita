<!-- resources/views/admin/products/edit.blade.php -->
@extends('layouts.app')

@section('title', 'Edit Produk - TokoKita')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .image-preview {
        max-width: 200px;
        max-height: 200px;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">
                    <i class="fas fa-edit me-2"></i>Edit Produk: {{ $product->name }}
                </h4>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Basic Information -->
                            <div class="col-md-8">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Informasi Dasar</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label for="name" class="form-label">Nama Produk <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                       id="name" name="name" value="{{ old('name', $product->name) }}" required>
                                                @error('name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label for="sku" class="form-label">SKU <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control @error('sku') is-invalid @enderror"
                                                       id="sku" name="sku" value="{{ old('sku', $product->sku) }}" required>
                                                @error('sku')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label for="barcode" class="form-label">Barcode</label>
                                                <input type="text" class="form-control @error('barcode') is-invalid @enderror"
                                                       id="barcode" name="barcode" value="{{ old('barcode', $product->barcode) }}">
                                                @error('barcode')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label for="unit" class="form-label">Satuan <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control @error('unit') is-invalid @enderror"
                                                       id="unit" name="unit" value="{{ old('unit', $product->unit) }}" required>
                                                @error('unit')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-12">
                                                <label for="description" class="form-label">Deskripsi</label>
                                                <textarea class="form-control @error('description') is-invalid @enderror"
                                                          id="description" name="description" rows="3">{{ old('description', $product->description) }}</textarea>
                                                @error('description')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Pricing & Stock -->
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Harga & Stok</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label for="purchase_price" class="form-label">Harga Beli <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text">Rp</span>
                                                    <input type="number" class="form-control @error('purchase_price') is-invalid @enderror"
                                                           id="purchase_price" name="purchase_price"
                                                           value="{{ old('purchase_price', $product->purchase_price) }}"
                                                           min="0" step="1" required>
                                                </div>
                                                @error('purchase_price')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-4">
                                                <label for="selling_price" class="form-label">Harga Jual <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text">Rp</span>
                                                    <input type="number" class="form-control @error('selling_price') is-invalid @enderror"
                                                           id="selling_price" name="selling_price"
                                                           value="{{ old('selling_price', $product->selling_price) }}"
                                                           min="0" step="1" required>
                                                </div>
                                                @error('selling_price')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label">Margin</label>
                                                <div class="form-control" id="profit_margin_display">
                                                    {{ number_format($product->profit_margin, 2) }}%
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <label for="stock" class="form-label">Stok <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control @error('stock') is-invalid @enderror"
                                                       id="stock" name="stock" value="{{ old('stock', $product->stock) }}" min="0" required>
                                                @error('stock')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-4">
                                                <label for="min_stock" class="form-label">Stok Minimum <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control @error('min_stock') is-invalid @enderror"
                                                       id="min_stock" name="min_stock" value="{{ old('min_stock', $product->min_stock) }}" min="0" required>
                                                @error('min_stock')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-4">
                                                <label for="max_stock" class="form-label">Stok Maksimum <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control @error('max_stock') is-invalid @enderror"
                                                       id="max_stock" name="max_stock" value="{{ old('max_stock', $product->max_stock) }}" min="0" required>
                                                @error('max_stock')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sidebar -->
                            <div class="col-md-4">
                                <!-- Categories & Suppliers -->
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Klasifikasi</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="category_id" class="form-label">Kategori <span class="text-danger">*</span></label>
                                            <select class="form-select @error('category_id') is-invalid @enderror"
                                                    id="category_id" name="category_id" required>
                                                <option value="">Pilih Kategori</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('category_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="supplier_id" class="form-label">Supplier <span class="text-danger">*</span></label>
                                            <select class="form-select @error('supplier_id') is-invalid @enderror"
                                                    id="supplier_id" name="supplier_id" required>
                                                <option value="">Pilih Supplier</option>
                                                @foreach($suppliers as $supplier)
                                                    <option value="{{ $supplier->id }}"
                                                        {{ old('supplier_id', $product->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                                        {{ $supplier->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('supplier_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Status Toggle -->
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Status</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" id="is_active"
                                                   name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_active">Produk Aktif</label>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="is_available"
                                                   name="is_available" value="1" {{ old('is_available', $product->is_available) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_available">Tersedia untuk Dijual</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Current Image -->
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Gambar Saat Ini</h5>
                                    </div>
                                    <div class="card-body text-center">
                                        @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}"
                                                 alt="{{ $product->name }}" class="img-thumbnail mb-3" style="max-height: 200px;">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="remove_image" name="remove_image" value="1">
                                                <label class="form-check-label" for="remove_image">
                                                    Hapus gambar
                                                </label>
                                            </div>
                                        @else
                                            <div class="text-muted">
                                                <i class="fas fa-image fa-3x mb-2"></i>
                                                <p>Belum ada gambar</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Update Image -->
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Ubah Gambar</h5>
                                    </div>
                                    <div class="card-body">
                                        <input type="file" class="form-control @error('image') is-invalid @enderror"
                                               id="image" name="image" accept="image/*">
                                        @error('image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Biarkan kosong jika tidak ingin mengubah</small>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-grid gap-2">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save me-1"></i> Update Produk
                                            </button>
                                            <a href="{{ route('admin.products.show', $product) }}" class="btn btn-info">
                                                <i class="fas fa-eye me-1"></i> Lihat Detail
                                            </a>
                                            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                                                <i class="fas fa-times me-1"></i> Batal
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    // Select2 for category and supplier
    $('#category_id, #supplier_id').select2({
        placeholder: "Pilih...",
        allowClear: true
    });

    // Image preview
    $('#image').change(function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#imagePreview').attr('src', e.target.result).show();
                $('#noImage').hide();
            }
            reader.readAsDataURL(file);
        }
    });

    // Calculate profit margin
    function calculateMargin() {
        const purchasePrice = parseFloat($('#purchase_price').val()) || 0;
        const sellingPrice = parseFloat($('#selling_price').val()) || 0;

        if (purchasePrice > 0 && sellingPrice > 0) {
            const margin = ((sellingPrice - purchasePrice) / purchasePrice) * 100;
            $('#profit_margin_display').text(margin.toFixed(2) + '%');
        } else {
            $('#profit_margin_display').text('0%');
        }
    }

    $('#purchase_price, #selling_price').on('input', calculateMargin);
});
</script>
@endsection
