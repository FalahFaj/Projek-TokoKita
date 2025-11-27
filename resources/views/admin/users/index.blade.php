<!-- resources/views/admin/users/index.blade.php -->
@extends('layouts.app')

@section('title', 'Manajemen User - TokoKita')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">
                    <i class="fas fa-users me-2"></i>Manajemen User
                </h4>
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Tambah User
                </a>
            </div>
        </div>
    </div>

    <!-- Users Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Total User</h5>
                            <h2 class="mb-0">{{ $users->count() }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-users fa-2x opacity-50"></i>
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
                            <h5 class="card-title">Admin</h5>
                            <h2 class="mb-0">{{ $users->where('role', 'admin')->count() }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-user-shield fa-2x opacity-50"></i>
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
                            <h5 class="card-title">Kasir</h5>
                            <h2 class="mb-0">{{ $users->where('role', 'kasir')->count() }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-cash-register fa-2x opacity-50"></i>
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
                            <h5 class="card-title">Nonaktif</h5>
                            <h2 class="mb-0">{{ $users->whereNotNull('deleted_at')->count() }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-user-slash fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Daftar User</h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-sync me-1"></i> Refresh
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($users->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nama User</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Telepon</th>
                                    <th>Status</th>
                                    <th>Terakhir Login</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr class="{{ $user->trashed() ? 'table-secondary' : '' }}">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar bg-primary text-white rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-size: 14px;">
                                                {{ $user->initials }}
                                            </div>
                                            <div>
                                                <strong>{{ $user->name }}</strong>
                                                @if($user->trashed())
                                                    <br><small class="text-muted">Deleted: {{ $user->deleted_at->format('d M Y') }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="badge bg-{{ $user->role === 'owner' ? 'danger' : ($user->role === 'admin' ? 'warning' : 'info') }}">
                                            {{ $user->role_name }}
                                        </span>
                                    </td>
                                    <td>{{ $user->phone ?? '-' }}</td>
                                    <td>
                                        @if($user->trashed())
                                            <span class="badge bg-danger">Nonaktif</span>
                                        @else
                                            <span class="badge bg-success">Aktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->last_login_at)
                                            <small>{{ $user->last_login_at->format('d M Y H:i') }}</small>
                                        @else
                                            <span class="text-muted">Belum login</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.users.show', $user) }}"
                                               class="btn btn-sm btn-info" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if(!$user->trashed() && !$user->isOwner())
                                            <a href="{{ route('admin.users.edit', $user) }}"
                                               class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.users.destroy', $user) }}"
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Nonaktifkan user ini?')" title="Nonaktifkan">
                                                    <i class="fas fa-user-slash"></i>
                                                </button>
                                            </form>
                                            @endif

                                            @if($user->trashed())
                                            <form action="{{ route('admin.users.restore', $user) }}"
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-success"
                                                        onclick="return confirm('Aktifkan kembali user ini?')" title="Aktifkan">
                                                    <i class="fas fa-user-check"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.users.force-delete', $user) }}"
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Hapus permanen user ini? Tindakan ini tidak dapat dibatalkan!')" title="Hapus Permanen">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                        <h5>Belum Ada User</h5>
                        <p class="text-muted">Mulai dengan menambahkan user pertama Anda.</p>
                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i> Tambah User Pertama
                        </a>
                    </div>
                    @endif
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
