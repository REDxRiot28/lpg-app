@extends('layouts.master')
@section('title', 'Stok Gas')
@section('content')

    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-6 align-self-center">
                    <h4 class="page-title">Master Stok Gas</h4>
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
                {{-- Total Stok Penuh --}}
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card shadow-lg border-0 h-100">
                        <div class="card-body bg-gradient-success text-white rounded">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h5>Total Stok Penuh</h5>
                                    <h3>{{ $totalStokPenuh }}</h3>
                                    <small>Tabung Siap Jual</small>
                                </div>
                                <div class="ms-3"><i class="mdi mdi-gas-cylinder display-4 opacity-75"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                 {{-- Total Stok Pengembalian --}}
                 <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card shadow-lg border-0 h-100">
                        <div class="card-body bg-gradient-info text-white rounded">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h5>Stok Pengembalian</h5>
                                    <h3>{{ $totalStokPengembalian }}</h3>
                                    <small>Tabung Kosong</small>
                                </div>
                                <div class="ms-3"><i class="mdi mdi-backup-restore display-4 opacity-75"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                
                 {{-- Total Nilai Stok --}}
                 <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card shadow-lg border-0 h-100">
                        <div class="card-body bg-gradient-warning text-white rounded">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h5>Nilai Stok</h5>
                                    <h3>Rp {{ number_format($totalNilaiStok, 0, ',', '.') }}</h3>
                                    <small>Total Investasi</small>
                                </div>
                                <div class="ms-3"><i class="mdi mdi-currency-usd display-4 opacity-75"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            @if($stokMinimum->count() > 0)
            <div class="alert alert-warning">
                <h5><i class="mdi mdi-alert-triangle me-2"></i>Peringatan Stok Minimum!</h5>
                <ul class="mb-0">
                    @foreach($stokMinimum as $stok)
                        <li><strong>{{ $stok->tipeGas->nama }}</strong> ({{ $stok->vendor->nama_vendor }}) - Sisa stok penuh: {{ $stok->jumlah_penuh }} tabung</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow-lg border-0">
                        <div class="card-header" style="background: linear-gradient(135deg, #6f42c1, #007bff); color: white;">
                            <h5 class="mb-0"><i class="mdi mdi-database me-2"></i>Data Rincian Stok Gas</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="stokTable" class="table table-bordered table-hover">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Tipe Gas</th>
                                            <th>Vendor</th>
                                            <th>Stok Penuh</th>
                                            <th>Stok Kosong</th>
                                            <th>Harga Beli</th>
                                            <th>Harga Jual</th>
                                            <th>Tgl. Masuk</th>
                                            {{-- <th>Aksi</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($stokGas as $stok)
                                            <tr class="{{ $stok->jumlah_penuh < 10 ? 'table-warning' : '' }}">
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $stok->tipeGas->nama ?? '-' }}</td>
                                                <td>{{ $stok->vendor->nama_vendor ?? '-' }}</td>
                                                <td><span class="badge bg-success">{{ $stok->jumlah_penuh ?? 0 }}</span></td>
                                                <td><span class="badge bg-info">{{ $stok->jumlah_pengembalian ?? 0 }}</span></td>
                                                <td>Rp {{ number_format($stok->harga_beli ?? 0, 0, ',', '.') }}</td>
                                                <td>Rp {{ number_format($stok->harga_jual ?? 0, 0, ',', '.') }}</td>
                                                <td>{{ $stok->tanggal_masuk ? \Carbon\Carbon::parse($stok->tanggal_masuk)->format('d M Y') : '-' }}</td>
                                                {{-- <td>
                                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#adjustmentModal{{ $stok->id }}">
                                                        <i class="mdi mdi-pencil"></i> Adjust
                                                    </button>
                                                </td> --}}
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

    {{-- @foreach ($stokGas as $stok)
        <div class="modal fade" id="adjustmentModal{{ $stok->id }}" tabindex="-1" aria-labelledby="adjustmentModalLabel{{ $stok->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content text-dark">
                    <div class="modal-header bg-warning text-white">
                        <h5 class="modal-title" id="adjustmentModalLabel{{ $stok->id }}">Adjustment Stok: {{ $stok->tipeGas->nama }} ({{ $stok->vendor->nama_vendor }})</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <form action="{{ route('stok.adjustment') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" name="stok_id" value="{{ $stok->id }}">
                            
                            <div class="mb-3">
                                <label>Tipe Adjustment</label>
                                <select name="tipe_adjustment" class="form-control adjustment-type" required>
                                    <option value="">Pilih Tipe...</option>
                                    <option value="penuh">Stok Penuh</option>
                                    <option value="pengembalian">Stok Kosong/Pengembalian</option>
                                    <option value="harga_jual">Update Harga Jual Sesuai Master</option>
                                </select>
                            </div>

                            <div class="stok-adjustment-form" style="display: none;">
                                <div class="mb-3">
                                    <label>Jumlah Adjustment</label>
                                    <input type="number" name="jumlah_adjustment" class="form-control" placeholder="Positif utk menambah, negatif utk mengurangi">
                                    <small class="text-muted">Stok saat ini: Penuh ({{$stok->jumlah_penuh}}), Kosong ({{$stok->jumlah_pengembalian}})</small>
                                </div>
                            </div>

                            <div class="harga-adjustment-form" style="display: none;">
                                <div class="alert alert-info">
                                    <p class="mb-1">Tindakan ini akan mengubah harga jual batch stok ini agar sesuai dengan harga master terbaru.</p>
                                    <hr>
                                    <p class="mb-1"><strong>Harga Jual Batch Ini:</strong> Rp {{ number_format($stok->harga_jual, 0, ',', '.') }}</p>
                                    <p class="mb-0"><strong>Harga Master Terbaru:</strong> Rp {{ number_format($stok->tipeGas->harga_jual, 0, ',', '.') }}</p>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label>Keterangan</label>
                                <textarea name="keterangan" class="form-control" rows="2" required placeholder="Tuliskan alasan adjustment..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-warning">Simpan Adjustment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach --}}
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#stokTable').DataTable({
            "language": { "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json" },
            "order": [[0, "asc"]]
        });

        // Menampilkan/menyembunyikan form di modal berdasarkan pilihan
        $('.adjustment-type').on('change', function() {
            let modal = $(this).closest('.modal');
            let stokForm = modal.find('.stok-adjustment-form');
            let hargaForm = modal.find('.harga-adjustment-form');
            let jumlahInput = modal.find('input[name="jumlah_adjustment"]');
            
            stokForm.hide();
            hargaForm.hide();
            jumlahInput.prop('required', false);

            if ($(this).val() === 'harga_jual') {
                hargaForm.show();
            } else if ($(this).val() !== '') {
                stokForm.show();
                jumlahInput.prop('required', true);
            }
        });
    });
</script>
@endpush