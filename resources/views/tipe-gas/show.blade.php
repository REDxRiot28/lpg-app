@extends('layouts.master')
@section('title', 'Detail Tipe Gas')

@section('content')
<div class="page-wrapper">
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h4 class="page-title">Detail Tipe Gas</h4>
                <div class="ms-auto text-end">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('tipe-gas.index') }}">Tipe Gas</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Detail</li>
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
                        <h4 class="card-title">Informasi Tipe Gas</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td width="200"><strong>ID</strong></td>
                                <td>: {{ $tipeGas->id }}</td>
                            </tr>
                            <tr>
                                <td><strong>Nama Tipe Gas</strong></td>
                                <td>: {{ $tipeGas->nama }}</td>
                            </tr>
                            {{-- TAMBAHKAN BARIS INI --}}
                            <tr>
                                <td><strong>Harga Jual Standar</strong></td>
                                <td>: <strong>Rp {{ number_format($tipeGas->harga_jual, 0, ',', '.') }}</strong></td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Dibuat</strong></td>
                                <td>: {{ $tipeGas->created_at->format('d F Y H:i:s') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Terakhir Diupdate</strong></td>
                                <td>: {{ $tipeGas->updated_at->format('d F Y H:i:s') }}</td>
                            </tr>
                        </table>

                        <div class="mt-4">
                            <a href="{{ route('tipe-gas.edit', $tipeGas->id) }}" class="btn btn-warning">
                                <i class="mdi mdi-pencil"></i> Edit
                            </a>
                            <a href="{{ route('tipe-gas.index') }}" class="btn btn-secondary">
                                <i class="mdi mdi-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection