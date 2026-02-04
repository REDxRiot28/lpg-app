@extends('layouts.master')
@section('title', 'Barang')

@section('content')
    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-12 d-flex no-block align-items-center">
                    <h4 class="page-title">Barang</h4>
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
                                <h4 class="card-title mb-0">Data Barang</h4>
                                <a href="{{ route('barangs.create') }}" class="btn btn-success">
                                    <i class="mdi mdi-plus"></i> Tambah Barang
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
                                <table id="barangsTable" class="table table-hover table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Barang</th>
                                            <th>Kode</th>
                                            <th>Harga</th>
                                            <th>Tanggal Dibuat</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($barangs as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->namabarang }}</td>
                                                <td>{{ $item->kode }}</td>
                                                <td>{{ 'Rp ' . number_format($item->harga, 0, ',', '.') }}</td>
                                                <td>{{ optional($item->created_at)->format('d/m/Y H:i') }}</td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('barangs.edit', $item->id) }}"
                                                            class="btn btn-warning btn-sm" title="Edit">
                                                            <i class="mdi mdi-pencil"></i>
                                                        </a>
                                                        <form action="{{ route('barangs.destroy', $item->id) }}"
                                                            method="POST" class="d-inline"
                                                            onsubmit="return confirm('Yakin ingin menghapus {{ $item->namabarang }}?')">
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

                            {{-- Kalau pakai paginate di controller, bisa tampilkan link --}}
                            @if(method_exists($barangs, 'links'))
                                <div class="mt-3">
                                    {{ $barangs->links() }}
                                </div>
                            @endif
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
            $('#barangsTable').DataTable({
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
                },
                pageLength: 10,
                order: [[4, "desc"]],
                columnDefs: [{ orderable: false, targets: 5 }]
            });
        });
    </script>
@endpush
