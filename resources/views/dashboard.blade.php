@extends('layouts.master')
@section('title', 'Dashboard')

@section('content')
<div class="page-wrapper">
    <!-- Breadcrumb -->
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-6 align-self-center">
                <h4 class="page-title">Dashboard</h4>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <!-- Statistics Cards Row 1 -->
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card shadow-lg border-0 h-100">
                    <div class="card-body bg-gradient-success text-white rounded">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h5 class="mb-1">Pendapatan Hari Ini</h5>
                                <h3 class="mb-0">Rp {{ number_format($penjualanHariIni, 0, ',', '.') }}</h3>
                            </div>
                            <div class="ms-3">
                                <i class="mdi mdi-cash-multiple display-4 opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card shadow-lg border-0 h-100">
                    <div class="card-body bg-gradient-info text-white rounded">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h5 class="mb-1">Transaksi Bulan Ini</h5>
                                <h3 class="mb-0">{{ $transaksiBlnIni }}</h3>
                            </div>
                            <div class="ms-3">
                                <i class="mdi mdi-chart-line display-4 opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card shadow-lg border-0 h-100">
                    <div class="card-body bg-gradient-danger text-white rounded">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h5 class="mb-1">Penjualan Bulan Ini</h5>
                                <h3 class="mb-0">{{ $penjualanBlnIni }} Tabung</h3>
                            </div>
                            <div class="ms-3">
                                <i class="mdi mdi-trending-up display-4 opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card shadow-lg border-0 h-100">
                    <div class="card-body bg-gradient-dark text-white rounded">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h5 class="mb-1">Pengembalian Bulan Ini</h5>
                                <h3 class="mb-0">{{ $pengembalianBlnIni }} Tabung</h3>
                            </div>
                            <div class="ms-3">
                                <i class="mdi mdi-backup-restore display-4 opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards Row 2 -->
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card shadow-lg border-0 h-100">
                    <div class="card-body bg-gradient-warning text-white rounded">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h5 class="mb-1">Total Stok Gas Penuh</h5>
                                <h3 class="mb-0">{{ $totalStokPenuh }} Tabung</h3>
                            </div>
                            <div class="ms-3">
                                <i class="mdi mdi-gas-cylinder display-4 opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card shadow-lg border-0 h-100">
                    <div class="card-body bg-gradient-secondary text-white rounded">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h5 class="mb-1">Total Stok Gas Kosong</h5>
                                <h3 class="mb-0">{{ $totalStokKosong }} Tabung</h3>
                            </div>
                            <div class="ms-3">
                                <i class="mdi mdi-cylinder display-4 opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- <div class="col-lg-3 col-md-6 mb-4">
                <div class="card shadow-lg border-0 h-100">
                    <div class="card-body" style="background: linear-gradient(135deg, #ff6b6b, #ee5a24); color: white;">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h5 class="mb-1">Total Stok Gas Rusak</h5>
                                <h3 class="mb-0">{{ $totalStokRusak }} Tabung</h3>
                            </div>
                            <div class="ms-3">
                                <i class="mdi mdi-alert-circle display-4 opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}

            @if (in_array(Auth::user()->role, ['super-admin', 'owner']))
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card shadow-lg border-0 h-100">
                        <div class="card-body" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white;">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h5 class="mb-1">Total Vendor</h5>
                                    <h3 class="mb-0">{{ $totalVendor }}</h3>
                                </div>
                                <div class="ms-3">
                                    <i class="mdi mdi-account-group display-4 opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Recent Transactions Table -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="mdi mdi-clock-outline me-2"></i>Transaksi Hari Ini
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($transaksiHariIniList->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th><i class="mdi mdi-barcode me-1"></i>Kode Transaksi</th>
                                            <th><i class="mdi mdi-account me-1"></i>Nama Pembeli</th>
                                            <th><i class="mdi mdi-gas-cylinder me-1"></i>Tipe Gas</th>
                                            <th><i class="mdi mdi-counter me-1"></i>Jumlah</th>
                                            <th><i class="mdi mdi-currency-usd me-1"></i>Total Harga</th>
                                            <th><i class="mdi mdi-clock me-1"></i>Waktu</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($transaksiHariIniList as $transaksi)
                                        <tr>
                                            <td>
                                                <span class="badge bg-primary">{{ $transaksi->kode_transaksi }}</span>
                                            </td>
                                            <td class="fw-bold">{{ $transaksi->nama_pembeli }}</td>
                                            <td>{{ $transaksi->stokGas->tipeGas->nama ?? '-' }}</td>
                                            <td>
                                                <span class="badge bg-success">{{ $transaksi->jumlah }} tabung</span>
                                            </td>
                                            <td class="fw-bold text-success">
                                                Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                                            </td>
                                            <td>{{ $transaksi->created_at->format('H:i') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="mdi mdi-information-outline display-4 text-muted"></i>
                                <p class="text-muted mt-2">Belum ada transaksi hari ini</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">
                            <i class="mdi mdi-lightning-bolt me-2"></i>Aksi Cepat
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-2">
                                <a href="{{ route('transaksi.penjualan.index') }}" class="btn btn-success w-100">
                                    <i class="mdi mdi-cart-plus me-1"></i> Transaksi Penjualan
                                </a>
                            </div>
                            <div class="col-md-3 mb-2">
                                <a href="{{ route('transaksi.pengembalian.index') }}" class="btn btn-warning w-100">
                                    <i class="mdi mdi-backup-restore me-1"></i> Pengembalian Gas
                                </a>
                            </div>
                            <div class="col-md-3 mb-2">
                                <a href="{{ route('transaksi.pembelian.index') }}" class="btn btn-info w-100">
                                    <i class="mdi mdi-shopping me-1"></i> Pembelian Gas
                                </a>
                            </div>
                            <div class="col-md-3 mb-2">
                                <a href="{{ route('stok.index') }}" class="btn btn-primary w-100">
                                    <i class="mdi mdi-package-variant me-1"></i> Kelola Stok
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-gradient-success {
    background: linear-gradient(135deg, #28a745, #20c997) !important;
}
.bg-gradient-info {
    background: linear-gradient(135deg, #17a2b8, #6f42c1) !important;
}
.bg-gradient-danger {
    background: linear-gradient(135deg, #dc3545, #fd7e14) !important;
}
.bg-gradient-dark {
    background: linear-gradient(135deg, #343a40, #6c757d) !important;
}
.bg-gradient-warning {
    background: linear-gradient(135deg, #ffc107, #fd7e14) !important;
}
.bg-gradient-secondary {
    background: linear-gradient(135deg, #6c757d, #adb5bd) !important;
}

.card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

.table-hover tbody tr:hover {
    background-color: rgba(0,123,255,0.1);
}

@media print {
    .btn, .card-header, .page-breadcrumb {
        display: none !important;
    }
    .card {
        border: 1px solid #ddd !important;
        box-shadow: none !important;
    }
}
</style>
@endsection
