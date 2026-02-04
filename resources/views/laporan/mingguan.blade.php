@extends('layouts.master')
@section('title', 'Laporan Mingguan')

@section('content')
<div class="page-wrapper">
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h4 class="page-title">Laporan Mingguan</h4>
                
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="{{ route('laporan.mingguan') }}">
                            <div class="row align-items-end">
                                <div class="col-md-4">
                                    <label for="minggu" class="form-label">Pilih Minggu</label>
                                    <input type="week" id="minggu" name="minggu" class="form-control" value="{{ $minggu }}" required>
                                </div>
                                <div class="col-md-8 d-flex justify-content-start justify-content-md-end align-items-center gap-2 mt-3 mt-md-0">
                                    <button type="submit" class="btn btn-primary"><i class="mdi mdi-magnify"></i> Tampilkan</button>
                                    <!-- button pdf -->
                                    <a href="{{ route('laporan.export.mingguan') }}?minggu={{ $minggu }}" class="btn btn-success" target="_blank"><i class="mdi mdi-file-pdf"></i> PDF</a>
                                    
                                    <a href="{{ route('laporan.mingguan') }}" class="btn btn-secondary"><i class="mdi mdi-refresh"></i> Minggu Ini</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="alert alert-info">
            Menampilkan laporan untuk periode: <strong>{{ $startDate->isoFormat('D MMMM Y') }}</strong> sampai <strong>{{ $endDate->isoFormat('D MMMM Y') }}</strong>
        </div>

        <div class="row">
            <div class="col-lg col-md-6"><div class="card bg-success text-white"><div class="card-body"><div class="d-flex align-items-center"><div class="flex-shrink-0"><i class="mdi mdi-cash-multiple display-6"></i></div><div class="flex-grow-1 ms-3 text-end"><h3 class="mb-0">Rp {{ number_format($summary['total_penjualan'], 0, ',', '.') }}</h3><span>Total Penjualan</span></div></div></div></div></div>
            <div class="col-lg col-md-6"><div class="card bg-primary text-white"><div class="card-body"><div class="d-flex align-items-center"><div class="flex-shrink-0"><i class="mdi mdi-shopping display-6"></i></div><div class="flex-grow-1 ms-3 text-end"><h3 class="mb-0">Rp {{ number_format($summary['total_pembelian'], 0, ',', '.') }}</h3><span>Total Pembelian</span></div></div></div></div></div>
            <div class="col-lg col-md-6"><div class="card bg-info text-white"><div class="card-body"><div class="d-flex align-items-center"><div class="flex-shrink-0"><i class="mdi mdi-gas-cylinder display-6"></i></div><div class="flex-grow-1 ms-3 text-end"><h3 class="mb-0">{{ $summary['total_tabung_terjual'] }}</h3><span>Tabung Terjual</span></div></div></div></div></div>
            <div class="col-lg col-md-6"><div class="card bg-warning text-white"><div class="card-body"><div class="d-flex align-items-center"><div class="flex-shrink-0"><i class="mdi mdi-backup-restore display-6"></i></div><div class="flex-grow-1 ms-3 text-end"><h3 class="mb-0">{{ $summary['total_tabung_kembali'] }}</h3><span>Tabung Kembali</span></div></div></div></div></div>
           
        </div>
        
        @if($penjualan->isEmpty() && $pengembalian->isEmpty() && $pembelian->isEmpty())
            <div class="alert alert-warning text-center"><h4 class="alert-heading">Tidak Ada Data</h4><p>Tidak ada transaksi yang tercatat pada minggu yang dipilih.</p></div>
        @else
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-secondary text-white"><h5 class="mb-0"><i class="mdi mdi-chart-bar"></i> Rekap Penjualan per Hari</h5></div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead><tr><th>No</th><th>Hari</th><th>Tanggal</th><th>Total Penjualan</th><th>Tabung Terjual</th><th>Rata-rata/Tabung</th></tr></thead>
                                    <tbody>
                                        @foreach($dailyData as $day)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td><td>{{ $day['hari'] }}</td><td>{{ \Carbon\Carbon::parse($day['tanggal'])->isoFormat('D MMM Y') }}</td><td>Rp {{ number_format($day['penjualan'], 0, ',', '.') }}</td><td>{{ $day['tabung_terjual'] }}</td><td>@if($day['tabung_terjual'] > 0) Rp {{ number_format($day['penjualan'] / $day['tabung_terjual'], 0, ',', '.') }} @else - @endif</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($penjualan->isNotEmpty())
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-success text-white"><h5 class="mb-0"><i class="mdi mdi-cart"></i> Detail Seluruh Penjualan Minggu Ini</h5></div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered data-table">
                                    <thead><tr><th>No</th><th>Kode</th><th>Pembeli</th><th>Tipe Gas</th><th>Jumlah</th><th>Total</th><th>Tanggal</th></tr></thead>
                                    <tbody>
                                        @foreach($penjualan as $item)
                                        <tr><td>{{ $loop->iteration }}</td><td>{{ $item->kode_transaksi }}</td><td>{{ $item->nama_pembeli }}</td><td>{{ $item->stokGas->tipeGas->nama ?? '-' }}</td><td>{{ $item->jumlah }}</td><td>Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td><td>{{ \Carbon\Carbon::parse($item->tanggal_transaksi)->isoFormat('dddd, D/M/Y H:i') }}</td></tr>
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
                        <div class="card-header bg-primary text-white"><h5 class="mb-0"><i class="mdi mdi-shopping"></i> Detail Seluruh Pembelian Minggu Ini</h5></div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered data-table">
                                    <thead><tr><th>No</th><th>Kode</th><th>Vendor</th><th>Tipe Gas</th><th>Jumlah</th><th>Total</th><th>Tanggal</th></tr></thead>
                                    <tbody>
                                        @foreach($pembelian as $item)
                                        <tr><td>{{ $loop->iteration }}</td><td>{{ $item->kode_pembelian }}</td><td>{{ $item->vendor->nama_vendor ?? '-' }}</td><td>{{ $item->tipeGas->nama ?? '-' }}</td><td>{{ $item->jumlah }}</td><td>Rp {{ number_format($item->jumlah * $item->harga_beli, 0, ',', '.') }}</td><td>{{ \Carbon\Carbon::parse($item->tanggal_masuk)->isoFormat('dddd, D/M/Y') }}</td></tr>
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
    .page-breadcrumb, .card:first-of-type, .btn, .breadcrumb, .dataTables_wrapper .row { display: none !important; }
    .card { border: 1px solid #ddd !important; box-shadow: none !important; }
    .table { font-size: 12px; } .table th, .table td { padding: 0.5rem; }
    .alert-info { border: 1px dashed #000; color: #000; background-color: #fff !important; }
}
</style>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.data-table').DataTable({"language": { "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json" },"pageLength": 10,"order": [],"columnDefs": [ { "orderable": false, "targets": 0 } ]});
});
</script>
@endpush