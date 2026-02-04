@extends('layouts.master')
@section('title', 'Vendor')

@section('content')
<div class="page-wrapper">
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h4 class="page-title">Vendor</h4>
                <div class="ms-auto text-end">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Vendor</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header" style="background: linear-gradient(135deg, #6f42c1, #007bff); color: white;">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">Data Vendor</h4>
                            <a href="{{ route('vendor.create') }}" class="btn btn-success">
                                <i class="mdi mdi-plus"></i> Tambah Vendor
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table id="vendorTable" class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Vendor</th>
                                        <th>Nama Vendor</th>
                                        <th>Telepon</th>
                                        <th>Email</th>
                                        <th>Kontak Person</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($vendors as $index => $vendor)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $vendor->kode_vendor }}</td>
                                        <td>{{ $vendor->nama_vendor }}</td>
                                        <td>{{ $vendor->telepon }}</td>
                                        <td>{{ $vendor->email ?? '-' }}</td>
                                        <td>{{ $vendor->kontak_person }}</td>
                                        <td>
                                            @if($vendor->status_aktif)
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-secondary">Tidak Aktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('vendor.show', $vendor->id) }}" 
                                                   class="btn btn-info btn-sm" title="Detail">
                                                    <i class="mdi mdi-eye"></i>
                                                </a>
                                                <a href="{{ route('vendor.edit', $vendor->id) }}" 
                                                   class="btn btn-warning btn-sm" title="Edit">
                                                    <i class="mdi mdi-pencil"></i>
                                                </a>
                                                <button type="button" class="btn btn-danger btn-sm" 
                                                        onclick="confirmDelete({{ $vendor->id }}, '{{ $vendor->nama_vendor }}')" 
                                                        title="Hapus">
                                                    <i class="mdi mdi-delete"></i>
                                                </button>
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

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus vendor <strong id="itemName"></strong>?</p>
                <p class="text-danger">Tindakan ini tidak dapat dibatalkan!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#vendorTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        },
        "pageLength": 25,
        "order": [[ 1, "asc" ]],
        "columnDefs": [
            { "orderable": false, "targets": [0, 7] }
        ]
    });
});

function confirmDelete(id, name) {
    document.getElementById('itemName').textContent = name;

    let url = "{{ route('vendor.delete', ':id') }}";
    url = url.replace(':id', id);

    document.getElementById('deleteForm').action = url;

    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

</script>
@endpush
