@extends('layouts.master')
@section('title', 'Tambah Tipe Gas')

@section('content')
    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-12 d-flex no-block align-items-center">
                    <h4 class="page-title">Tambah Tipe Gas</h4>
                    <div class="ms-auto text-end">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('tipe-gas.index') }}">Tipe Gas</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Tambah</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Form Tambah Tipe Gas</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('tipe-gas.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="nama">Nama Tipe Gas</label>
                                    <input type="text" name="nama" class="form-control" value="{{ old('nama') }}">
                                    @error('nama')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="harga_jual">Harga Jual</label>
                                    <input type="number" name="harga_jual" class="form-control"
                                        value="{{ old('harga_jual') }}">
                                    @error('harga_jual')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <a href="{{ route('tipe-gas.index') }}" class="btn btn-secondary">Batal</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
