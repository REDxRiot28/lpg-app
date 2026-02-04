{{-- File: resources/views/transaksi/pembelian/struk.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembelian - {{ $kode_pembelian }}</title>
    <style>
        /* CSS KHUSUS UNTUK PRINTER THERMAL */
        body {
            font-family: 'Consolas', 'Courier New', monospace;
            font-size: 10px;
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

        .total-row td {
            border-top: 1px solid black;
            font-weight: bold;
        }

        @media print {
            body {
                width: 78mm;
                margin: 0;
                padding: 0;
                font-size: 9pt;
            }

            @page {
                size: auto;
                margin: 0;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="header">
            <h3 style="margin: 5px 0; font-size: 12px;">{{ config('app.name', 'GAS APP') }}</h3>
            <p style="margin: 0;">BUKTI PENERIMAAN BARANG</p>
        </div>

        <div class="separator"></div>

        <div class="left" style="font-size: 10px;">
            <p style="margin: 2px 0;">Kode Pembelian: <strong>{{ $kode_pembelian }}</strong></p>
            <p style="margin: 2px 0;">Tanggal Terima: {{ \Carbon\Carbon::parse($tanggal_masuk)->format('d/m/Y') }}
            </p>
        </div>

        <div class="separator"></div>

        <div class="left" style="font-size: 10px;">
            <p style="margin: 2px 0;">Vendor: <strong>{{ $vendor->nama_vendor }}</strong></p>
            <p style="margin: 2px 0;">Alamat: {{ $vendor->alamat }}</p>
        </div>

        <div class="separator"></div>

        <table>
            <thead>
                <tr>
                    <th class="left" style="width: 50%;">ITEM</th>
                    <th class="right" style="width: 15%;">QTY</th>
                    <th class="right" style="width: 35%;">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td class="left" colspan="3" style="font-weight: bold;">{{ $item->tipeGas->nama }}</td>
                    </tr>
                    <tr>
                        <td class="left">Rp. {{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                        <td class="right">{{ $item->jumlah }}</td>
                        <td class="right">Rp. {{ number_format($item->jumlah * $item->harga_beli, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="2" class="left">GRAND TOTAL</td>
                    <td class="right">Rp. {{ number_format($total_harga, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

        @if ($keterangan)
            <div class="separator"></div>
            <p style="margin: 5px 0;">Ket: {{ $keterangan }}</p>
        @endif

        <div class="separator"></div>

        <div class="footer" style="padding-top: 10px;">
            <p style="margin: 5px 0;"> BARANG DITERIMA </p>
            <p style="margin: 0; font-size: 9px;">Dicatat oleh {{ Auth::user()->name ?? 'Admin' }}</p>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
            // window.close(); // Opsional
        };
    </script>

</body>

</html>
