@extends('layouts.master')

@section('title', 'Transaksi Penjualan & Pengembalian Gas')

@section('content')
    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-6 align-self-center">
                    {{-- Judul Halaman Diperbarui --}}
                    <h4 class="page-title">Transaksi Penjualan & Pengembalian Gas</h4>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            {{-- ==================================================================== --}}
            {{-- BLOK STATISTIK GABUNGAN --}}
            {{-- ==================================================================== --}}
            <div class="row">
                {{-- Penjualan Hari Ini --}}
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card shadow-lg border-0 h-100">
                        <div class="card-body bg-gradient-success text-white rounded">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h5>Pendapatan Hari Ini</h5>
                                    <h3>Rp {{ number_format($totalPendapatanHariIni, 0, ',', '.') }}</h3>
                                </div>
                                <div class="ms-3">
                                    <i class="mdi mdi-cash-multiple display-4 opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Total Transaksi Penjualan Hari Ini --}}
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card shadow-lg border-0 h-100">
                        <div class="card-body bg-gradient-info text-white rounded">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h5>Jml Transaksi Jual Hari Ini</h5>
                                    <h3>{{ $transaksiHariIni }}</h3>
                                </div>
                                <div class="ms-3">
                                    <i class="mdi mdi-chart-line display-4 opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Pengembalian Hari Ini --}}
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card shadow-lg border-0 h-100">
                        <div class="card-body bg-gradient-warning text-white rounded"
                            style="background: linear-gradient(135deg, #17a2b8, #20c997) !important;">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h5>Pengembalian Hari Ini</h5>
                                    <h3>{{ $totalPengembalianHariIni }} Tabung</h3>
                                </div>
                                <div class="ms-3">
                                    <i class="mdi mdi-backup-restore display-4 opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Stok Penuh (Contoh: hanya ambil item pertama) --}}
                @if (isset($stokPenuhPerTipe) && count($stokPenuhPerTipe) > 0)
                    @foreach ($stokPenuhPerTipe as $item)
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="card shadow-lg border-0 h-100">
                                <div class="card-body bg-primary text-white rounded">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <h5>Stok {{ $item->tipeGas->nama }}</h5>
                                            <h3>{{ $item->total_penuh }} Tabung</h3>
                                        </div>
                                        <div class="ms-3">
                                            <i class="mdi mdi-gas-cylinder display-4 opacity-75"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            {{-- ==================================================================== --}}
            {{-- BLOK FORM PENJUALAN & PENGEMBALIAN GABUNGAN --}}
            {{-- ==================================================================== --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow-lg border-0">
                        <div class="card-header"
                            style="background: linear-gradient(135deg, #007bff, #28a745); color: white;">
                            <h5 class="mb-0"><i class="mdi mdi-cart-plus me-2"></i>Form Transaksi Gas (Penjualan &
                                Pengembalian)</h5>
                        </div>
                        <div class="card-body">
                            {{-- Ubah route ke transaksi.store --}}
                            <form action="{{ route('transaksi.penjualan.store') }}" method="POST"
                                id="form-transaksi-gabungan">
                                @csrf

                                {{-- BAGIAN DATA PEMBELI --}}
                                <div class="border p-3 mb-4 rounded shadow-sm" style="background-color: #f8f9fa;">
                                    <h6 class="mb-3 text-primary"><i class="mdi mdi-account-circle me-1"></i>Data Pembeli
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="pilih_pelanggan">Cari/Input Nama Pembeli</label>
                                                <select id="pilih_pelanggan" class="form-control select2-pelanggan"
                                                    required>
                                                    <option value=""></option>
                                                    @foreach ($pelanggans as $p)
                                                        <option value="{{ $p->nama }}" data-kk="{{ $p->no_kk }}"
                                                            data-telp="{{ $p->no_telp }}">
                                                            {{ $p->nama }} ({{ $p->no_kk ?? 'No KK -' }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <input type="hidden" name="nama_pembeli" id="nama_pembeli"
                                                    value="{{ old('nama_pembeli') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="no_kk">No KK (Otomatis/Manual)</label>
                                                <input id="no_kk" type="number" name="no_kk" class="form-control"
                                                    value="{{ old('no_kk') }}" placeholder="Masukkan No KK">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="no_telp">No Telepon (Otomatis/Manual)</label>
                                                <input id="no_telp" type="number" name="no_telp" class="form-control"
                                                    value="{{ old('no_telp') }}" placeholder="Masukkan No Telp">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mt-2">
                                        <label for="keterangan">Keterangan Transaksi (Opsional)</label>
                                        <textarea id="keterangan" name="keterangan" class="form-control" rows="2">{{ old('keterangan') }}</textarea>
                                    </div>
                                </div>

                                <div class="row">
                                    {{-- BAGIAN PENJUALAN --}}
                                    <div class="col-md-6">
                                        <h5 class="mb-3 text-success"><i class="mdi mdi-cart me-1"></i> Penjualan Produk
                                            (Diisi jika ada)</h5>
                                        <div id="penjualan-container">
                                            <div class="penjualan-item border p-3 mb-3 rounded shadow-sm"
                                                style="background-color: #f1f8e9;">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <h6 class="mb-0 text-success"><i
                                                            class="mdi mdi-gas-cylinder me-1"></i>Produk Jual #1</h6>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <div class="form-group">
                                                            <label>Tipe Gas Jual</label>
                                                            {{-- Nama input diubah ke items_penjualan --}}
                                                            <select name="items_penjualan[0][tipe_gas_id]"
                                                                class="form-control select2 tipe-gas-select-jual">
                                                                <option value="" selected disabled>Pilih Tipe Gas...
                                                                </option>
                                                                @foreach ($tipeGasTersedia as $tipe)
                                                                    <option value="{{ $tipe['id'] }}"
                                                                        data-harga-jual="{{ $tipe['harga_jual'] }}"
                                                                        data-stok-total="{{ $tipe['total_stok'] }}">
                                                                        {{ $tipe['nama'] }} (Stok:
                                                                        {{ $tipe['total_stok'] }})
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>Jumlah Jual</label>
                                                            {{-- Nama input diubah ke items_penjualan --}}
                                                            <input type="number" name="items_penjualan[0][jumlah]"
                                                                class="form-control jumlah-input-jual" min="1"
                                                                placeholder="0">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Total Harga</label>
                                                            <input type="text"
                                                                class="form-control total-harga-display-jual" readonly
                                                                placeholder="Rp 0">
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- Harga Satuan tersembunyi untuk ditampilkan di JS --}}
                                                <input type="hidden" class="harga-satuan-jual-hidden" value="0">
                                            </div>
                                        </div>
                                        <button type="button" id="add-penjualan"
                                            class="btn btn-primary btn-sm shadow mb-3">
                                            <i class="mdi mdi-plus"></i> Tambah Produk Jual
                                        </button>
                                    </div>


                                    {{-- BAGIAN PENGEMBALIAN --}}
                                    <div class="col-md-6">
                                        <h5 class="mb-3 text-info"><i class="mdi mdi-backup-restore me-1"></i>
                                            Pengembalian Produk (Opsional)</h5>
                                        <div id="pengembalian-container">
                                            {{-- Item Pertama Pengembalian --}}
                                            <div class="pengembalian-item border p-3 mb-3 rounded shadow-sm"
                                                data-item="0" style="background-color: #e3f2fd;">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <h6 class="mb-0 text-info">Produk Kembali #1</h6>
                                                </div>
                                                <div class="row align-items-end">
                                                    <div class="col-lg-8 mb-2">
                                                        <div class="form-group">
                                                            <label class="form-label">Tipe Gas Kembali</label>
                                                            {{-- Nama input diubah ke items_pengembalian --}}
                                                            <select name="items_pengembalian[0][tipe_gas_id]"
                                                                class="select2 form-control tipe-gas-select-kembali">
                                                                <option value="" selected disabled>Pilih Tipe Gas...
                                                                </option>
                                                                {{-- Menggunakan $tipeGasUntukPengembalian dari Controller yang diperbarui --}}
                                                                @foreach ($tipeGasUntukPengembalian as $tipe)
                                                                    <option value="{{ $tipe['id'] }}">
                                                                        {{ $tipe['nama'] }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-md-8 mb-2">
                                                        <div class="form-group">
                                                            <label class="form-label">Jumlah Kembali</label>
                                                            {{-- Nama input diubah ke items_pengembalian[jumlah_kembali] --}}
                                                            <input type="number"
                                                                name="items_pengembalian[0][jumlah_kembali]"
                                                                class="form-control jumlah-input-kembali" placeholder="0"
                                                                min="1">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <button type="button" id="add-pengembalian"
                                            class="btn btn-outline-info btn-sm shadow mb-3">
                                            <i class="mdi mdi-plus"></i> Tambah Produk Kembali
                                        </button>
                                    </div>
                                </div>


                                {{-- Tombol Submit Gabungan --}}
                                <div class="mt-4 pt-3 border-top">
                                    <h3 class="float-end">TOTAL: <span id="grand-total-display">Rp 0</span></h3>
                                    <button type="submit" class="btn btn-success shadow px-5 py-2">
                                        <i class="mdi mdi-content-save"></i> SIMPAN TRANSAKSI
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


                {{-- ==================================================================== --}}
                {{-- RIWAYAT PENJUALAN --}}
                {{-- ==================================================================== --}}
                <div class="col-md-12 mt-4">
                    <div class="card shadow-lg border-0">
                        <div class="card-header"
                            style="background: linear-gradient(135deg, #17a2b8, #007bff); color: white;">
                            <h5 class="mb-0"><i class="mdi mdi-history me-2"></i>Riwayat Transaksi Penjualan</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="penjualanTable" class="table table-bordered table-hover">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Kode Transaksi</th>
                                            <th>Nama Pembeli</th>
                                            <th>Total Item</th>
                                            <th>Total Harga</th>
                                            <th>Tanggal</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $no_jual = 1; @endphp
                                        @foreach ($penjualans as $kode => $items)
                                            <tr>
                                                <td>{{ $no_jual++ }}</td>
                                                <td>{{ $kode }}</td>
                                                <td>{{ $items->first()->nama_pembeli }}</td>
                                                <td>{{ $items->sum('jumlah') }} Tabung</td>
                                                <td>Rp {{ number_format($items->sum('total_harga'), 0, ',', '.') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($items->first()->tanggal_transaksi)->format('d M Y, H:i') }}
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-info btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#detailModal-{{ Str::slug($kode) }}">
                                                        <i class="mdi mdi-eye"></i> Detail
                                                    </button>
                                                    {{-- 🚀 BARU: TOMBOL CETAK STRUK --}}
                                                    <a href="{{ route('transaksi.penjualan.struk', $kode) }}"
                                                        target="_blank" class="btn btn-warning btn-sm">
                                                        <i class="mdi mdi-printer"></i> Cetak
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- RIWAYAT PENGEMBALIAN --}}
                <div class="col-md-12 mt-4">
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
                                            <th>Total Item</th>
                                            <th>Tanggal</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $no_kembali = 1; @endphp
                                        {{-- DIUBAH: Loop menggunakan $pengembaliansGrouped --}}
                                        @foreach ($pengembaliansGrouped as $kode => $items)
                                            <tr>
                                                <td>{{ $no_kembali++ }}</td>
                                                <td>{{ $kode }}</td>
                                                <td>{{ $items->first()->nama_pembeli }}</td>
                                                {{-- Menghitung total jumlah tabung yang dikembalikan dalam satu kode --}}
                                                <td>{{ $items->sum('jumlah') }} Tabung</td>
                                                <td>{{ \Carbon\Carbon::parse($items->first()->tanggal_pengembalian)->format('d M Y, H:i') }}
                                                </td>
                                                <td>
                                                    {{-- Ubah target modal ke slug kode transaksi --}}
                                                    <button type="button" class="btn btn-info btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#detailKembaliModal-{{ Str::slug($kode) }}">
                                                        <i class="mdi mdi-eye"></i> Detail
                                                    </button>
                                                    <a href="{{ route('transaksi.penjualan.struk', $kode) }}"
                                                        target="_blank" class="btn btn-warning btn-sm">
                                                        <i class="mdi mdi-printer"></i> Cetak
                                                    </a>
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

    {{-- Modal Detail Penjualan (Dibiarkan sama) --}}
    @foreach ($penjualans as $kode => $items)
        <div class="modal fade" id="detailModal-{{ Str::slug($kode) }}" tabindex="-1"
            aria-labelledby="modalLabel-{{ Str::slug($kode) }}" aria-hidden="true">
            {{-- Konten modal penjualan --}}
            <div class="modal-dialog modal-lg">
                <div class="modal-content text-dark">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="modalLabel-{{ Str::slug($kode) }}">Detail Transaksi:
                            {{ $kode }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Pembeli:</strong> {{ $items->first()->nama_pembeli }} | <strong>Tanggal:</strong>
                            {{ \Carbon\Carbon::parse($items->first()->tanggal_transaksi)->format('d M Y, H:i') }}</p>
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>No</th>
                                    <th>Produk</th>
                                    <th>Jumlah</th>
                                    <th>Harga Satuan</th>
                                    <th>Total Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $i => $item)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $item->stokGas->tipeGas->nama }}</td>
                                        <td>{{ $item->jumlah }} Tabung</td>
                                        <td>Rp {{ number_format($item->harga_jual_satuan, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="4" class="text-end fw-bold">Grand Total:</td>
                                    <td class="fw-bold">Rp {{ number_format($items->sum('total_harga'), 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    {{-- Modal Detail Pengembalian (Disesuaikan) --}}
    {{-- Modal Detail Pengembalian (Disesuaikan dan digrupkan) --}}
    @foreach ($pengembaliansGrouped as $kode => $items)
        {{-- Item: group of PengembalianGas with the same code --}}
        <div class="modal fade" id="detailKembaliModal-{{ Str::slug($kode) }}" tabindex="-1"
            aria-labelledby="modalLabel-{{ Str::slug($kode) }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content text-dark">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title" id="modalLabel-{{ Str::slug($kode) }}">Detail Pengembalian:
                            {{ $kode }}</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Kode:</strong> {{ $kode }}</p>
                        <p><strong>Nama Pembeli:</strong> {{ $items->first()->nama_pembeli }}</p>
                        <p><strong>Tanggal:</strong>
                            {{ \Carbon\Carbon::parse($items->first()->tanggal_pengembalian)->format('d M Y, H:i') }}</p>
                        <p><strong>Keterangan:</strong> {{ $items->first()->keterangan ?? '-' }}</p>

                        <h6 class="mt-3">Detail Produk:</h6>
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>No</th>
                                    <th>Produk</th>
                                    <th>Jumlah Kembali</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $i => $item)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $item->stokGas->tipeGas->nama }}</td>
                                        <td>{{ $item->jumlah }} Tabung</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="2" class="text-end fw-bold">Total Tabung Kembali:</td>
                                    <td class="fw-bold">{{ $items->sum('jumlah') }} Tabung</td>
                                </tr>
                            </tbody>
                        </table>
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
    <script>
        $(document).ready(function() {
            // =========================================================================
            // INISIALISASI
            // =========================================================================
            $('#penjualanTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json"
                },
            });

            $('#pengembalianTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json"
                },
            });

            let indexPenjualan = 0; // Mulai dari 0 untuk item yang sudah ada
            let indexPengembalian = 0; // Mulai dari 0 untuk item yang sudah ada

            // Fungsi untuk inisialisasi Select2
            function initSelect2(selector) {
                $(selector).select2({
                    placeholder: 'Pilih Tipe Gas',
                    allowClear: true,
                    width: '100%'
                });
            }

            // Inisialisasi awal
            initSelect2('.select2');

            $('.select2-pelanggan').select2({
                placeholder: 'Ketik Nama Baru atau Pilih Pelanggan...',
                tags: true, // Ini yang memungkinkan input teks baru
                allowClear: true,
                width: '100%'
            });

            // 2. Logika saat Pelanggan Dipilih atau Diketik
            $('#pilih_pelanggan').on('select2:select', function(e) {
                var data = e.params.data;
                var element = $(data.element); // Mengambil element option jika ada

                if (element.length > 0) {
                    // KASUS A: Memilih dari database (Pelanggan Lama)
                    var nama = data.value; // Nilai asli nama
                    var kk = element.data('kk');
                    var telp = element.data('telp');

                    $('#nama_pembeli').val(nama);
                    $('#no_kk').val(kk);
                    $('#no_telp').val(telp);
                } else {
                    // KASUS B: Mengetik nama baru (Pelanggan Baru)
                    $('#nama_pembeli').val(data.text);
                    $('#no_kk').val(''); // Kosongkan agar diisi manual
                    $('#no_telp').val('');
                }
            });

            // 3. Logika jika pilihan di-clear
            $('#pilih_pelanggan').on('select2:unselect', function(e) {
                $('#nama_pembeli').val('');
                $('#no_kk').val('');
                $('#no_telp').val('');
            });

            // Fungsi untuk format Rupiah
            function formatRupiah(angka) {
                if (isNaN(angka) || angka === null || angka === '') return 'Rp 0';
                let number_string = parseFloat(angka).toFixed(0).toString().replace(/\D/g, '');
                let split = number_string.length % 3;
                let rupiah = number_string.substr(0, split);
                let ribuan = number_string.substr(split).match(/\d{3}/gi);

                if (ribuan) {
                    let separator = split ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }
                return 'Rp ' + rupiah;
            }

            // =========================================================================
            // LOGIKA PENJUALAN (items_penjualan)
            // =========================================================================
            function calculateTotalPenjualan(container) {
                const selectedOption = container.find('.tipe-gas-select-jual :selected');
                const harga = parseFloat(selectedOption.data('harga-jual')) || 0;
                let jumlah = parseInt(container.find('.jumlah-input-jual').val()) || 0;
                const maxStok = parseInt(selectedOption.data('stok-total')) || 0;

                // Validasi stok
                if (jumlah > maxStok) {
                    alert('Jumlah Penjualan melebihi total stok yang tersedia (' + maxStok +
                        '). Dibatasi menjadi ' + maxStok);
                    jumlah = maxStok;
                    container.find('.jumlah-input-jual').val(maxStok);
                }

                const total = harga * jumlah;
                container.find('.harga-satuan-jual-hidden').val(harga);
                container.find('.total-harga-display-jual').val(formatRupiah(total));
                updateGrandTotal();
            }

            // Event listener Penjualan
            $(document).on('change', '.tipe-gas-select-jual', function() {
                const container = $(this).closest('.penjualan-item');
                container.find('.jumlah-input-jual').val('1'); // Set default jumlah ke 1
                calculateTotalPenjualan(container);
            });

            $(document).on('input', '.jumlah-input-jual', function() {
                const container = $(this).closest('.penjualan-item');
                calculateTotalPenjualan(container);
            });

            // Tambah Item Penjualan
            $('#add-penjualan').click(function() {
                indexPenjualan++;
                const newItemHtml = `
            <div class="penjualan-item border p-3 mb-3 rounded shadow-sm" style="background-color: #f1f8e9;">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0 text-success"><i class="mdi mdi-gas-cylinder me-1"></i>Produk Jual #${indexPenjualan + 1}</h6>
                    <button type="button" class="btn btn-danger btn-sm remove-penjualan">
                        <i class="mdi mdi-delete"></i> Hapus
                    </button>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label>Tipe Gas Jual</label>
                            <select name="items_penjualan[${indexPenjualan}][tipe_gas_id]" class="form-control select2 tipe-gas-select-jual" required>
                                <option value="" selected disabled>Pilih Tipe Gas...</option>
                                @foreach ($tipeGasTersedia as $tipe)
                                    <option value="{{ $tipe['id'] }}"
                                            data-harga-jual="{{ $tipe['harga_jual'] }}"
                                            data-stok-total="{{ $tipe['total_stok'] }}">
                                        {{ $tipe['nama'] }} (Stok: {{ $tipe['total_stok'] }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Jumlah Jual</label>
                            <input type="number" name="items_penjualan[${indexPenjualan}][jumlah]" class="form-control jumlah-input-jual" required min="1" placeholder="0">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Total Harga</label>
                            <input type="text" class="form-control total-harga-display-jual" readonly placeholder="Rp 0">
                        </div>
                    </div>
                </div>
                <input type="hidden" class="harga-satuan-jual-hidden" value="0">
            </div>`;
                $('#penjualan-container').append(newItemHtml);
                initSelect2(`#penjualan-container .penjualan-item:last .select2`);
            });

            // Hapus Item Penjualan
            $(document).on('click', '.remove-penjualan', function() {
                if ($('.penjualan-item').length > 1) {
                    $(this).closest('.penjualan-item').remove();
                    updateGrandTotal(); // Perbarui total setelah dihapus
                } else {
                    alert(
                        'Minimal harus ada satu produk Penjualan, atau hapus semua input Pengembalian jika hanya ingin melakukan pengembalian.'
                    );
                }
            });

            // =========================================================================
            // LOGIKA PENGEMBALIAN (items_pengembalian)
            // =========================================================================

            // Tambah Item Pengembalian
            $('#add-pengembalian').click(function() {
                indexPengembalian++;
                let newItemHtml = `
            <div class="pengembalian-item border p-3 mb-3 rounded shadow-sm" data-item="${indexPengembalian}" style="background-color: #e3f2fd;">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0 text-info">Produk Kembali #${indexPengembalian + 1}</h6>
                    <button type="button" class="btn btn-danger btn-sm remove-pengembalian"><i class="mdi mdi-delete"></i> Hapus</button>
                </div>
                <div class="row align-items-end">
                    <div class="col-lg-8 mb-2">
                        <div class="form-group">
                            <label class="form-label">Tipe Gas Kembali</label>
                            <select name="items_pengembalian[${indexPengembalian}][tipe_gas_id]" class="select2 form-control tipe-gas-select-kembali" required>
                                <option value="" selected disabled>Pilih Tipe Gas...</option>
                                @foreach ($tipeGasUntukPengembalian as $tipe)
                                    <option value="{{ $tipe['id'] }}">{{ $tipe['nama'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-8 mb-2">
                        <div class="form-group">
                            <label class="form-label">Jumlah Kembali</label>
                            <input type="number" name="items_pengembalian[${indexPengembalian}][jumlah_kembali]" class="form-control jumlah-input-kembali" placeholder="0" required min="1">
                        </div>
                    </div>
                </div>
            </div>`;
                $('#pengembalian-container').append(newItemHtml);
                initSelect2(
                    `.pengembalian-item[data-item="${indexPengembalian}"] .select2`);
            });

            // Hapus Item Pengembalian
            $(document).on('click', '.remove-pengembalian', function() {
                $(this).closest('.pengembalian-item').remove();
            });


            // =========================================================================
            // GRAND TOTAL
            // =========================================================================
            function updateGrandTotal() {
                let grandTotal = 0;
                $('.penjualan-item').each(function() {
                    const totalItem = parseFloat($(this).find('.total-harga-display-jual').val().replace(
                        /[^0-9]/g, '')) || 0;
                    grandTotal += totalItem;
                });

                $('#grand-total-display').text(formatRupiah(grandTotal));
            }

            // Inisialisasi total saat halaman dimuat
            calculateTotalPenjualan($('.penjualan-item').first());
            updateGrandTotal();
        });
    </script>
@endpush
