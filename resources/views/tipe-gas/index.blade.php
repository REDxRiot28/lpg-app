@extends('layouts.master')
@section('title', 'Tipe Gas')

@section('content')
    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-12 d-flex no-block align-items-center">
                    <h4 class="page-title">Tipe Gas</h4>
                    
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
                                <h4 class="card-title mb-0">Data Tipe Gas</h4>
                                <a href="{{ route('tipe-gas.create') }}" class="btn btn-success">
                                    <i class="mdi mdi-plus"></i> Tambah Tipe Gas
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            {{-- Notifikasi --}}
                            @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif
                            @if (session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif

                            <div class="table-responsive">
                                <table id="tipeGasTable" class="table table-hover table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Tipe Gas</th>
                                            <th>Harga Jual</th>
                                            <th>Tanggal Dibuat</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($tipeGas as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->nama }}</td>
                                                <td>{{ 'Rp ' . number_format($item->harga_jual, 0, ',', '.') }}</td>
                                                <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('tipe-gas.edit', $item->id) }}"
                                                            class="btn btn-warning btn-sm" title="Edit">
                                                            <i class="mdi mdi-pencil"></i>
                                                        </a>
                                                        <form action="{{ route('tipe-gas.destroy', $item->id) }}"
                                                            method="POST" class="d-inline"
                                                            onsubmit="return confirm('Yakin ingin menghapus {{ $item->nama }}?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                                                <i class="mdi mdi-delete"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#tipeGasTable').DataTable({
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
                },
                pageLength: 10,
                order: [[3, "desc"]],
                columnDefs: [{ orderable: false, targets: 4 }]
            });
        });
    </script>
@endpush
