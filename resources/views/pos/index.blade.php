<!-- resources/views/pos/index.blade.php -->
@extends('layouts.app')

@section('title', 'POS - TokoKita')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-cash-register me-2"></i>Point of Sale (POS)
                    </h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Selamat datang di sistem POS, <strong>{{ $user->name }}</strong>!
                        <br>Anda login sebagai <strong>{{ $user->role_name }}</strong>
                    </div>

                    <div class="text-center py-5">
                        <i class="fas fa-cash-register fa-4x text-success mb-3"></i>
                        <h4>Sistem POS TokoKita</h4>
                        <p class="text-muted">Fitur POS akan segera tersedia</p>

                        <div class="mt-4">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <i class="fas fa-search fa-2x text-primary mb-2"></i>
                                            <h6>Cari Produk</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <i class="fas fa-shopping-cart fa-2x text-warning mb-2"></i>
                                            <h6>Keranjang Belanja</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <i class="fas fa-receipt fa-2x text-success mb-2"></i>
                                            <h6>Cetak Struk</h6>
                                        </div>
                                    </div>
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
