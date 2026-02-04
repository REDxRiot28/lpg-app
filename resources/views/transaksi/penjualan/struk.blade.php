{{-- File: resources/views/transaksi/struk.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Transaksi - {{ $kode_transaksi }}</title>
    <style>
        /* CSS KHUSUS UNTUK PRINTER THERMAL (80mm atau 58mm) */
        body {
            font-family: 'Consolas', 'Courier New', monospace;
            /* Font monospasi untuk kesan struk */
            font-size: 10px;
            /* Ukuran font kecil */
            width: 300px;
            /* Lebar maksimum kertas thermal 80mm */
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            margin: auto;
            padding: 5px;
        }

        .header,
        .footer,
        .separator {
            text-align: center;
            margin: 5px 0;
        }

        .separator {
            border-bottom: 1px dashed black;
        }

        .left {
            text-align: left;
        }

        .right {
            text-align: right;
        }

        .center {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 5px 0;
        }

        th,
        td {
            padding: 2px 0;
            vertical-align: top;
        }

        .item-name {
            white-space: normal;
            /* Biarkan nama item wrap */
            padding-right: 5px;
        }

        .total-row td {
            border-top: 1px dashed black;
            font-weight: bold;
        }

        /* Media query untuk print */
        @media print {
            body {
                width: 78mm;
                /* Sesuaikan lebar kertas printer Anda */
                margin: 0;
                padding: 0;
                font-size: 9pt;
                /* Ukuran font lebih sesuai untuk cetak */
            }

            /* Memicu dialog cetak secara otomatis */
            @page {
                size: auto;
                /* Agar tidak ada margin default yang besar */
                margin: 0;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="header">
            <h3 style="margin: 5px 0; font-size: 12px;">{{ config('app.name', 'GAS APP') }}</h3>
            <p style="margin: 0;">Jl. Contoh No. 123, Kota Anda</p>
            <p style="margin: 0;">Telp: 0812-3456-7890</p>
        </div>

        <div class="separator"></div>

        <div class="left" style="font-size: 10px;">
            <p style="margin: 2px 0;">Kode Transaksi: <strong>{{ $kode_transaksi }}</strong></p>
            <p style="margin: 2px 0;">Tanggal: {{ \Carbon\Carbon::parse($tanggal_transaksi)->format('d/m/Y H:i') }}</p>
            <p style="margin: 2px 0;">Pelanggan: {{ $nama_pembeli }}</p>
        </div>

        <div class="separator"></div>

        @if ($penjualan_items->isNotEmpty())
            <div class="center" style="margin: 5px 0; font-weight: bold;">PENJUALAN</div>
            <table>
                @php $totalSemuaPenjualan = 0; @endphp
                @foreach ($penjualan_items as $item)
                    @php $totalSemuaPenjualan += $item->total_harga; @endphp
                    <tr>
                        <td colspan="4" class="item-name">{{ $item->stokGas->tipeGas->nama }}</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td class="left">{{ $item->jumlah }} x</td>
                        <td class="right">Rp. {{ number_format($item->harga_jual_satuan, 0, ',', '.') }}</td>
                        <td class="right">Rp. {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="3" class="left">GRAND TOTAL PENJUALAN</td>
                    <td class="right">Rp. {{ number_format($totalSemuaPenjualan, 0, ',', '.') }}</td>
                </tr>
            </table>
        @endif

        @if ($pengembalian_items->isNotEmpty())
            <div class="separator"></div>
            <div class="center" style="margin: 5px 0; font-weight: bold;">PENGEMBALIAN TABUNG KOSONG</div>
            <table>
                @foreach ($pengembalian_items as $item)
                    <tr>
                        <td class="item-name">{{ $item->stokGas->tipeGas->nama }}</td>
                        <td class="right">Jml: {{ $item->jumlah }}</td>
                    </tr>
                @endforeach
            </table>
        @endif

        <div class="separator"></div>

        @if ($penjualan_items->isNotEmpty() && $pengembalian_items->isNotEmpty())
            <p class="center" style="margin: 5px 0;">(Transaksi Gabungan)</p>
        @endif

        <div class="footer">
            <p style="margin: 5px 0;">Terima kasih atas kunjungan Anda.</p>
            <p style="margin: 0; font-size: 9px;">Layanan oleh {{ Auth::user()->name ?? 'Kasir' }}</p>
        </div>
    </div>

    <script>
        // Memicu dialog cetak setelah halaman dimuat
        window.onload = function() {
            window.print();
            // Opsional: Tutup jendela setelah cetak (jika di open di tab baru)
            // window.close(); 
        };
    </script>

</body>

</html>
