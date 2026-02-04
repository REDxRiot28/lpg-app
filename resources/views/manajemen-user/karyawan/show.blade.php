@extends('layouts.master')
@section('title', 'Detail Karyawan')

@section('content')
<div class="page-wrapper">
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h4 class="page-title">Detail Karyawan</h4>
                <div class="ms-auto text-end">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('karyawan.index') }}">Karyawan</a></li>
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
                        <h4 class="card-title">Informasi User</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td width="200"><strong>ID</strong></td>
                                <td>: {{ $karyawans->id }}</td>
                            </tr>
                            <tr>
                                <td><strong>Nama Lengkap</strong></td>
                                <td>: {{ $karyawans->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Username</strong></td>
                                <td>: {{ $karyawans->username }}</td>
                            </tr>
                            <tr>
                                <td><strong>Email</strong></td>
                                <td>: {{ $karyawans->email }}</td>
                            </tr>
                            <tr>
                                <td><strong>Role</strong></td>
                                <td>: 
                                    @if($karyawans->role == 'super-admin')
                                        <span class="badge bg-danger">Super Admin</span>
                                    @elseif($karyawans->role == 'owner')
                                        <span class="badge bg-warning">Owner</span>
                                    @else
                                        <span class="badge bg-info">Kasir</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Status</strong></td>
                                <td>: 
                                    @if($karyawans->status_aktif)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Tidak Aktif</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Dibuat</strong></td>
                                <td>: {{ $karyawans->created_at->format('d F Y H:i:s') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Terakhir Diupdate</strong></td>
                                <td>: {{ $karyawans->updated_at->format('d F Y H:i:s') }}</td>
                            </tr>
                        </table>

                        <div class="mt-4">
                            <a href="{{ route('karyawan.edit', $karyawans->id) }}" class="btn btn-warning">
                                <i class="mdi mdi-pencil"></i> Edit
                            </a>
                            <a href="{{ route('karyawan.index') }}" class="btn btn-secondary">
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
