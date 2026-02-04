@extends('layouts.master')
@section('title', 'Manajemen Karyawan')

@section('content')
<div class="page-wrapper">
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h4 class="page-title">Manajemen Karyawan</h4>
                <div class="ms-auto text-end">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Karyawan</li>
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
                            <h4 class="card-title mb-0">Data Karyawan</h4>
                            <a href="{{ route('karyawan.create') }}" class="btn btn-success">
                                <i class="mdi mdi-plus"></i> Tambah Karyawan
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
                            <table id="usersTable" class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Tanggal Dibuat</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($karyawans as $index => $karyawan)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $karyawan->name }}</td>
                                        <td>{{ $karyawan->username }}</td>
                                        <td>{{ $karyawan->email }}</td>
                                        <td>
                                            @if($karyawan->role == 'super-admin')
                                                <span class="badge bg-danger">Super Admin</span>
                                            @elseif($karyawan->role == 'owner')
                                                <span class="badge bg-warning">Owner</span>
                                            @else
                                                <span class="badge bg-info">Kasir</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($karyawan->status_aktif)
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-secondary">Tidak Aktif</span>
                                            @endif
                                        </td>
                                        <td>{{ $karyawan->created_at->format('d/m/Y') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('karyawan.show', $karyawan->id) }}" 
                                                   class="btn btn-info btn-sm" title="Detail">
                                                    <i class="mdi mdi-eye"></i>
                                                </a>
                                                <a href="{{ route('karyawan.edit', $karyawan->id) }}" 
                                                   class="btn btn-warning btn-sm" title="Edit">
                                                    <i class="mdi mdi-pencil"></i>
                                                </a>
                                                @if($karyawan->id != auth()->id())
                                                <button type="button" class="btn btn-danger btn-sm" 
                                                        onclick="confirmDelete({{ $karyawan->id }}, '{{ $karyawan->name }}')" 
                                                        title="Hapus">
                                                    <i class="mdi mdi-delete"></i>
                                                </button>
                                                @endif
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
                <p>Apakah Anda yakin ingin menghapus Karyawan <strong id="itemName"></strong>?</p>
                <p class="text-danger">Tindakan ini tidak dapat dibatalkan!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="GET" style="display: inline;">
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
    $('#usersTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        },
        "pageLength": 25,
        "order": [[ 6, "desc" ]],
        "columnDefs": [
            { "orderable": false, "targets": [0, 7] }
        ]
    });
});

function confirmDelete(id, name) {
    document.getElementById('itemName').textContent = name;
    document.getElementById('deleteForm').action = '{{ route("karyawan.delete", "") }}/' + id;
    
    var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}
</script>
@endpush
