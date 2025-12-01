<!-- resources/views/admin/products/index.blade.php -->
@extends('layouts.app')

@section('title', 'Manajemen Produk - TokoKita')

@section('styles')
{{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> --}}
<style>
    .product-image {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 4px;
    }
    .stock-low { color: #dc3545; font-weight: bold; }
    .stock-ok { color: #28a745; }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">
                    <i class="fas fa-boxes me-2"></i>Manajemen Produk
                </h4>
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Tambah Produk
                </a>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.products.index') }}" method="GET">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="search" class="form-label">Cari Produk</label>
                                <input type="text" class="form-control" id="search" name="search"
                                       value="{{ request('search') }}" placeholder="Nama, SKU, atau Barcode...">
                            </div>
                            <div class="col-md-3">
                                <label for="category_id" class="form-label">Kategori</label>
                                <select class="form-select" id="category_id" name="category_id">
                                    <option value="">Semua Kategori</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="stock_status" class="form-label">Status Stok</label>
                                <select class="form-select" id="stock_status" name="stock_status">
                                    <option value="">Semua Status</option>
                                    <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>Stok Rendah</option>
                                    <option value="out" {{ request('stock_status') == 'out' ? 'selected' : '' }}>Stok Habis</option>
                                    <option value="available" {{ request('stock_status') == 'available' ? 'selected' : '' }}>Tersedia</option>
                                </select>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search me-1"></i> Filter
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Daftar Produk</h5>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-secondary" id="bulkActionBtn" disabled>
                            <i class="fas fa-cog me-1"></i> Aksi Massal
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-sync me-1"></i> Refresh
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($products->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="50">
                                        <input type="checkbox" id="selectAll">
                                    </th>
                                    <th>Gambar</th>
                                    <th>Nama Produk</th>
                                    <th>SKU</th>
                                    <th>Kategori</th>
                                    <th>Harga</th>
                                    <th>Stok</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="product-checkbox" value="{{ $product->id }}">
                                    </td>
                                    <td>
                                        @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}"
                                                 alt="{{ $product->name }}" class="product-image">
                                        @else
                                            <div class="product-image bg-light d-flex align-items-center justify-content-center">
                                                <i class="fas fa-box text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $product->name }}</strong>
                                        @if(!$product->is_active)
                                            <span class="badge bg-secondary ms-1">Nonaktif</span>
                                        @endif
                                        @if(!$product->is_available)
                                            <span class="badge bg-warning ms-1">Tidak Tersedia</span>
                                        @endif
                                    </td>
                                    <td>
                                        <code>{{ $product->sku }}</code>
                                        @if($product->barcode)
                                            <br><small class="text-muted">{{ $product->barcode }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $product->category->name }}</td>
                                    <td>
                                        <strong>Rp {{ number_format($product->selling_price, 0, ',', '.') }}</strong>
                                        <br>
                                        <small class="text-muted">Beli: Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</small>
                                    </td>
                                    <td>
                                        @if($product->stock == 0)
                                            <span class="badge badge-out-of-stock">Habis</span>
                                        @elseif($product->stock <= $product->min_stock)
                                            <span class="badge bg-warning text-dark">Rendah ({{ $product->stock }})</span>
                                        @else
                                            <span class="badge bg-success">{{ $product->stock }}</span>
                                        @endif
                                        <br>
                                        <small class="text-muted">Min: {{ $product->min_stock }}</small>
                                    </td>
                                    <td>
                                        @if($product->is_active)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.products.show', $product) }}"
                                               class="btn btn-sm btn-info" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.products.edit', $product) }}"
                                               class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.products.destroy', $product) }}"
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Hapus produk ini?')" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            Menampilkan {{ $products->firstItem() }} - {{ $products->lastItem() }} dari {{ $products->total() }} produk
                        </div>
                        <div>
                            {{ $products->links() }}
                        </div>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-boxes fa-3x text-muted mb-3"></i>
                        <h5>Belum Ada Produk</h5>
                        <p class="text-muted">Mulai dengan menambahkan produk pertama Anda.</p>
                        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i> Tambah Produk Pertama
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Action Modal -->
<div class="modal fade" id="bulkActionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Aksi Massal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.products.bulk-action') }}" method="POST" id="bulkActionForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="ids" id="selectedIds">
                    <div class="mb-3">
                        <label for="action" class="form-label">Pilih Aksi</label>
                        <select class="form-select" id="action" name="action" required>
                            <option value="">-- Pilih Aksi --</option>
                            <option value="activate">Aktifkan Produk</option>
                            <option value="deactivate">Nonaktifkan Produk</option>
                            <option value="delete">Hapus Produk</option>
                        </select>
                    </div>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        Aksi ini akan diterapkan pada <span id="selectedCount">0</span> produk terpilih.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Eksekusi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Select2 for category filter
    $('#category_id').select2({
        placeholder: "Pilih kategori",
        allowClear: true
    });

    // Bulk selection
    $('#selectAll').change(function() {
        $('.product-checkbox').prop('checked', this.checked);
        updateBulkActionButton();
    });

    $('.product-checkbox').change(function() {
        if (!this.checked) {
            $('#selectAll').prop('checked', false);
        }
        updateBulkActionButton();
    });

    function updateBulkActionButton() {
        const checkedCount = $('.product-checkbox:checked').length;
        const bulkActionBtn = $('#bulkActionBtn');

        if (checkedCount > 0) {
            bulkActionBtn.prop('disabled', false);
            bulkActionBtn.text(`Aksi Massal (${checkedCount})`);
        } else {
            bulkActionBtn.prop('disabled', true);
            bulkActionBtn.text('Aksi Massal');
        }
    }

    // Bulk action modal
    $('#bulkActionBtn').click(function() {
        const selectedIds = $('.product-checkbox:checked').map(function() {
            return this.value;
        }).get();

        $('#selectedIds').val(selectedIds);
        $('#selectedCount').text(selectedIds.length);
        $('#bulkActionModal').modal('show');
    });

    // Confirm bulk delete
    $('#bulkActionForm').submit(function(e) {
        const action = $('#action').val();
        if (action === 'delete') {
            if (!confirm('Yakin ingin menghapus produk yang dipilih? Tindakan ini tidak dapat dibatalkan.')) {
                e.preventDefault();
            }
        }
    });
});
</script>
@endsection
