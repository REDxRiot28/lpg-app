@extends('layouts.master')
@section('title', 'Tambah Vendor')

@section('content')
<div class="page-wrapper">
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h4 class="page-title">Tambah Vendor</h4>
                <div class="ms-auto text-end">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('vendor.index') }}">Vendor</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Tambah</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Form Tambah Vendor</h4>
                    </div>
                    <div class="card-body">
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="{{ route('vendor.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="kode_vendor" class="form-label">Kode Vendor <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               class="form-control @error('kode_vendor') is-invalid @enderror" 
                                               id="kode_vendor" 
                                               name="kode_vendor" 
                                               value="{{ old('kode_vendor') }}" 
                                               placeholder="Masukkan kode vendor"
                                               required>
                                        @error('kode_vendor')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="nama_vendor" class="form-label">Nama Vendor <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               class="form-control @error('nama_vendor') is-invalid @enderror" 
                                               id="nama_vendor" 
                                               name="nama_vendor" 
                                               value="{{ old('nama_vendor') }}" 
                                               placeholder="Masukkan nama vendor"
                                               required>
                                        @error('nama_vendor')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="alamat" class="form-label">Alamat <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('alamat') is-invalid @enderror" 
                                          id="alamat" 
                                          name="alamat" 
                                          rows="3" 
                                          placeholder="Masukkan alamat lengkap"
                                          required>{{ old('alamat') }}</textarea>
                                @error('alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="telepon" class="form-label">Telepon <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               class="form-control @error('telepon') is-invalid @enderror" 
                                               id="telepon" 
                                               name="telepon" 
                                               value="{{ old('telepon') }}" 
                                               placeholder="Masukkan nomor telepon"
                                               required>
                                        @error('telepon')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" 
                                               class="form-control @error('email') is-invalid @enderror" 
                                               id="email" 
                                               name="email" 
                                               value="{{ old('email') }}" 
                                               placeholder="Masukkan email">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="kontak_person" class="form-label">Kontak Person <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               class="form-control @error('kontak_person') is-invalid @enderror" 
                                               id="kontak_person" 
                                               name="kontak_person" 
                                               value="{{ old('kontak_person') }}" 
                                               placeholder="Masukkan nama kontak person"
                                               required>
                                        @error('kontak_person')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="status_aktif" class="form-label">Status <span class="text-danger">*</span></label>
                                        <select class="form-control @error('status_aktif') is-invalid @enderror" 
                                                id="status_aktif" 
                                                name="status_aktif" 
                                                required>
                                            <option value="">Pilih Status</option>
                                            <option value="1" {{ old('status_aktif') == '1' ? 'selected' : '' }}>Aktif</option>
                                            <option value="0" {{ old('status_aktif') == '0' ? 'selected' : '' }}>Tidak Aktif</option>
                                        </select>
                                        @error('status_aktif')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="mdi mdi-content-save"></i> Simpan
                                </button>
                                <a href="{{ route('vendor.index') }}" class="btn btn-secondary">
                                    <i class="mdi mdi-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
