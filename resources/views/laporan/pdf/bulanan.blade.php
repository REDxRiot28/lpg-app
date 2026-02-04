<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Bulanan - {{ $startDate->isoFormat('MMMM YYYY') }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 10px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .company-name { font-size: 18px; font-weight: bold; margin: 0; }
        .report-title { font-size: 15px; font-weight: bold; color: #333; margin: 0; }
        .report-date { font-size: 12px; color: #666; margin-top: 5px; }
        .section-title { font-size: 12px; font-weight: bold; margin: 20px 0 10px 0; padding-bottom: 5px; border-bottom: 1px solid #007bff; color: #007bff; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid #ccc; padding: 5px; text-align: left; vertical-align: top; }
        th { background-color: #f2f2f2; font-weight: bold; text-align: center; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .footer { margin-top: 30px; font-size: 9px; color: #666; position: fixed; bottom: 0; left: 0; right: 0; text-align: center; }
        .signature-section { margin-top: 40px; width: 100%; page-break-inside: avoid; }
        .signature-box { width: 180px; float: right; text-align: center; }
        .signature-box .signature-name { margin-top: 50px; font-weight: bold; border-top: 1px solid #333; padding-top: 5px; }
        .no-data { text-align: center; padding: 20px; background-color: #fff3cd; border: 1px solid #ffeeba; color: #856404; }
        
        /* Layout untuk section summary dan top produk (2 kolom) */
        .two-column-layout { width: 100%; }
        .two-column-layout .left-column { width: 65%; float: left; padding-right: 10px; box-sizing: border-box; }
        .two-column-layout .right-column { width: 35%; float: right; padding-left: 10px; box-sizing: border-box; }
        .clear { clear: both; }

        .summary-table { width: 100%; }
        .summary-table td { font-weight: bold; }
        .summary-table td:last-child { text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <p class="company-name">PANGKALAN GAS LPG RESMI</p>
        <p class="report-title">LAPORAN TRANSAKSI BULANAN</p>
        <p class="report-date">Periode: {{ $startDate->isoFormat('MMMM YYYY') }}</p>
    </div>

    @if($penjualan->isEmpty() && $pengembalian->isEmpty() && $pembelian->isEmpty())
        <p class="no-data">Tidak ada transaksi yang tercatat pada bulan ini.</p>
    @else
        <div class="two-column-layout">
            <div class="left-column">
                <div class="section-title">ANALISIS PERFORMA</div>
                <table class="summary-table">
                    <tr><td>Total Penjualan</td><td>Rp {{ number_format($summary['total_penjualan'], 0, ',', '.') }}</td></tr>
                    <tr><td>Total Pembelian</td><td>Rp {{ number_format($summary['total_pembelian'], 0, ',', '.') }}</td></tr>
                    <tr style="font-size: 12px; background-color: #e9ecef;"><td>Keuntungan Kotor</td><td>Rp {{ number_format($summary['keuntungan_kotor'], 0, ',', '.') }}</td></tr>
                    <tr><td>Margin Keuntungan</td><td>{{ number_format($summary['margin_keuntungan'], 1) }}%</td></tr>
                    <tr><td>Rata-rata Penjualan Harian</td><td>Rp {{ number_format($summary['rata_rata_harian'], 0, ',', '.') }}</td></tr>
                    <tr><td>Tingkat Kerusakan Tabung</td><td>{{ number_format($summary['tingkat_kerusakan'], 1) }}%</td></tr>
                </table>

                <div class="section-title" style="margin-top: 15px;">REKAP PENJUALAN PER MINGGU</div>
                <table>
                    <thead><tr><th>Minggu</th><th>Periode</th><th>Penjualan</th><th>Tabung</th></tr></thead>
                    <tbody>
                        @foreach($weeklyData as $week)
                        <tr><td>{{ $week['minggu'] }}</td><td class="text-center">{{ $week['periode'] }}</td><td class="text-right">Rp {{ number_format($week['penjualan'], 0, ',', '.') }}</td><td class="text-center">{{ $week['tabung_terjual'] }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="right-column">
                <div class="section-title">TOP 5 TIPE GAS TERLARIS</div>
                <table>
                    <thead><tr><th>Rank</th><th>Tipe Gas</th><th>Jumlah</th></tr></thead>
                    <tbody>
                        @forelse($topTipeGas as $item)
                        <tr><td class="text-center">{{ $loop->iteration }}</td><td>{{ $item['tipe'] }}</td><td class="text-center">{{ $item['jumlah'] }}</td></tr>
                        @empty
                        <tr><td colspan="3" class="text-center">Tidak ada penjualan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="clear"></div>
        </div>
        
        @if ($penjualan->isNotEmpty())
        <div class="section-title">DETAIL SELURUH PENJUALAN BULAN INI</div>
        <table>
            <thead><tr><th>No</th><th>Kode</th><th>Pembeli</th><th>Tipe Gas</th><th>Jumlah</th><th>Total</th><th>Tanggal</th></tr></thead>
            <tbody>
                @foreach($penjualan as $item)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td><td>{{ $item->kode_transaksi }}</td><td>{{ $item->nama_pembeli }}</td><td>{{ $item->stokGas->tipeGas->nama ?? '-' }}</td><td class="text-center">{{ $item->jumlah }}</td><td class="text-right">Rp{{ number_format($item->total_harga, 0, ',', '.') }}</td><td class="text-center">{{ \Carbon\Carbon::parse($item->tanggal_transaksi)->isoFormat('D/M/Y H:i') }}</td>
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

    <div class="footer">Laporan ini dicetak oleh Sistem Manajemen Gas LPG pada {{ now()->isoFormat('D MMMM YYYY, H:mm:ss') }}</div>
</body>
</html>