@extends('layouts.master')
@section('title', 'Detail Vendor')

@section('content')
<div class="page-wrapper">
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h4 class="page-title">Detail Vendor</h4>
                <div class="ms-auto text-end">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('vendor.index') }}">Vendor</a></li>
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
                        <h4 class="card-title">Informasi Vendor</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td width="200"><strong>ID</strong></td>
                                <td>: {{ $vendor->id }}</td>
                            </tr>
                            <tr>
                                <td><strong>Kode Vendor</strong></td>
                                <td>: {{ $vendor->kode_vendor }}</td>
                            </tr>
                            <tr>
                                <td><strong>Nama Vendor</strong></td>
                                <td>: {{ $vendor->nama_vendor }}</td>
                            </tr>
                            <tr>
                                <td><strong>Alamat</strong></td>
                                <td>: {{ $vendor->alamat }}</td>
                            </tr>
                            <tr>
                                <td><strong>Telepon</strong></td>
                                <td>: {{ $vendor->telepon }}</td>
                            </tr>
                            <tr>
                                <td><strong>Email</strong></td>
                                <td>: {{ $vendor->email ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Kontak Person</strong></td>
                                <td>: {{ $vendor->kontak_person }}</td>
                            </tr>
                            <tr>
                                <td><strong>Status</strong></td>
                                <td>: 
                                    @if($vendor->status_aktif)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Tidak Aktif</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Dibuat</strong></td>
                                <td>: {{ $vendor->created_at->format('d F Y H:i:s') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Terakhir Diupdate</strong></td>
                                <td>: {{ $vendor->updated_at->format('d F Y H:i:s') }}</td>
                            </tr>
                        </table>

                        <div class="mt-4">
                            <a href="{{ route('vendor.edit', $vendor->id) }}" class="btn btn-warning">
                                <i class="mdi mdi-pencil"></i> Edit
                            </a>
                            <a href="{{ route('vendor.index') }}" class="btn btn-secondary">
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
