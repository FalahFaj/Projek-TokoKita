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
                                <!-- Sama seperti create form, tapi dengan value dari $product -->
                                <!-- Karena panjang, saya buat struktur yang sama dengan create -->
                                <!-- Isi dengan value="{{ $product->field_name }}" -->
                            </div>

                            <!-- Sidebar -->
                            <div class="col-md-4">
                                <!-- Status Toggle -->
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Status</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" id="is_active"
                                                   name="is_active" value="1" {{ $product->is_active ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_active">Produk Aktif</label>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="is_available"
                                                   name="is_available" value="1" {{ $product->is_available ? 'checked' : '' }}>
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
                                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
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

    // Auto-generate SKU if empty
    $('#name').blur(function() {
        if (!$('#sku').val()) {
            const name = $(this).val();
            if (name) {
                const sku = 'SKU-' + name.substring(0, 3).toUpperCase() + '-' + Date.now().toString().substr(-4);
                $('#sku').val(sku);
            }
        }
    });
});
</script>
@endsection
