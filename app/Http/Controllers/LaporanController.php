<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\StokGas;
use App\Models\MutasiGas;
use App\Models\PembelianGas;
use App\Models\PenjualanGas;
use Illuminate\Http\Request;
use App\Models\PengembalianGas;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    // =========================================================================
    // METHOD TAMPILAN LAPORAN (VIEW)
    // =========================================================================

    public function harian(Request $request)
    {
        $tanggal = $request->input('tanggal', now()->format('Y-m-d'));
        $data = $this->getHarianReportData($tanggal);

        return view('laporan.harian', $data);
    }

    public function mingguan(Request $request)
    {
        $minggu = $request->input('minggu', now()->format('Y-\WW'));
        $data = $this->getMingguanReportData($minggu);

        return view('laporan.mingguan', $data);
    }

    public function bulanan(Request $request)
    {
        $bulan = $request->input('bulan', now()->format('Y-m'));
        $data = $this->getBulananReportData($bulan);

        return view('laporan.bulanan', $data);
    }

    // =========================================================================
    // METHOD EKSPOR PDF
    // =========================================================================

    public function exportHarianPdf(Request $request)
    {
        $tanggal = $request->input('tanggal', now()->format('Y-m-d'));

        // 1. Ambil semua data laporan seperti biasa
        $data = $this->getHarianReportData($tanggal);

        // 2. Ambil data owner
        $owner = User::where('role', 'owner')->first();

        // 3. Masukkan data owner ke dalam array $data
        $data['owner'] = $owner;

        // 4. Kirim satu array $data yang sudah berisi semuanya ke view
        $pdf = PDF::loadView('laporan.pdf.harian', $data);

        return $pdf->download('laporan-harian-' . $tanggal . '.pdf');
    }

    public function exportMingguanPdf(Request $request)
    {
        $minggu = $request->input('minggu', now()->format('Y-\WW'));
        $data = $this->getMingguanReportData($minggu);

        // 2. Ambil data owner
        $owner = User::where('role', 'owner')->first();

        // 3. Masukkan data owner ke dalam array $data
        $data['owner'] = $owner;

        $pdf = PDF::loadView('laporan.pdf.mingguan', $data);
        return $pdf->download('laporan-mingguan-' . $data['minggu'] . '.pdf');
    }

    public function exportBulananPdf(Request $request)
    {
        $bulan = $request->input('bulan', now()->format('Y-m'));
        $data = $this->getBulananReportData($bulan);
        // 2. Ambil data owner
        $owner = User::where('role', 'owner')->first();

        // 3. Masukkan data owner ke dalam array $data
        $data['owner'] = $owner;
        $pdf = PDF::loadView('laporan.pdf.bulanan', $data);
        $pdf->setPaper('A4', 'landscape');
        return $pdf->download('laporan-bulanan-' . $data['bulan'] . '.pdf');
    }

    // =========================================================================
    // METHOD PRIVATE UNTUK MENGAMBIL DATA (MENGHILANGKAN DUPLIKASI)
    // =========================================================================

    private function getHarianReportData(string $tanggal): array
    {
        // 1. Tentukan rentang waktu untuk laporan harian (dari awal hingga akhir hari).
        $startDate = Carbon::parse($tanggal)->startOfDay(); // Contoh: '2025-10-01 00:00:00'
        $endDate = Carbon::parse($tanggal)->endOfDay(); // Contoh: '2025-10-01 23:59:59'

        // 2. Ambil semua data mentah dari database untuk rentang waktu yang ditentukan.
        $baseData = $this->getLaporanData($startDate, $endDate);

        // 3. Ekstrak data dari hasil panggilan tadi ke variabel terpisah agar lebih mudah dibaca.
        $penjualan = $baseData['penjualan'];
        $pengembalian = $baseData['pengembalian'];
        $pembelian = $baseData['pembelian'];

        // 4. Buat array ringkasan (summary) dengan melakukan kalkulasi pada data yang sudah diambil.
        $summary = [
            // Jumlahkan semua nilai dari kolom 'total_harga' di koleksi penjualan. Jika null, hasilnya 0.
            'total_penjualan' => $penjualan->sum('total_harga') ?? 0,
            // Jumlahkan semua nilai dari kolom 'jumlah' (tabung) di koleksi penjualan.
            'total_tabung_terjual' => $penjualan->sum('jumlah') ?? 0,
            // Hitung berapa banyak transaksi pengembalian yang terjadi.
            'total_pengembalian' => $pengembalian->count(),
            // Jumlahkan total tabung yang dikembalikan dari semua transaksi pengembalian.
            'total_tabung_kembali' => $pengembalian->sum('jumlah') ?? 0,
            // Jumlahkan total tabung rusak dari semua transaksi pengembalian.
            'total_tabung_rusak' => $pengembalian->sum('jumlah_rusak') ?? 0,
            // Kalkulasi total pembelian: untuk setiap item, kalikan jumlah dengan harga beli, lalu jumlahkan semuanya.
            'total_pembelian' => $pembelian->sum(fn($item) => ($item->jumlah ?? 0) * ($item->harga_beli ?? 0)),
            // Jumlahkan total tabung yang dibeli.
            'total_tabung_dibeli' => $pembelian->sum('jumlah') ?? 0,
        ];

        // 5. Kelompokkan data penjualan berdasarkan nama tipe gas, lalu hitung total untuk setiap tipe.
        $penjualanPerTipe = $penjualan->groupBy('stokGas.tipeGas.nama')->map(
            fn($items, $tipe) => [
                'tipe' => $tipe,
                'jumlah' => $items->sum('jumlah'),
                'total_harga' => $items->sum('total_harga'),
            ],
        );

        // 6. Gabungkan semua data (data mentah, tanggal, summary, penjualan per tipe) menjadi satu array untuk dikembalikan.
        return array_merge($baseData, compact('tanggal', 'summary', 'penjualanPerTipe'));
    }

    private function getMingguanReportData(string $minggu): array
    {
        $year = substr($minggu, 0, 4);
        $week = substr($minggu, 6);

        $startDate = Carbon::now()->setISODate($year, $week)->startOfWeek();
        $endDate = $startDate->copy()->endOfWeek();

        $baseData = $this->getLaporanData($startDate, $endDate);

        // ======================= PERBAIKAN DI SINI =======================
        // Membuat variabel individual agar bisa diakses langsung di Blade
        $penjualan = $baseData['penjualan'];
        $pengembalian = $baseData['pengembalian'];
        $pembelian = $baseData['pembelian'];

        $summary = [
            'total_penjualan' => $penjualan->sum('total_harga') ?? 0,
            'total_tabung_terjual' => $penjualan->sum('jumlah') ?? 0,
            'total_pengembalian' => $pengembalian->count(),
            'total_tabung_kembali' => $pengembalian->sum('jumlah') ?? 0,
            'total_tabung_rusak' => $pengembalian->sum('jumlah_rusak') ?? 0,
            'total_pembelian' => $pembelian->sum(fn($item) => ($item->jumlah ?? 0) * ($item->harga_beli ?? 0)),
            'total_tabung_dibeli' => $pembelian->sum('jumlah') ?? 0,
        ];

        $dailyData = [];
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dailyPenjualan = $penjualan->where('tanggal_transaksi', '>=', $date->copy()->startOfDay())->where('tanggal_transaksi', '<=', $date->copy()->endOfDay());
            $dailyData[] = [
                'tanggal' => $date->format('Y-m-d'),
                'hari' => $date->isoFormat('dddd'),
                'penjualan' => $dailyPenjualan->sum('total_harga') ?? 0,
                'tabung_terjual' => $dailyPenjualan->sum('jumlah') ?? 0,
            ];
        }

        // Menggabungkan semuanya, sekarang view akan menerima $penjualan, $pengembalian, dll.
        return array_merge($baseData, compact('minggu', 'startDate', 'endDate', 'summary', 'dailyData'));
    }

    private function getBulananReportData(string $bulan): array
    {
        $startDate = Carbon::createFromFormat('Y-m', $bulan)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();
        $baseData = $this->getLaporanData($startDate, $endDate);

        // ======================= PERBAIKAN DI SINI =======================
        // Membuat variabel individual agar bisa diakses langsung di Blade
        $penjualan = $baseData['penjualan'];
        $pengembalian = $baseData['pengembalian'];
        $pembelian = $baseData['pembelian'];

        $totalPenjualan = $penjualan->sum('total_harga') ?? 0;
        $totalPembelian = $pembelian->sum(fn($item) => ($item->jumlah ?? 0) * ($item->harga_beli ?? 0));
        $totalTabungTerjual = $penjualan->sum('jumlah') ?? 0;
        $totalTabungKembali = $pengembalian->sum('jumlah') ?? 0;
        $totalTabungRusak = $pengembalian->sum('jumlah_rusak') ?? 0;

        $summary = [
            'total_penjualan' => $totalPenjualan,
            'total_tabung_terjual' => $totalTabungTerjual,
            'total_pengembalian' => $pengembalian->count(),
            'total_tabung_kembali' => $totalTabungKembali,
            'total_tabung_rusak' => $totalTabungRusak,
            'total_pembelian' => $totalPembelian,
            'total_tabung_dibeli' => $pembelian->sum('jumlah') ?? 0,
            'keuntungan_kotor' => $totalPenjualan - $totalPembelian,
            'margin_keuntungan' => $totalPenjualan > 0 ? (($totalPenjualan - $totalPembelian) / $totalPenjualan) * 100 : 0,
            'rata_rata_harian' => $totalPenjualan > 0 ? $totalPenjualan / $endDate->day : 0,
            'rata_rata_tabung_harian' => $totalTabungTerjual > 0 ? $totalTabungTerjual / $endDate->day : 0,
            'harga_rata_rata_tabung' => $totalTabungTerjual > 0 ? $totalPenjualan / $totalTabungTerjual : 0,
            'tingkat_kerusakan' => $totalTabungKembali > 0 ? ($totalTabungRusak / $totalTabungKembali) * 100 : 0,
        ];

        $weeklyData = [];
        $currentWeekStart = $startDate->copy()->startOfWeek();
        while ($currentWeekStart->lte($endDate)) {
            $weekEnd = $currentWeekStart->copy()->endOfWeek()->endOfDay();
            if ($weekEnd->gt($endDate)) {
                $weekEnd = $endDate->copy();
            }

            $weeklyPenjualan = $penjualan->whereBetween('tanggal_transaksi', [$currentWeekStart, $weekEnd]);

            $weeklyData[] = [
                'minggu' => 'Minggu ' . $currentWeekStart->weekOfMonth,
                'periode' => $currentWeekStart->format('d M') . ' - ' . $weekEnd->format('d M'),
                'penjualan' => $weeklyPenjualan->sum('total_harga') ?? 0,
                'tabung_terjual' => $weeklyPenjualan->sum('jumlah') ?? 0,
            ];
            $currentWeekStart->addWeek()->startOfWeek();
        }

        $topTipeGas = $penjualan->groupBy('stokGas.tipeGas.nama')->map(fn($items, $tipe) => ['tipe' => $tipe, 'jumlah' => $items->sum('jumlah'), 'total_harga' => $items->sum('total_harga')])->sortByDesc('jumlah')->take(5);

        // Menggabungkan semuanya, sekarang view akan menerima $penjualan, $pengembalian, dll.
        return array_merge($baseData, compact('bulan', 'startDate', 'endDate', 'summary', 'weeklyData', 'topTipeGas'));
    }

    private function getLaporanData(Carbon $startDate, Carbon $endDate): array
    {
        return [
            'penjualan' => PenjualanGas::with(['stokGas.tipeGas'])
                ->whereBetween('tanggal_transaksi', [$startDate, $endDate])
                ->get(),
            'pengembalian' => PengembalianGas::with(['stokGas.tipeGas'])
                ->whereBetween('tanggal_pengembalian', [$startDate, $endDate])
                ->get(),
            'pembelian' => PembelianGas::with(['tipeGas', 'vendor'])
                ->whereBetween('tanggal_masuk', [$startDate, $endDate])
                ->get(),
            'mutasi' => MutasiGas::with(['tipeGas', 'stokGas'])
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->get(),
        ];
    }
}
