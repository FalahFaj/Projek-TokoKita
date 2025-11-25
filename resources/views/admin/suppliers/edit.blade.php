@extends('layouts.app')

@section('title', 'Edit Supplier - TokoKita')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">
                    <i class="fas fa-edit me-2"></i>Edit Supplier: {{ $supplier->name }}
                </h4>
                <a href="{{ route('admin.suppliers.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Form Edit Supplier</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.suppliers.update', $supplier) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <!-- Personal Information -->
                            <div class="col-md-6">
                                <h6 class="border-bottom pb-2 mb-3">Informasi Pribadi</h6>

                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Supplier <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name" value="{{ old('name', $supplier->name) }}"
                                           placeholder="Masukkan nama supplier" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="company_name" class="form-label">Nama Perusahaan</label>
                                    <input type="text" class="form-control @error('company_name') is-invalid @enderror"
                                           id="company_name" name="company_name" value="{{ old('company_name', $supplier->company_name) }}"
                                           placeholder="Masukkan nama perusahaan (opsional)">
                                    @error('company_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="tax_number" class="form-label">NPWP</label>
                                    <input type="text" class="form-control @error('tax_number') is-invalid @enderror"
                                           id="tax_number" name="tax_number" value="{{ old('tax_number', $supplier->tax_number) }}"
                                           placeholder="Masukkan NPWP (opsional)">
                                    @error('tax_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Contact Information -->
                            <div class="col-md-6">
                                <h6 class="border-bottom pb-2 mb-3">Informasi Kontak</h6>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                           id="email" name="email" value="{{ old('email', $supplier->email) }}"
                                           placeholder="Masukkan email supplier">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="phone" class="form-label">Telepon <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                           id="phone" name="phone" value="{{ old('phone', $supplier->phone) }}"
                                           placeholder="Masukkan nomor telepon" required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_active"
                                               name="is_active" value="1"
                                               {{ old('is_active', $supplier->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Supplier Aktif
                                        </label>
                                    </div>
                                    <small class="text-muted">
                                        Jika dinonaktifkan, supplier tidak akan muncul di pilihan produk baru.
                                    </small>
                                </div>
                            </div>

                            <!-- Address Information -->
                            <div class="col-12">
                                <h6 class="border-bottom pb-2 mb-3">Informasi Alamat</h6>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="address" class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                                        <textarea class="form-control @error('address') is-invalid @enderror"
                                                  id="address" name="address" rows="3"
                                                  placeholder="Masukkan alamat lengkap supplier" required>{{ old('address', $supplier->address) }}</textarea>
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="city" class="form-label">Kota <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('city') is-invalid @enderror"
                                               id="city" name="city" value="{{ old('city', $supplier->city) }}"
                                               placeholder="Masukkan kota" required>
                                        @error('city')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="col-12">
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end border-top pt-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i> Update Supplier
                                    </button>
                                    <a href="{{ route('admin.suppliers.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times me-1"></i> Batal
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="card mt-4 border-danger">
                <div class="card-header bg-danger text-white">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>Zona Berbahaya
                    </h6>
                </div>
                <div class="card-body">
                    <h6>Hapus Supplier</h6>
                    <p class="text-muted">
                        @if($supplier->products_count > 0)
                            Supplier ini memiliki {{ $supplier->products_count }} produk dan tidak dapat dihapus.
                            Pindahkan atau hapus semua produk terlebih dahulu.
                        @else
                        Tindakan ini akan menghapus supplier secara permanen. Tindakan ini tidak dapat dibatalkan.
                        @endif
                    </p>

                    @if($supplier->products_count == 0)
                    <form action="{{ route('admin.suppliers.destroy', $supplier) }}" method="POST"
                          onsubmit="return confirm('Yakin ingin menghapus supplier ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-1"></i> Hapus Supplier
                        </button>
                    </form>
                    @else
                    <button type="button" class="btn btn-danger" disabled>
                        <i class="fas fa-trash me-1"></i> Tidak Dapat Dihapus
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
