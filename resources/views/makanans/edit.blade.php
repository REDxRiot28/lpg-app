@extends('layouts.master')
@section('title', 'Edit Makanan')

@section('content')
    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-12 d-flex no-block align-items-center">
                    <h4 class="page-title">Edit Makanan</h4>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header"
                            style="background: linear-gradient(135deg, #6f42c1, #007bff); color: white;">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="card-title mb-0">Form Edit Makanan</h4>
                                <a href="{{ route('makanans.index') }}" class="btn btn-light">
                                    <i class="mdi mdi-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            <form action="{{ route('makanans.update', $makanan->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="form-group">
                                    <label for="namamakanan">Nama Makanan</label>
                                    <input type="text" name="namamakanan" id="namamakanan"
                                           class="form-control @error('namamakanan') is-invalid @enderror"
                                           value="{{ old('namamakanan', $makanan->namamakanan) }}"
                                           placeholder="Masukkan nama makanan">
                                    @error('namamakanan')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="kode">Kode Makanan</label>
                                    <input type="text" name="kode" id="kode"
                                           class="form-control @error('kode') is-invalid @enderror"
                                           value="{{ old('kode', $makanan->kode) }}"
                                           placeholder="Masukkan kode makanan">
                                    @error('kode')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="harga">Harga</label>
                                    <input type="number" name="harga" id="harga" step="0.01"
                                           class="form-control @error('harga') is-invalid @enderror"
                                           value="{{ old('harga', $makanan->harga) }}"
                                           placeholder="Masukkan harga">
                                    @error('harga')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="mdi mdi-content-save"></i> Update
                                    </button>
                                    <a href="{{ route('makanans.index') }}" class="btn btn-secondary">
                                        Batal
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
