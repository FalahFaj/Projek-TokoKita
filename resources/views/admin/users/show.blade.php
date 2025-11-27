<!-- resources/views/admin/users/show.blade.php -->
@extends('layouts.app')

@section('title', 'Detail User - TokoKita')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">
                    <i class="fas fa-user me-2"></i>Detail User: {{ $user->name }}
                </h4>
                <div>
                    @if(!$user->trashed() && !$user->isOwner())
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-1"></i> Edit
                    </a>
                    @endif
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- User Info -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informasi User</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center mb-4">
                            <div class="user-avatar bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center"
                                 style="width: 100px; height: 100px; font-size: 36px;">
                                {{ $user->initials }}
                            </div>
                            @if($user->trashed())
                            <div class="mt-2">
                                <span class="badge bg-danger">Nonaktif</span>
                            </div>
                            @endif
                        </div>
                        <div class="col-md-9">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Nama Lengkap</th>
                                    <td>{{ $user->name }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <th>Role</th>
                                    <td>
                                        <span class="badge bg-{{ $user->role === 'owner' ? 'danger' : ($user->role === 'admin' ? 'warning' : 'info') }}">
                                            {{ $user->role_name }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Telepon</th>
                                    <td>{{ $user->phone ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Alamat</th>
                                    <td>{{ $user->address ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activity Information -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informasi Aktivitas</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <th>Dibuat Pada</th>
                                    <td>{{ $user->created_at->format('d M Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Diupdate Pada</th>
                                    <td>{{ $user->updated_at->format('d M Y H:i') }}</td>
                                </tr>
                                @if($user->trashed())
                                <tr>
                                    <th>Dinonaktifkan Pada</th>
                                    <td>{{ $user->deleted_at->format('d M Y H:i') }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <th>Terakhir Login</th>
                                    <td>
                                        @if($user->last_login_at)
                                            {{ $user->last_login_at->format('d M Y H:i') }}
                                        @else
                                            <span class="text-muted">Belum login</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Total Transaksi</th>
                                    <td>{{ $user->transactions_count ?? 0 }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        @if($user->trashed())
                                            <span class="badge bg-danger">Nonaktif</span>
                                        @else
                                            <span class="badge bg-success">Aktif</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
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
                        @if(!$user->trashed() && !$user->isOwner())
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-1"></i> Edit User
                        </a>
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100"
                                    onclick="return confirm('Nonaktifkan user ini?')">
                                <i class="fas fa-user-slash me-1"></i> Nonaktifkan
                            </button>
                        </form>
                        @endif

                        @if($user->trashed())
                        <form action="{{ route('admin.users.restore', $user) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success w-100"
                                    onclick="return confirm('Aktifkan kembali user ini?')">
                                <i class="fas fa-user-check me-1"></i> Aktifkan Kembali
                            </button>
                        </form>
                        <form action="{{ route('admin.users.force-delete', $user) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100"
                                    onclick="return confirm('Hapus permanen user ini? Tindakan ini tidak dapat dibatalkan!')">
                                <i class="fas fa-trash me-1"></i> Hapus Permanen
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- User Stats -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Statistik User</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Total Transaksi:</strong>
                        <span class="float-end">{{ $user->transactions_count ?? 0 }}</span>
                    </div>
                    <div class="mb-3">
                        <strong>Transaksi Bulan Ini:</strong>
                        <span class="float-end">
                            {{ $user->transactions()->whereMonth('created_at', now()->month)->count() }}
                        </span>
                    </div>
                    <div class="mb-3">
                        <strong>Total Pendapatan:</strong>
                        <span class="float-end text-success">
                            Rp {{ number_format($user->transactions()->sum('total_amount'), 0, ',', '.') }}
                        </span>
                    </div>
                    <hr>
                    <div class="mb-0">
                        <strong>Bergabung Sejak:</strong>
                        <span class="float-end">{{ $user->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .user-avatar {
        font-weight: bold;
    }
</style>
@endsection
