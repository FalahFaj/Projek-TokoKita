<!-- resources/views/admin/suppliers/index.blade.php -->
@extends('layouts.app')

@section('title', 'Manajemen Supplier - TokoKita')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">
                    <i class="fas fa-truck me-2"></i>Manajemen Supplier
                </h4>
                <a href="{{ route('admin.suppliers.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Tambah Supplier
                </a>
            </div>
        </div>
    </div>

    <!-- Suppliers Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Total Supplier</h5>
                            <h2 class="mb-0">{{ $suppliers->count() }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-truck fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Supplier Aktif</h5>
                            <h2 class="mb-0">{{ $suppliers->where('is_active', true)->count() }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Total Produk</h5>
                            <h2 class="mb-0">{{ $suppliers->sum('products_count') }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-boxes fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Rata-rata Produk</h5>
                            <h2 class="mb-0">{{ round($suppliers->avg('products_count'), 1) }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-chart-bar fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Suppliers Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Daftar Supplier</h5>
                    <a href="{{ route('admin.suppliers.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i> Tambah Supplier
                    </a>
                </div>
                <div class="card-body">
                    @if($suppliers->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nama Supplier</th>
                                    <th>Perusahaan</th>
                                    <th>Kontak</th>
                                    <th>Lokasi</th>
                                    <th>Jumlah Produk</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($suppliers as $supplier)
                                <tr>
                                    <td>
                                        <strong>{{ $supplier->name }}</strong>
                                        @if($supplier->company_name)
                                            <br>
                                            <small class="text-muted">{{ $supplier->company_name }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $supplier->company_name ?: '-' }}
                                    </td>
                                    <td>
                                        @if($supplier->email)
                                            <div>
                                                <i class="fas fa-envelope text-muted me-1"></i>
                                                <small>{{ $supplier->email }}</small>
                                            </div>
                                        @endif
                                        @if($supplier->phone)
                                            <div>
                                                <i class="fas fa-phone text-muted me-1"></i>
                                                <small>{{ $supplier->phone }}</small>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <small>
                                            {{ $supplier->city }}
                                            @if($supplier->address)
                                                <br>
                                                <span class="text-muted">{{ Str::limit($supplier->address, 30) }}</span>
                                            @endif
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $supplier->products_count > 0 ? 'primary' : 'secondary' }}">
                                            {{ $supplier->products_count }} produk
                                        </span>
                                    </td>
                                    <td>
                                        @if($supplier->is_active)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.suppliers.show', $supplier) }}"
                                               class="btn btn-sm btn-info" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.suppliers.edit', $supplier) }}"
                                               class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.suppliers.destroy', $supplier) }}"
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Hapus supplier ini?')"
                                                        {{ $supplier->products_count > 0 ? 'disabled' : '' }}
                                                        title="{{ $supplier->products_count > 0 ? 'Tidak dapat menghapus supplier yang memiliki produk' : 'Hapus' }}">
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
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-truck fa-3x text-muted mb-3"></i>
                        <h5>Belum Ada Supplier</h5>
                        <p class="text-muted">Mulai dengan menambahkan supplier pertama Anda.</p>
                        <a href="{{ route('admin.suppliers.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i> Tambah Supplier Pertama
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
