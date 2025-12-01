@extends('layouts.app')

@section('title', 'POS - TokoKita')

@section('content')
<div class="container-fluid pos-container">
    <div class="row">
        <!-- Products Section -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-cash-register me-2"></i>Point of Sale (POS)
                    </h4>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-light text-dark me-3">
                            <i class="fas fa-user me-1"></i>{{ $user->name }} ({{ $user->role_name }})
                        </span>
                        <span class="badge bg-light text-dark">
                            <i class="fas fa-clock me-1"></i>{{ now()->format('d M Y H:i') }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Search and Filter -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                                <input type="text" class="form-control" id="productSearch" placeholder="Cari produk...">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <select class="form-select" id="categoryFilter">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-secondary w-100" data-action="clearFilters">
                                <i class="fas fa-refresh"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Products Grid -->
                    <div class="row" id="productsGrid">
                        @foreach($products as $product)
                            <div class="col-xl-3 col-lg-4 col-md-6 mb-3 product-item"
                                 data-category="{{ $product->category_id }}"
                                 data-name="{{ strtolower($product->name) }}"
                                 data-sku="{{ strtolower($product->sku) }}"
                                 data-barcode="{{ strtolower($product->barcode) }}">
                                <div class="card product-card h-100 {{ $product->stock <= 0 ? 'out-of-stock' : '' }}"
                                     data-product-id="{{ $product->id }}">
                                    <div class="card-body text-center p-3">
                                        <!-- Product content remains the same -->
                                        @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}"
                                                 alt="{{ $product->name }}"
                                                 class="product-image w-100 mb-2">
                                        @else
                                            <div class="no-image mb-2">
                                                <i class="fas fa-image fa-2x"></i>
                                            </div>
                                        @endif

                                        <h6 class="card-title mb-1" style="font-size: 0.9rem; height: 2.5rem; overflow: hidden;">
                                            {{ $product->name }}
                                        </h6>

                                        <p class="card-text text-success fw-bold mb-1">
                                            Rp {{ number_format($product->selling_price, 0, ',', '.') }}
                                        </p>

                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <small class="text-muted">
                                                {{ $product->category->name ?? '-' }}
                                            </small>
                                            @if($product->stock <= 0)
                                                <span class="badge bg-danger stock-badge">Habis</span>
                                            @elseif($product->stock <= $product->min_stock)
                                                <span class="badge bg-warning stock-badge">Menipis</span>
                                            @else
                                                <span class="badge bg-success stock-badge">Tersedia</span>
                                            @endif
                                        </div>

                                        <small class="text-muted d-block">
                                            Stok: {{ $product->stock }} {{ $product->unit }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($products->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Tidak ada produk tersedia</h5>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Cart Section -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-shopping-cart me-2"></i>Keranjang Belanja
                        <span class="badge bg-warning ms-2" id="cartCount">0</span>
                    </h5>
                </div>
                <div class="card-body">
                    <div id="cartItems" class="mb-3">
                        <div class="empty-cart">
                            <i class="fas fa-shopping-cart fa-3x mb-3"></i>
                            <p>Keranjang belanja kosong</p>
                            <small class="text-muted">Klik produk untuk menambahkannya ke keranjang</small>
                        </div>
                    </div>

                    <div class="cart-totals">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-semibold">Subtotal:</span>
                            <span class="fw-semibold" id="subtotal">Rp 0</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Diskon:</span>
                            <span id="discount">Rp 0</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Pajak:</span>
                            <span id="tax">Rp 0</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3 fw-bold fs-5 text-success">
                            <span>TOTAL:</span>
                            <span id="total">Rp 0</span>
                        </div>

                        <!-- Payment Method -->
                        <div class="payment-section">
                            <label class="form-label fw-semibold">Metode Pembayaran</label>
                            <select class="form-select" id="paymentMethod">
                                <option value="cash">Tunai</option>
                                <option value="transfer">Transfer Bank</option>
                                <option value="debit">Kartu Debit</option>
                                <option value="credit">Kartu Kredit</option>
                            </select>
                        </div>

                        <!-- Cash Input -->
                        <div class="payment-section" id="cashInputSection">
                            <label class="form-label fw-semibold">Jumlah Bayar</label>
                            <input type="number" class="form-control form-control-lg" id="cashAmount" placeholder="0" min="0">
                            <small class="text-muted">Masukkan jumlah uang yang diterima</small>
                        </div>

                        <!-- Change -->
                        <div class="payment-section" id="changeSection" style="display: none;">
                            <div class="alert alert-success mb-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <strong>Kembalian:</strong>
                                    <strong id="changeAmount">Rp 0</strong>
                                </div>
                            </div>
                        </div>

                        <!-- Customer Info -->
                        <div class="payment-section">
                            <label class="form-label fw-semibold">Nama Pelanggan (Opsional)</label>
                            <input type="text" class="form-control" id="customerName" placeholder="Pelanggan Umum">
                        </div>

                        <!-- Actions -->
                        <div class="d-grid gap-2 mt-3">
                            <button class="btn btn-success btn-lg py-3" data-action="processPayment" id="checkoutBtn" disabled>
                                <i class="fas fa-check me-2"></i>PROSES PEMBAYARAN
                            </button>
                            <button class="btn btn-outline-danger" data-action="clearCart">
                                <i class="fas fa-trash me-2"></i>Kosongkan Keranjang
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div class="pos-loading" id="loadingOverlay" style="display: none;">
    <div class="spinner">
        <i class="fas fa-spinner fa-spin"></i>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Mendefinisikan variabel global yang akan digunakan oleh pos.js
    // Pastikan blok ini dieksekusi sebelum app.js dimuat.
    window.posProducts = {!! $products->toJson() !!};
    window.posRoutes = { store: '{{ route("pos.sales.store") }}' };
    window.csrfToken = '{{ csrf_token() }}';
</script>
@endpush
