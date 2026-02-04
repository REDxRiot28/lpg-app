@extends('layouts.master')

@section('title', 'Manajemen Pelanggan')

@section('content')
<div class="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="mdi mdi-account-group me-2"></i>Daftar Pelanggan</h5>
                        <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#modalTambah">
                            <i class="mdi mdi-plus"></i> Tambah Pelanggan
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="pelangganTable" class="table table-bordered table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Pelanggan</th>
                                        <th>No KK</th>
                                        <th>No Telepon</th>
                                        <th>Alamat</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pelanggans as $p)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $p->nama }}</td>
                                        <td>{{ $p->no_kk ?? '-' }}</td>
                                        <td>{{ $p->no_telp ?? '-' }}</td>
                                        <td>{{ $p->alamat ?? '-' }}</td>
                                        <td>
                                            <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $p->id }}">
                                                <i class="mdi mdi-pencil"></i>
                                            </button>
                                            <form action="{{ route('pelanggan.destroy', $p->id) }}" method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-danger" onclick="return confirm('Hapus pelanggan ini?')">
                                                    <i class="mdi mdi-delete"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>

                                    <div class="modal fade" id="modalEdit{{ $p->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form action="{{ route('pelanggan.update', $p->id) }}" method="POST">
                                                @csrf @method('PUT')
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Pelanggan</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label>Nama Pelanggan</label>
                                                            <input type="text" name="nama" class="form-control" value="{{ $p->nama }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label>No KK</label>
                                                            <input type="text" name="no_kk" class="form-control" value="{{ $p->no_kk }}">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label>No Telp</label>
                                                            <input type="text" name="no_telp" class="form-control" value="{{ $p->no_telp }}">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label>Alamat</label>
                                                            <textarea name="alamat" class="form-control">{{ $p->alamat }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
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

<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('pelanggan.store') }}" method="POST">
            @csrf
            <div class="modal-content text-dark">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Tambah Pelanggan Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nama Pelanggan</label>
                        <input type="text" name="nama" class="form-control" required placeholder="Masukkan nama lengkap">
                    </div>
                    <div class="mb-3">
                        <label>No KK</label>
                        <input type="text" name="no_kk" class="form-control" placeholder="Masukkan nomor kartu keluarga">
                    </div>
                    <div class="mb-3">
                        <label>No Telp</label>
                        <input type="text" name="no_telp" class="form-control" placeholder="Contoh: 08123456789">
                    </div>
                    <div class="mb-3">
                        <label>Alamat</label>
                        <textarea name="alamat" class="form-control" placeholder="Alamat lengkap..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Pelanggan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#pelangganTable').DataTable({
            "language": { "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json" }
        });
    });
</script>
@endpush