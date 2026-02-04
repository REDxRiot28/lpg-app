@extends('layouts.master')

@section('title', 'Pengembalian Gas')


@section('content')

    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-6 align-self-center">
                    <h4 class="page-title">Transaksi Pengembalian Gas</h4>
                </div>
            </div>
        </div>

        <div class="container-fluid">

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card shadow-lg border-0 h-100">
                        <div class="card-body bg-gradient-success text-white rounded">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h5>Pengembalian Hari Ini</h5>
                                    <h3>{{ $totalPengembalianHariIni }}</h3>
                                </div>
                                <div class="ms-3"><i class="mdi mdi-backup-restore display-4 opacity-75"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card shadow-lg border-0 h-100">
                        <div class="card-body bg-gradient-info text-white rounded">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h5>Pengembalian Bulan Ini</h5>
                                    <h3>{{ $totalPengembalianBlnIni }}</h3>
                                </div>
                                <div class="ms-3"><i class="mdi mdi-calendar-check display-4 opacity-75"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                @foreach ($stokPengembalianPerTipe as $item)
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card shadow-lg border-0 h-100">
                            <div class="card-body bg-gradient-secondary text-white rounded">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <h5>Stok Kembali: {{ $item->tipeGas->nama }}</h5>
                                        <h3>{{ $item->total_pengembalian ?? 0 }} Tabung</h3>
                                    </div>
                                    <div class="ms-3"><i class="mdi mdi-gas-cylinder display-4 opacity-75"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- Blok Stok Rusak Per Tipe Dihapus --}}
            </div>

            <div class="row">
                <div class="col-md-12 mb-4">
                    <div class="card shadow-lg border-0">
                        <div class="card-header"
                            style="background: linear-gradient(135deg, #17a2b8, #20c997); color: white;">
                            <h5 class="mb-0"><i class="mdi mdi-backup-restore me-2"></i>Form Pengembalian Gas dari
                                Pelanggan</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('transaksi.pengembalian.store') }}" method="POST"
                                id="form-pengembalian">
                                @csrf

                                <div class="border p-3 mb-4 rounded shadow-sm" style="background-color: #f8f9fa;">
                                    <h6 class="mb-3 text-primary"><i class="mdi mdi-account-circle me-1"></i>Data Pembeli
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <div class="form-group"><label for="nama_pembeli" class="form-label">Nama
                                                    Pembeli</label><input type="text" id="nama_pembeli"
                                                    name="nama_pembeli" class="form-control"
                                                    value="{{ old('nama_pembeli') }}" required></div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="form-group"><label for="no_kk" class="form-label">No.
                                                    KK</label><input type="number" id="no_kk" name="no_kk"
                                                    class="form-control" value="{{ old('no_kk') }}"></div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="form-group"><label for="no_telp" class="form-label">No.
                                                    Telepon</label><input type="number" id="no_telp" name="no_telp"
                                                    class="form-control" value="{{ old('no_telp') }}"></div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="form-group"><label for="tanggal_pengembalian"
                                                    class="form-label">Tanggal Pengembalian</label><input type="date"
                                                    id="tanggal_pengembalian" name="tanggal_pengembalian"
                                                    class="form-control"
                                                    value="{{ old('tanggal_pengembalian', date('Y-m-d')) }}" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group"><label for="keterangan" class="form-label">Keterangan
                                            (Opsional)</label>
                                        <textarea id="keterangan" name="keterangan" class="form-control" rows="2">{{ old('keterangan') }}</textarea>
                                    </div>
                                </div>

                                <h6 class="mb-3 text-success"><i class="mdi mdi-gas-cylinder me-1"></i>Detail Produk yang
                                    Dikembalikan</h6>
                                <div id="pengembalian-container">
                                    {{-- Item Pertama --}}
                                    <div class="pengembalian-item border p-3 mb-3 rounded shadow-sm" data-item="0"
                                        style="background-color: #fcfdff;">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <p class="mb-0 fw-bold text-success">Produk #1</p>
                                        </div>
                                        <div class="row align-items-end">
                                            {{-- Kolom Tipe Gas (Diperlebar) --}}
                                            <div class="col-lg-8 mb-2"> 
                                                <div class="form-group">
                                                    <label class="form-label">Tipe Gas</label>
                                                    <select name="items[0][tipe_gas_id]" class="select2 form-control"
                                                        data-placeholder="Pilih Tipe Gas" required>
                                                        <option></option>
                                                        @foreach ($tipeGas as $tipe)
                                                            <option value="{{ $tipe->id }}">{{ $tipe->nama }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            {{-- Kolom Jumlah (Diperlebar) --}}
                                            <div class="col-lg-4 col-md-8 mb-2">
                                                <div class="form-group"><label class="form-label">Jumlah Total</label><input
                                                        type="number" name="items[0][jumlah]"
                                                        class="form-control jumlah-input" placeholder="0" required
                                                        min="1"></div>
                                            </div>
                                            
                                            {{-- Kolom Kondisi Rusak Dihapus --}}
                                            {{-- Kolom Jumlah Rusak Dihapus --}}
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-center mb-3">
                                    <button type="button" id="add-pengembalian"
                                        class="btn btn-outline-primary shadow-sm"><i class="mdi mdi-plus"></i> Tambah
                                        Produk Lain</button>
                                </div>
                                <button type="submit" class="btn btn-success shadow w-100 py-2"><i
                                        class="mdi mdi-content-save"></i> Simpan Transaksi</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card shadow-lg border-0">
                        <div class="card-header"
                            style="background: linear-gradient(135deg, #6f42c1, #007bff); color: white;">
                            <h5 class="mb-0"><i class="mdi mdi-database me-2"></i>Riwayat Transaksi Pengembalian</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="pengembalianTable" class="table table-bordered table-hover">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Kode</th>
                                            <th>Nama Pembeli</th>
                                            <th>Produk</th>
                                            <th>Jumlah</th>
                                            {{-- Kolom Jml Rusak Dihapus --}}
                                            {{-- Kolom Kondisi Dihapus --}}
                                            <th>Tanggal</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pengembalians as $pengembalian)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $pengembalian->kode }}</td>
                                                <td>{{ $pengembalian->nama_pembeli }}</td>
                                                <td>{{ $pengembalian->stokGas->tipeGas->nama }}</td>
                                                <td>{{ $pengembalian->jumlah }}</td>
                                                {{-- Kolom Jml Rusak Dihapus --}}
                                                {{-- Kolom Kondisi Dihapus --}}
                                                <td>{{ \Carbon\Carbon::parse($pengembalian->tanggal_pengembalian)->format('d M Y') }}
                                                </td>
                                                <td><button type="button" class="btn btn-info btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#detailModal{{ $pengembalian->id }}"><i
                                                            class="mdi mdi-eye"></i></button></td>
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

    @foreach ($pengembalians as $pengembalian)
        <div class="modal fade" id="detailModal{{ $pengembalian->id }}" tabindex="-1"
            aria-labelledby="modalLabel{{ $pengembalian->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content text-dark">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title" id="modalLabel{{ $pengembalian->id }}">Detail Pengembalian:
                            {{ $pengembalian->kode }}</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        {{-- Isi Modal Anda (Pastikan Anda juga menghapus info 'rusak' dari sini jika ada) --}}
                        <p><strong>Kode:</strong> {{ $pengembalian->kode }}</p>
                        <p><strong>Nama Pembeli:</strong> {{ $pengembalian->nama_pembeli }}</p>
                        <p><strong>No. KK:</strong> {{ $pengembalian->no_kk ?? '-' }}</p>
                        <p><strong>No. Telp:</strong> {{ $pengembalian->no_telp ?? '-' }}</p>
                        <p><strong>Produk:</strong> {{ $pengembalian->stokGas->tipeGas->nama }}</p>
                        <p><strong>Jumlah:</strong> {{ $pengembalian->jumlah }}</p>
                        <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($pengembalian->tanggal_pengembalian)->format('d M Y H:i') }}</p>
                        <p><strong>Keterangan:</strong> {{ $pengembalian->keterangan ?? '-' }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

@endsection

@push('scripts')
    {{-- JS untuk Select2 --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> --}}

    <script>
        $(document).ready(function() {
            // Inisialisasi DataTable
            $('#pengembalianTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
                },
                "order": [
                    [5, "desc"] // Mengubah urutan default ke kolom tanggal (indeks ke-5)
                ],
                "columnDefs": [
                    { "orderable": false, "targets": 6 } // Nonaktifkan order di kolom Aksi (indeks ke-6)
                ]
            });

            let index = 1;

            // Fungsi untuk inisialisasi Select2
            function initSelect2(selector) {
                $(selector).select2({
                    placeholder: 'Pilih Tipe Gas',
                    // theme: 'bootstrap-5',
                    width: '100%'
                });
            }
            initSelect2('.select2'); // Inisialisasi untuk yang sudah ada

            // Tambah item baru
            $('#add-pengembalian').click(function() {
                // Template HTML telah diperbarui: kolom rusak dihapus, kolom lain diperlebar
                let newItemHtml = `
                <div class="pengembalian-item border p-3 mb-3 rounded shadow-sm" data-item="${index}" style="background-color: #fcfdff;">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <p class="mb-0 fw-bold text-success">Produk #${index + 1}</p>
                        <button type="button" class="btn btn-danger btn-sm remove-pengembalian"><i class="mdi mdi-delete"></i> Hapus</button>
                    </div>
                    <div class="row align-items-end">
                        <div class="col-lg-8 mb-2">
                            <div class="form-group">
                                <label class="form-label">Tipe Gas</label>
                                <select name="items[${index}][tipe_gas_id]" class="select2 form-control" data-placeholder="Pilih Tipe Gas" required>
                                    <option></option>
                                    @foreach ($tipeGas as $tipe)
                                        <option value="{{ $tipe->id }}">{{ $tipe->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-8 mb-2"><div class="form-group"><label class="form-label">Jml Total</label><input type="number" name="items[${index}][jumlah]" class="form-control jumlah-input" placeholder="0" required min="1"></div></div>
                    </div>
                </div>`;
                $('#pengembalian-container').append(newItemHtml);
                initSelect2(
                `.pengembalian-item[data-item="${index}"] .select2`); // Inisialisasi Select2 untuk elemen baru
                index++;
            });

            // Event handler menggunakan event delegation untuk elemen dinamis
            const container = $('#pengembalian-container');
            container.on('click', '.remove-pengembalian', function() {
                $(this).closest('.pengembalian-item').remove();
            });

            // Event handler untuk 'kondisi-rusak-check' Dihapus
            
            // Event handler untuk validasi 'jumlah-rusak-input' Dihapus

        });
    </script>
@endpush