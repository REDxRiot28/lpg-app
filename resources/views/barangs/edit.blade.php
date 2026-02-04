@extends('layouts.master')
@section('title', 'Edit Barang')

@section('content')
    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-12 d-flex no-block align-items-center">
                    <h4 class="page-title">Edit Barang</h4>
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
                                <h4 class="card-title mb-0">Form Edit Barang</h4>
                                <a href="{{ route('barangs.index') }}" class="btn btn-light">
                                    <i class="mdi mdi-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            <form action="{{ route('barangs.update', $barang->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="form-group">
                                    <label for="namabarang">Nama Barang</label>
                                    <input type="text" name="namabarang" id="namabarang"
                                           class="form-control @error('namabarang') is-invalid @enderror"
                                           value="{{ old('namabarang', $barang->namabarang) }}"
                                           placeholder="Masukkan nama barang">
                                    @error('namabarang')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="kode">Kode Barang</label>
                                    <input type="text" name="kode" id="kode"
                                           class="form-control @error('kode') is-invalid @enderror"
                                           value="{{ old('kode', $barang->kode) }}"
                                           placeholder="Masukkan kode barang">
                                    @error('kode')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="harga">Harga</label>
                                    <input type="number" name="harga" id="harga" step="0.01"
                                           class="form-control @error('harga') is-invalid @enderror"
                                           value="{{ old('harga', $barang->harga) }}"
                                           placeholder="Masukkan harga">
                                    @error('harga')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="mdi mdi-content-save"></i> Update
                                    </button>
                                    <a href="{{ route('barangs.index') }}" class="btn btn-secondary">
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
