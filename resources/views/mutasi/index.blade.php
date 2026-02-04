@extends('layouts.master')

@section('title', 'Mutasi Gas')

@section('content')

    <div class="page-wrapper">

        <!-- Breadcrumb -->
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-6 align-self-center">
                    <h4 class="page-title">Laporan Mutasi Gas</h4>
                </div>
            </div>
        </div>

        <div class="container-fluid">

            <!-- Filter Form -->
            <div class="row">
                <div class="col-md-12 mb-4">
                    <div class="card shadow-lg border-0">
                        <div class="card-header" style="background: linear-gradient(135deg, #28a745, #20c997); color: white;">
                            <h5 class="mb-0"><i class="mdi mdi-filter me-2"></i>Filter Data Mutasi</h5>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="{{ route('mutasi.index') }}">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><i class="mdi mdi-calendar-start me-1"></i>Tanggal Mulai</label>
                                            <input type="date" name="tanggal_mulai" class="form-control" value="{{ $tanggalMulai }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><i class="mdi mdi-calendar-end me-1"></i>Tanggal Selesai</label>
                                            <input type="date" name="tanggal_selesai" class="form-control" value="{{ $tanggalSelesai }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><i class="mdi mdi-gas-cylinder me-1"></i>Tipe Gas</label>
                                            <select name="tipe_gas_id" class="form-control">
                                                <option value="">Semua Tipe Gas</option>
                                                @foreach($tipeGas as $tipe)
                                                    <option value="{{ $tipe->id }}" {{ $tipeGasFilter == $tipe->id ? 'selected' : '' }}>
                                                        {{ $tipe->nama }} - {{ $tipe->ukuran }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><i class="mdi mdi-code-tags me-1"></i>Jenis Mutasi</label>
                                            <select name="kode_mutasi" class="form-control">
                                                <option value="">Semua Jenis</option>
                                                @foreach($kodeMutasiOptions as $kode => $nama)
                                                    <option value="{{ $kode }}" {{ $kodeMutasiFilter == $kode ? 'selected' : '' }}>
                                                        {{ $nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="mdi mdi-magnify"></i> Filter Data
                                        </button>
                                        <a href="{{ route('mutasi.index') }}" class="btn btn-secondary">
                                            <i class="mdi mdi-refresh"></i> Reset Filter
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stat Cards -->
            <div class="row">

                {{-- Total Mutasi Hari Ini --}}
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card shadow-lg border-0 h-100">
                        <div class="card-body bg-gradient-warning text-white rounded">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h5>Mutasi Hari Ini</h5>
                                    <h3>{{ $mutasiHariIni }}</h3>
                                    <small>Transaksi</small>
                                </div>
                                <div class="ms-3">
                                    <i class="mdi mdi-swap-horizontal display-4 opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Total Mutasi Bulan Ini --}}
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card shadow-lg border-0 h-100">
                        <div class="card-body bg-gradient-info text-white rounded">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h5>Mutasi Bulan Ini</h5>
                                    <h3>{{ $mutasiBlnIni }}</h3>
                                    <small>Transaksi</small>
                                </div>
                                <div class="ms-3">
                                    <i class="mdi mdi-calendar-month display-4 opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Stok Masuk Hari Ini --}}
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card shadow-lg border-0 h-100">
                        <div class="card-body bg-gradient-success text-white rounded">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h5>Stok Masuk Hari Ini</h5>
                                    <h3>{{ $stokMasukHariIni }}</h3>
                                    <small>Tabung</small>
                                </div>
                                <div class="ms-3">
                                    <i class="mdi mdi-arrow-down-bold display-4 opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Stok Keluar Hari Ini --}}
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card shadow-lg border-0 h-100">
                        <div class="card-body bg-gradient-danger text-white rounded">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h5>Stok Keluar Hari Ini</h5>
                                    <h3>{{ $stokKeluarHariIni }}</h3>
                                    <small>Tabung</small>
                                </div>
                                <div class="ms-3">
                                    <i class="mdi mdi-arrow-up-bold display-4 opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Nilai Mutasi Hari Ini --}}
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card shadow-lg border-0 h-100">
                        <div class="card-body bg-gradient-warning text-white rounded">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h5>Nilai Mutasi Hari Ini</h5>
                                    <h3>Rp {{ number_format($nilaiMutasiHariIni, 0, ',', '.') }}</h3>
                                    <small>Total Nilai</small>
                                </div>
                                <div class="ms-3">
                                    <i class="mdi mdi-currency-usd display-4 opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Nilai Mutasi Bulan Ini --}}
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card shadow-lg border-0 h-100">
                        <div class="card-body bg-gradient-secondary text-white rounded">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h5>Nilai Mutasi Bulan Ini</h5>
                                    <h3>Rp {{ number_format($nilaiMutasiBlnIni, 0, ',', '.') }}</h3>
                                    <small>Total Nilai</small>
                                </div>
                                <div class="ms-3">
                                    <i class="mdi mdi-cash-multiple display-4 opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Mutasi per Tipe Gas --}}
                @foreach ($mutasiPerTipe as $item)
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card shadow-lg border-0 h-100">
                            <div class="card-body bg-gradient-success text-white rounded">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <h5>{{ $item->nama }}</h5>
                                        <h3>{{ $item->total_transaksi }} Transaksi</h3>
                                        <small>Masuk: {{ $item->total_masuk }} | Keluar: {{ $item->total_keluar }}</small>
                                    </div>
                                    <div class="ms-3">
                                        <i class="mdi mdi-gas-cylinder display-4 opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- Mutasi per Kode --}}
                @foreach ($mutasiPerKode as $item)
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card shadow-lg border-0 h-100">
                            <div class="card-body bg-gradient-dark text-white rounded">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <h5>
                                            @switch($item->kode_mutasi)
                                                @case('M') Mutasi Masuk @break
                                                @case('K') Mutasi Keluar @break
                                                @case('P') Pengembalian @break
                                                @case('R') Rusak @break
                                                @case('A') Adjustment @break
                                                @case('H') Edit Harga @break
                                                @default {{ $item->kode_mutasi }}
                                            @endswitch
                                        </h5>
                                        <h3>{{ $item->total_transaksi }} Transaksi</h3>
                                        <small>Nilai: Rp {{ number_format($item->total_nilai, 0, ',', '.') }}</small>
                                    </div>
                                    <div class="ms-3">
                                        @switch($item->kode_mutasi)
                                            @case('M') <i class="mdi mdi-arrow-down-bold display-4 opacity-75"></i> @break
                                            @case('K') <i class="mdi mdi-arrow-up-bold display-4 opacity-75"></i> @break
                                            @case('P') <i class="mdi mdi-backup-restore display-4 opacity-75"></i> @break
                                            @case('R') <i class="mdi mdi-alert-circle display-4 opacity-75"></i> @break
                                            @case('A') <i class="mdi mdi-pencil display-4 opacity-75"></i> @break
                                            @case('H') <i class="mdi mdi-currency-usd display-4 opacity-75"></i> @break
                                            @default <i class="mdi mdi-help-circle display-4 opacity-75"></i>
                                        @endswitch
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>

            <div class="row">
                <div class="col-md-12 mt-4">
                    <div class="card shadow-lg border-0">
                        <div class="card-header" style="background: linear-gradient(135deg, #6f42c1, #007bff); color: white;">
                            <h5 class="mb-0"><i class="mdi mdi-database me-2"></i>Data Mutasi Gas</h5>
                            <small>Periode: {{ date('d M Y', strtotime($tanggalMulai)) }} - {{ date('d M Y', strtotime($tanggalSelesai)) }}</small>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="mutasiTable" class="table table-bordered table-hover">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal</th>
                                            <th>Kode</th>
                                            <th>Tipe Gas</th>
                                            <th>Vendor</th>
                                            <th>Jenis Mutasi</th>
                                            <th>Stok Awal</th>
                                            <th>Stok Masuk</th>
                                            <th>Stok Keluar</th>
                                            <th>Stok Akhir</th>
                                            <th>Total Harga</th>
                                            <th>Keterangan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $no = 1; @endphp
                                        @foreach ($mutasiGas as $mutasi)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>{{ date('d M Y', strtotime($mutasi->tanggal)) }}</td>
                                                <td>{{ $mutasi->kode }}</td>
                                                <td>{{ $mutasi->tipeGas->nama ?? '-' }} - {{ $mutasi->tipeGas->ukuran ?? '-' }}</td>
                                                <td>{{ $mutasi->stokGas->vendor->nama_vendor ?? '-' }}</td>
                                                <td>
                                                    @switch($mutasi->kode_mutasi)
                                                        @case('M')
                                                            <span class="badge bg-success">Masuk</span>
                                                            @break
                                                        @case('K')
                                                            <span class="badge bg-danger">Keluar</span>
                                                            @break
                                                        @case('P')
                                                            <span class="badge bg-info">Pengembalian</span>
                                                            @break
                                                        @case('R')
                                                            <span class="badge bg-warning">Rusak</span>
                                                            @break
                                                        @case('A')
                                                            <span class="badge bg-secondary">Adjustment</span>
                                                            @break
                                                        @case('H')
                                                            <span class="badge bg-primary">Edit Harga</span>
                                                            @break
                                                        @default
                                                            <span class="badge bg-dark">{{ $mutasi->kode_mutasi }}</span>
                                                    @endswitch
                                                </td>
                                                <td>{{ $mutasi->stok_awal }}</td>
                                                <td>
                                                    @if($mutasi->stok_masuk > 0)
                                                        <span class="text-success fw-bold">+{{ $mutasi->stok_masuk }}</span>
                                                    @else
                                                        <span class="text-muted">0</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($mutasi->stok_keluar > 0)
                                                        <span class="text-danger fw-bold">-{{ $mutasi->stok_keluar }}</span>
                                                    @else
                                                        <span class="text-muted">0</span>
                                                    @endif
                                                </td>
                                                <td><strong>{{ $mutasi->stok_akhir }}</strong></td>
                                                <td>
                                                    @if($mutasi->total_harga > 0)
                                                        Rp {{ number_format($mutasi->total_harga, 0, ',', '.') }}
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <small>{{ Str::limit($mutasi->ket_mutasi, 50) }}</small>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-info btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#detailModal{{ $mutasi->id }}">
                                                        <i class="mdi mdi-eye"></i> Detail
                                                    </button>
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

    <!-- Modal Detail untuk setiap mutasi -->
    @foreach ($mutasiGas as $mutasi)
        <div class="modal fade" id="detailModal{{ $mutasi->id }}" tabindex="-1" aria-labelledby="detailModalLabel{{ $mutasi->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content text-dark">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title" id="detailModalLabel{{ $mutasi->id }}">Detail Mutasi: {{ $mutasi->kode }}</h5>
                        <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Kode Mutasi:</strong> {{ $mutasi->kode }}
                            </div>
                            <div class="col-md-6">
                                <strong>Tanggal:</strong> {{ date('d M Y H:i', strtotime($mutasi->tanggal)) }}
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Tipe Gas:</strong> {{ $mutasi->tipeGas->nama ?? '-' }} - {{ $mutasi->tipeGas->ukuran ?? '-' }}
                            </div>
                            <div class="col-md-6">
                                <strong>Vendor:</strong> {{ $mutasi->stokGas->vendor->nama_vendor ?? '-' }}
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <strong>Jenis Mutasi:</strong>
                                @switch($mutasi->kode_mutasi)
                                    @case('M')
                                        <span class="badge bg-success fs-6">Masuk</span>
                                        @break
                                    @case('K')
                                        <span class="badge bg-danger fs-6">Keluar</span>
                                        @break
                                    @case('P')
                                        <span class="badge bg-info fs-6">Pengembalian</span>
                                        @break
                                    @case('R')
                                        <span class="badge bg-warning fs-6">Rusak</span>
                                        @break
                                    @case('A')
                                        <span class="badge bg-secondary fs-6">Adjustment</span>
                                        @break
                                    @case('H')
                                        <span class="badge bg-primary fs-6">Edit Harga</span>
                                        @break
                                    @default
                                        <span class="badge bg-dark fs-6">{{ $mutasi->kode_mutasi }}</span>
                                @endswitch
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <strong>Stok Awal:</strong> 
                                <span class="badge bg-secondary fs-6">{{ $mutasi->stok_awal }}</span>
                            </div>
                            <div class="col-md-3">
                                <strong>Stok Masuk:</strong> 
                                <span class="badge bg-success fs-6">+{{ $mutasi->stok_masuk }}</span>
                            </div>
                            <div class="col-md-3">
                                <strong>Stok Keluar:</strong> 
                                <span class="badge bg-danger fs-6">-{{ $mutasi->stok_keluar }}</span>
                            </div>
                            <div class="col-md-3">
                                <strong>Stok Akhir:</strong> 
                                <span class="badge bg-primary fs-6">{{ $mutasi->stok_akhir }}</span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <strong>Total Harga:</strong> 
                                @if($mutasi->total_harga > 0)
                                    <span class="text-success fw-bold">Rp {{ number_format($mutasi->total_harga, 0, ',', '.') }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <strong>Keterangan:</strong> 
                                <p class="mt-2">{{ $mutasi->ket_mutasi }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#mutasiTable').DataTable({
                "language": {
                    "decimal": "",
                    "emptyTable": "Belum ada data mutasi",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                    "infoFiltered": "(disaring dari total _MAX_ data)",
                    "lengthMenu": "Tampilkan _MENU_ data",
                    "loadingRecords": "Memuat...",
                    "processing": "Memproses...",
                    "search": "Cari:",
                    "zeroRecords": "Data tidak ditemukan",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Next",
                        "previous": "Previous"
                    }
                },
                "order": [[1, "desc"]], // Order by tanggal desc
                "pageLength": 25
            });
        });
    </script>
@endpush
