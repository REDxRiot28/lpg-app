@extends('layouts.master')

@section('title', 'Pembelian Gas')

@section('content')

    <div class="page-wrapper">

        <!-- Breadcrumb -->
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-6 align-self-center">
                    <h4 class="page-title">Transaksi Pembelian Gas</h4>
                </div>
            </div>
        </div>

        <div class="container-fluid">

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

            <!-- Stat Cards -->
            <div class="row">

                {{-- Total Pengeluaran Hari Ini --}}
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card shadow-lg border-0 h-100">
                        <div class="card-body bg-gradient-danger text-white rounded">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h5>Pengeluaran Hari Ini</h5>
                                    <h3>Rp {{ number_format($totalPengeluaranHariIni, 0, ',', '.') }}</h3>
                                </div>
                                <div class="ms-3">
                                    <i class="mdi mdi-cash-minus display-4 opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Total Pembelian Hari Ini --}}
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card shadow-lg border-0 h-100">
                        <div class="card-body bg-gradient-success text-white rounded">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h5>Pembelian Hari Ini</h5>
                                    <h3>{{ $totalPembelianHariIni }}</h3>
                                </div>
                                <div class="ms-3">
                                    <i class="mdi mdi-cart-plus display-4 opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Total Tabung Hari Ini --}}
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card shadow-lg border-0 h-100">
                        <div class="card-body bg-gradient-info text-white rounded">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h5>Tabung Hari Ini</h5>
                                    <h3>{{ $totalTabungHariIni }}</h3>
                                </div>
                                <div class="ms-3">
                                    <i class="mdi mdi-gas-cylinder display-4 opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Total Pengeluaran Bulan Ini --}}
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card shadow-lg border-0 h-100">
                        <div class="card-body bg-gradient-warning text-white rounded">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h5>Pengeluaran Bulan Ini</h5>
                                    <h3>Rp {{ number_format($totalPengeluaranBlnIni, 0, ',', '.') }}</h3>
                                </div>
                                <div class="ms-3">
                                    <i class="mdi mdi-calendar-month display-4 opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Stok Penuh per Tipe --}}
                @foreach ($stokPenuhPerTipe as $item)
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card shadow-lg border-0 h-100">
                            <div class="card-body bg-gradient-success text-white rounded">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <h5>Stok: {{ $item->tipeGas->nama }}</h5>
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

                {{-- Pembelian Bulan Ini per Tipe --}}
                @foreach ($pembelianBlnIniPerTipe as $item)
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card shadow-lg border-0 h-100">
                            <div class="card-body bg-gradient-secondary text-white rounded">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <h5>Beli Bulan Ini: {{ $item->nama }}</h5>
                                        <h3>{{ $item->total_beli }} Tabung</h3>
                                    </div>
                                    <div class="ms-3">
                                        <i class="mdi mdi-trending-up display-4 opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- Pembelian per Vendor --}}
                @foreach ($pembelianPerVendor as $item)
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card shadow-lg border-0 h-100">
                            <div class="card-body bg-gradient-dark text-white rounded">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <h5>{{ $item->nama_vendor }}</h5>
                                        <h3>{{ $item->total_beli }} Tabung</h3>
                                        <small>Rp {{ number_format($item->total_harga, 0, ',', '.') }}</small>
                                    </div>
                                    <div class="ms-3">
                                        <i class="mdi mdi-store display-4 opacity-75"></i>
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
                        <div class="card-header"
                            style="background: linear-gradient(135deg, #1ddb7fff, #dafd14ff); color: white;">
                            <h5 class="mb-0"><i class="mdi mdi-database me-2"></i>Data Transaksi Pembelian</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="pembelianTable" class="table table-bordered table-hover">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Kode Pembelian</th>
                                            <th>Vendor</th>
                                            <th>Total Item</th>
                                            <th>Total Harga</th>
                                            <th>Tanggal</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $no = 1; @endphp
                                        @foreach ($pembelians as $kode => $items)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>{{ $kode }}</td>
                                                <td>{{ $items->first()->vendor->nama_vendor }}</td>
                                                <td>{{ $items->sum('jumlah') }} Tabung</td>
                                                <td>Rp
                                                    {{ number_format($items->sum(function ($item) {return $item->jumlah * $item->harga_beli;}),0,',','.') }}
                                                </td>
                                                <td>{{ date('d M Y', strtotime($items->first()->tanggal_masuk)) }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-info btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#detailModal{{ $kode }}">
                                                        <i class="mdi mdi-eye"></i> Detail
                                                    </button>
                                                    {{-- 🚀 BARU: TOMBOL CETAK STRUK --}}
                                                    <a href="{{ route('transaksi.pembelian.struk', $kode) }}"
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

            <div class="col-md-12">
                <div class="card shadow-lg border-0">
                    <div class="card-header" style="background: linear-gradient(135deg, #28a745, #20c997); color: white;">
                        <h5 class="mb-0"><i class="mdi mdi-cart-plus me-2"></i>Form Pembelian Gas dari Vendor</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('transaksi.pembelian.store') }}" method="POST" id="form-pembelian">
                            @csrf

                            <!-- Data Vendor (Di luar repeater) -->
                            <div class="border p-3 mb-3 rounded shadow-sm"
                                style="background: linear-gradient(135deg, #e3f2fd, #f3e5f5);">
                                <h6 class="mb-3 text-primary"><i class="mdi mdi-store me-1"></i>Data Vendor</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><i class="mdi mdi-store me-1"></i>Vendor</label>
                                            <select name="vendor_id" class="select2 form-control"
                                                data-placeholder="Pilih Vendor" required>
                                                <option value=""></option>
                                                @foreach ($vendors as $vendor)
                                                    <option value="{{ $vendor->id }}"
                                                        {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>
                                                        {{ $vendor->nama_vendor }} - {{ $vendor->alamat }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><i class="mdi mdi-note-text me-1"></i>Keterangan</label>
                                            <textarea name="keterangan" class="form-control" rows="2">{{ old('keterangan') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Produk Gas (Repeater) -->
                            <div id="pembelian-container">
                                <div class="pembelian-item border p-3 mb-3 rounded shadow-sm" data-item="0"
                                    style="background: linear-gradient(135deg, #ffffff, #fff3cd);">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0 text-warning"><i class="mdi mdi-gas-cylinder me-1"></i>Produk #1
                                        </h6>
                                        <button type="button" class="btn btn-danger btn-sm remove-pembelian"
                                            style="display: none;">
                                            <i class="mdi mdi-delete"></i> Hapus
                                        </button>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label><i class="mdi mdi-gas-cylinder me-1"></i>Tipe Gas</label>
                                                <select name="items[0][tipe_id]" class="select2 form-control"
                                                    data-placeholder="Pilih Tipe Gas" required>
                                                    <option value=""></option>
                                                    @foreach ($tipeGas as $tipe)
                                                        <option value="{{ $tipe->id }}">
                                                            {{ $tipe->nama }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label><i class="mdi mdi-counter me-1"></i>Jumlah</label>
                                                <input type="number" name="items[0][jumlah]"
                                                    class="form-control jumlah-input" required>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label><i class="mdi mdi-currency-usd me-1"></i>Harga Beli</label>
                                                <input type="number" name="items[0][harga_beli]"
                                                    class="form-control harga-beli-input" required>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label><i class="mdi mdi-calendar me-1"></i>Tanggal Masuk</label>
                                                <input type="date" name="items[0][tanggal_masuk]" class="form-control"
                                                    value="{{ date('Y-m-d') }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label><i class="mdi mdi-cash me-1"></i>Total Harga</label>
                                                <input type="text" class="form-control total-harga-display" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center mb-3">
                                <button type="button" id="add-pembelian" class="btn btn-primary btn-sm shadow">
                                    <i class="mdi mdi-plus"></i> Tambah Produk
                                </button>
                            </div>

                            <button type="submit" class="btn btn-success shadow">
                                <i class="mdi mdi-cart-plus"></i> Simpan Pembelian
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>

    </div>

    @foreach ($pembelians as $kode => $items)
        <div class="modal fade" id="detailModal{{ $kode }}" tabindex="-1"
            aria-labelledby="modalLabel{{ $kode }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content text-dark">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="modalLabel{{ $kode }}">Detail Pembelian: {{ $kode }}
                        </h5>
                        <button type="button" class="btn-close text-white" data-bs-dismiss="modal"
                            aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Vendor:</strong> {{ $items->first()->vendor->nama_vendor }}
                            </div>
                            <div class="col-md-6">
                                <strong>Alamat:</strong> {{ $items->first()->vendor->alamat }}
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Telepon:</strong> {{ $items->first()->vendor->telepon ?? '-' }}
                            </div>
                            <div class="col-md-6">
                                <strong>Tanggal:</strong> {{ date('d M Y', strtotime($items->first()->tanggal_masuk)) }}
                            </div>
                        </div>
                        @if ($items->first()->keterangan)
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <strong>Keterangan:</strong> {{ $items->first()->keterangan }}
                                </div>
                            </div>
                        @endif
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>No</th>
                                    <th>Tipe Gas</th>
                                    <th>Jumlah</th>
                                    <th>Harga Beli</th>
                                    <th>Total Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $i => $item)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $item->tipeGas->nama }}</td>
                                        <td>{{ $item->jumlah }} Tabung</td>
                                        <td>Rp {{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($item->jumlah * $item->harga_beli, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="table-info">
                                    <th colspan="4">Total</th>
                                    <th>Rp
                                        {{ number_format($items->sum(function ($item) {return $item->jumlah * $item->harga_beli;}),0,',','.') }}
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
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
            $('#pembelianTable').DataTable({
                "language": {
                    "decimal": "",
                    "emptyTable": "Belum ada data pembelian",
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
                "order": [
                    [0, "asc"]
                ]
            });

            let index = 1;

            // Initialize Select2 for existing elements
            $('.select2').each(function() {
                var placeholder = $(this).data('placeholder');
                $(this).select2({
                    placeholder: placeholder,
                    allowClear: true
                });
            });


            // Add new item
            $('#add-pembelian').click(function() {
                let newItemHtml = `
                <div class="pembelian-item border p-3 mb-3 rounded shadow-sm" data-item="${index}" style="background: linear-gradient(135deg, #ffffff, #fff3cd);">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0 text-warning"><i class="mdi mdi-gas-cylinder me-1"></i>Produk #${index + 1}</h6>
                        <button type="button" class="btn btn-danger btn-sm remove-pembelian">
                            <i class="mdi mdi-delete"></i> Hapus
                        </button>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><i class="mdi mdi-gas-cylinder me-1"></i>Tipe Gas</label>
                                <select name="items[${index}][tipe_id]" class="select2 form-control" data-placeholder="Pilih Tipe Gas" required>
                                    <option value=""></option>
                                    @foreach ($tipeGas as $tipe)
                                        <option value="{{ $tipe->id }}">
                                            {{ $tipe->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label><i class="mdi mdi-counter me-1"></i>Jumlah</label>
                                <input type="number" name="items[${index}][jumlah]" class="form-control jumlah-input" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label><i class="mdi mdi-currency-usd me-1"></i>Harga Beli</label>
                                <input type="number" name="items[${index}][harga_beli]" class="form-control harga-beli-input" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label><i class="mdi mdi-calendar me-1"></i>Tanggal Masuk</label>
                                <input type="date" name="items[${index}][tanggal_masuk]" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><i class="mdi mdi-cash me-1"></i>Total Harga</label>
                                <input type="text" class="form-control total-harga-display" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                `;

                $('#pembelian-container').append(newItemHtml);

                // Initialize Select2 for the new select elements only
                $('#pembelian-container .pembelian-item:last .select2').select2({
                    placeholder: 'Pilih Tipe Gas'
                });

                index++;
            });

            // Remove item
            $(document).on('click', '.remove-pembelian', function() {
                $(this).closest('.pembelian-item').remove();
            });

            // Handle jumlah and harga beli input change
            $(document).on('input', '.jumlah-input, .harga-beli-input', function() {
                let container = $(this).closest('.pembelian-item');
                let jumlah = parseInt(container.find('.jumlah-input').val()) || 0;
                let hargaBeli = parseInt(container.find('.harga-beli-input').val()) || 0;
                let total = jumlah * hargaBeli;

                container.find('.total-harga-display').val(formatRupiah(total));
            });

            // Format currency function
            function formatRupiah(angka) {
                return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }

            const kodePembelianBaru = "{{ session('kode_pembelian_baru') }}";
            if (kodePembelianBaru) {
                // Panggil rute cetak di jendela/tab baru
                window.open("{{ route('transaksi.pembelian.struk', '') }}/" + kodePembelianBaru, '_blank');
            }
        });
    </script>
@endpush
