<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Laporan Harian - {{ \Carbon\Carbon::parse($tanggal)->isoFormat('D MMMM YYYY') }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .company-name {
            font-size: 20px;
            font-weight: bold;
            margin: 0;
        }

        .report-title {
            font-size: 16px;
            font-weight: bold;
            color: #333;
            margin: 0;
        }

        .report-date {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin: 25px 0 10px 0;
            padding-bottom: 5px;
            border-bottom: 1px solid #007bff;
            color: #007bff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 7px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .total-row {
            background-color: #e9ecef;
            font-weight: bold;
        }

        .footer {
            margin-top: 40px;
            font-size: 10px;
            color: #666;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
        }

        .signature-section {
            margin-top: 60px;
            width: 100%;
        }

        .signature-box {
            width: 200px;
            /* Lebar area tanda tangan */
            float: right;
            text-align: center;
        }

        .signature-box .signature-name {
            margin-top: 60px;
            /* Jarak untuk tanda tangan */
            font-weight: bold;
            border-top: 1px solid #333;
            padding-top: 5px;
        }

        .no-data {
            text-align: center;
            padding: 20px;
            background-color: #fff3cd;
            border: 1px solid #ffeeba;
            color: #856404;
        }

        /* Menggunakan tabel untuk summary agar lebih solid di PDF */
        .summary-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 5px;
        }

        .summary-table td {
            width: 25%;
            text-align: center;
            border: 1px solid #007bff;
            background-color: #f8f9fa;
        }

        .summary-label {
            font-size: 10px;
            color: #333;
            margin-bottom: 5px;
        }

        .summary-value {
            font-size: 14px;
            font-weight: bold;
            color: #007bff;
        }
    </style>
</head>

<body>
    <div class="header">
        <p class="company-name">PANGKALAN GAS LPG RESMI</p>
        <p class="report-title">LAPORAN TRANSAKSI HARIAN</p>
        <p class="report-date">{{ \Carbon\Carbon::parse($tanggal)->isoFormat('dddd, D MMMM YYYY') }}</p>
    </div>

    <div class="section-title">RINGKASAN HARI INI</div>
    <table class="summary-table">
        <tr>
            <td>
                <div class="summary-label">TOTAL PENJUALAN</div>
                <div class="summary-value">Rp {{ number_format($summary['total_penjualan'], 0, ',', '.') }}</div>
            </td>
            <td>
                <div class="summary-label">TOTAL PEMBELIAN</div>
                <div class="summary-value">Rp {{ number_format($summary['total_pembelian'], 0, ',', '.') }}</div>
            </td>
            <td>
                <div class="summary-label">TABUNG TERJUAL</div>
                <div class="summary-value">{{ $summary['total_tabung_terjual'] }}</div>
            </td>
            <td>
                <div class="summary-label">TABUNG KEMBALI</div>
                <div class="summary-value">{{ $summary['total_tabung_kembali'] }}</div>
            </td>
        </tr>
    </table>

    @if ($penjualan->isEmpty() && $pengembalian->isEmpty() && $pembelian->isEmpty())
        <p class="no-data">Tidak ada transaksi yang tercatat pada tanggal ini.</p>
    @else
        @if ($penjualan->isNotEmpty())
            <div class="section-title">DETAIL PENJUALAN</div>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Pembeli</th>
                        <th>Tipe Gas</th>
                        <th>Jumlah</th>
                        <th>Harga Satuan</th>
                        <th>Total</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($penjualan as $item)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $item->kode_transaksi }}</td>
                            <td>{{ $item->nama_pembeli }}</td>
                            <td>{{ $item->stokGas->tipeGas->nama ?? '-' }}</td>
                            <td class="text-center">{{ $item->jumlah }}</td>
                            <td class="text-right">Rp{{ number_format($item->harga_jual_satuan, 0, ',', '.') }}</td>
                            <td class="text-right">Rp{{ number_format($item->total_harga, 0, ',', '.') }}</td>
                            <td class="text-center">
                                {{ \Carbon\Carbon::parse($item->tanggal_transaksi)->format('H:i') }}</td>
                        </tr>
                    @endforeach
                    <tr class="total-row">
                        <td colspan="6" class="text-center">TOTAL PENJUALAN</td>
                        <td class="text-right">Rp{{ number_format($summary['total_penjualan'], 0, ',', '.') }}</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        @endif

        @if ($pembelian->isNotEmpty())
            <div class="section-title">DETAIL PEMBELIAN</div>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Vendor</th>
                        <th>Tipe Gas</th>
                        <th>Jumlah</th>
                        <th>Harga Beli</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pembelian as $item)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $item->kode_pembelian }}</td>
                            <td>{{ $item->vendor->nama_vendor ?? '-' }}</td>
                            <td>{{ $item->tipeGas->nama ?? '-' }}</td>
                            <td class="text-center">{{ $item->jumlah }}</td>
                            <td class="text-right">Rp{{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                            <td class="text-right">
                                Rp{{ number_format($item->jumlah * $item->harga_beli, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                    <tr class="total-row">
                        <td colspan="6" class="text-center">TOTAL PEMBELIAN</td>
                        <td class="text-right">Rp{{ number_format($summary['total_pembelian'], 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
        @endif

        @if ($pengembalian->isNotEmpty())
            <div class="section-title">DETAIL PENGEMBALIAN</div>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Tipe Gas</th>
                        <th>Jumlah</th>
                        <th>Rusak</th>
                        <th>Kondisi</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pengembalian as $item)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $item->kode }}</td>
                            <td>{{ $item->nama_pembeli }}</td>
                            <td>{{ $item->stokGas->tipeGas->nama ?? '-' }}</td>
                            <td class="text-center">{{ $item->jumlah }}</td>
                            <td class="text-center">{{ $item->jumlah_rusak ?? 0 }}</td>
                            <td class="text-center">
                                @if ($item->kondisi_rusak)
                                    <span style="color: red;">Ada Rusak</span>
                                @else
                                    <span style="color: green;">Baik</span>
                                @endif
                            </td>
                            <td class="text-center">
                                {{ \Carbon\Carbon::parse($item->tanggal_pengembalian)->format('H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

    @endif

    <div class="signature-section">
        <div class="signature-box">
            Pekanbaru, {{ now()->isoFormat('D MMMM YYYY') }}
            <div class="signature-name">{{ $owner->name ?? '(____________________________)' }}</div>
            Pemilik Pangkalan
        </div>
    </div>

    <div class="footer">
        Laporan ini dicetak oleh Sistem Manajemen Gas LPG pada {{ now()->isoFormat('D MMMM YYYY, H:mm:ss') }}
    </div>
</body>

</html>
