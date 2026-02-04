@extends('layouts.master')
@section('title', 'Laporan Harian')

@section('content')
<div class="page-wrapper">
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h4 class="page-title">Laporan Harian</h4>
                
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="{{ route('laporan.harian') }}">
                            <div class="row align-items-end">
                                <div class="col-md-4">
                                    <label for="tanggal" class="form-label">Pilih Tanggal Laporan</label>
                                    <input type="date" id="tanggal" name="tanggal" class="form-control" value="{{ $tanggal }}" required>
                                </div>
                                <div class="col-md-8 d-flex justify-content-start justify-content-md-end align-items-center gap-2 mt-3 mt-md-0">
                                    <button type="submit" class="btn btn-primary"><i class="mdi mdi-magnify"></i> Tampilkan</button>
                                    <a href="{{ route('laporan.export.harian') }}?tanggal={{ $tanggal }}" class="btn btn-success" target="_blank"><i class="mdi mdi-file-pdf"></i> PDF</a>
                                    <a href="{{ route('laporan.harian') }}" class="btn btn-secondary"><i class="mdi mdi-refresh"></i> Hari Ini</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="alert alert-info">
            Menampilkan laporan untuk tanggal: <strong>{{ \Carbon\Carbon::parse($tanggal)->isoFormat('dddd, D MMMM Y') }}</strong>
        </div>
        
        <div class="row">
            <div class="col-lg col-md-6">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0"><i class="mdi mdi-cash-multiple display-6"></i></div>
                            <div class="flex-grow-1 ms-3 text-end">
                                <h3 class="mb-0">Rp {{ number_format($summary['total_penjualan'], 0, ',', '.') }}</h3>
                                <span>Total Penjualan</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg col-md-6">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0"><i class="mdi mdi-shopping display-6"></i></div>
                            <div class="flex-grow-1 ms-3 text-end">
                                <h3 class="mb-0">Rp {{ number_format($summary['total_pembelian'], 0, ',', '.') }}</h3>
                                <span>Total Pembelian</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg col-md-6">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0"><i class="mdi mdi-gas-cylinder display-6"></i></div>
                            <div class="flex-grow-1 ms-3 text-end">
                                <h3 class="mb-0">{{ $summary['total_tabung_terjual'] }}</h3>
                                <span>Tabung Terjual</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg col-md-6">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0"><i class="mdi mdi-backup-restore display-6"></i></div>
                            <div class="flex-grow-1 ms-3 text-end">
                                <h3 class="mb-0">{{ $summary['total_tabung_kembali'] }}</h3>
                                <span>Tabung Kembali</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
           
        </div>

        @if($penjualan->isEmpty() && $pengembalian->isEmpty() && $pembelian->isEmpty())
            <div class="alert alert-warning text-center">
                <h4 class="alert-heading">Tidak Ada Data</h4>
                <p>Tidak ada transaksi (penjualan, pembelian, atau pengembalian) yang tercatat pada tanggal yang dipilih.</p>
            </div>
        @else
            @if($penjualan->isNotEmpty())
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-success text-white"><h5 class="mb-0"><i class="mdi mdi-cart"></i> Detail Penjualan</h5></div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered data-table">
                                    <thead><tr><th>No</th><th>Kode</th><th>Pembeli</th><th>Tipe Gas</th><th>Jumlah</th><th>Harga Satuan</th><th>Total</th><th>Waktu</th></tr></thead>
                                    <tbody>
                                        @foreach($penjualan as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->kode_transaksi }}</td>
                                            <td>{{ $item->nama_pembeli }}</td>
                                            <td>{{ $item->stokGas->tipeGas->nama ?? '-' }}</td>
                                            <td>{{ $item->jumlah }}</td>
                                            <td>Rp {{ number_format($item->harga_jual_satuan, 0, ',', '.') }}</td>
                                            <td>Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->tanggal_transaksi)->format('H:i') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if($pengembalian->isNotEmpty())
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-warning text-dark"><h5 class="mb-0"><i class="mdi mdi-backup-restore"></i> Detail Pengembalian</h5></div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered data-table">
                                    <thead><tr><th>No</th><th>Kode</th><th>Nama</th><th>Tipe Gas</th><th>Jumlah</th><th>Rusak</th><th>Kondisi</th><th>Waktu</th></tr></thead>
                                    <tbody>
                                        @foreach($pengembalian as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->kode }}</td>
                                            <td>{{ $item->nama_pembeli }}</td>
                                            <td>{{ $item->stokGas->tipeGas->nama ?? '-' }}</td>
                                            <td>{{ $item->jumlah }}</td>
                                            <td>{{ $item->jumlah_rusak ?? 0 }}</td>
                                            <td>@if($item->kondisi_rusak)<span class="badge bg-danger">Ada Rusak</span>@else<span class="badge bg-success">Baik</span>@endif</td>
                                            <td>{{ \Carbon\Carbon::parse($item->tanggal_pengembalian)->format('H:i') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if($pembelian->isNotEmpty())
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-primary text-white"><h5 class="mb-0"><i class="mdi mdi-shopping"></i> Detail Pembelian</h5></div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered data-table">
                                    <thead><tr><th>No</th><th>Kode</th><th>Vendor</th><th>Tipe Gas</th><th>Jumlah</th><th>Harga Beli</th><th>Total</th></tr></thead>
                                    <tbody>
                                        @foreach($pembelian as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->kode_pembelian }}</td>
                                            <td>{{ $item->vendor->nama_vendor ?? '-' }}</td>
                                            <td>{{ $item->tipeGas->nama ?? '-' }}</td>
                                            <td>{{ $item->jumlah }}</td>
                                            <td>Rp {{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                                            <td>Rp {{ number_format($item->jumlah * $item->harga_beli, 0, ',', '.') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if($penjualanPerTipe->isNotEmpty())
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-secondary text-white"><h5 class="mb-0"><i class="mdi mdi-chart-pie"></i> Rekap Penjualan per Tipe Gas</h5></div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered data-table-no-order">
                                    <thead><tr><th>No</th><th>Tipe Gas</th><th>Jumlah Terjual</th><th>Total Penjualan</th><th>Kontribusi</th></tr></thead>
                                    <tbody>
                                        @foreach($penjualanPerTipe as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item['tipe'] }}</td>
                                            <td>{{ $item['jumlah'] }} tabung</td>
                                            <td>Rp {{ number_format($item['total_harga'], 0, ',', '.') }}</td>
                                            <td>
                                                @php
                                                    $persentase = $summary['total_penjualan'] > 0 ? ($item['total_harga'] / $summary['total_penjualan']) * 100 : 0;
                                                @endphp
                                                {{ number_format($persentase, 1) }}%
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
            @endif
        @endif
    </div>
</div>

<style>
@media print {
    body * { visibility: hidden; }
    .page-wrapper, .page-wrapper * { visibility: visible; }
    .page-wrapper { position: absolute; left: 0; top: 0; width: 100%; }
    .page-breadcrumb, .card:first-of-type, .btn, .breadcrumb { display: none !important; }
    .card { border: 1px solid #ddd !important; box-shadow: none !important; }
    .table { font-size: 12px; }
    .table th, .table td { padding: 0.5rem; }
    .alert-info { border: 1px dashed #000; color: #000; background-color: #fff !important; }
}
</style>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Inisialisasi DataTable untuk tabel yang bisa diurutkan
    $('.data-table').DataTable({
        "language": { "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json" },
        "pageLength": 10,
        "order": [], // Biarkan default sorting dari query
        "columnDefs": [ { "orderable": false, "targets": 0 } ]
    });

    // Inisialisasi DataTable untuk tabel yang TIDAK perlu diurutkan
    $('.data-table-no-order').DataTable({
        "language": { "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json" },
        "pageLength": 10,
        "ordering": false, // Mematikan fitur sorting
    });
});
</script>
@endpush