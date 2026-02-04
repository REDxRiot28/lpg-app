<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Vendor;
use App\Models\StokGas;
use App\Models\TipeGas;
use App\Models\MutasiGas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MutasiGasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Filter tanggal
        $tanggalMulai = $request->get('tanggal_mulai', now()->startOfMonth()->format('Y-m-d'));
        $tanggalSelesai = $request->get('tanggal_selesai', now()->format('Y-m-d'));
        $tipeGasFilter = $request->get('tipe_gas_id');
        $kodeMutasiFilter = $request->get('kode_mutasi');

        // Query dasar
        $query = MutasiGas::with(['tipeGas', 'stokGas.vendor'])
            ->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai]);

        // Apply filters
        if ($tipeGasFilter) {
            $query->where('tipe_id', $tipeGasFilter);
        }

        if ($kodeMutasiFilter) {
            $query->where('kode_mutasi', $kodeMutasiFilter);
        }

        $mutasiGas = $query->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        // Statistik mutasi hari ini
        $mutasiHariIni = MutasiGas::whereDate('tanggal', now())->count();
        
        // Statistik mutasi bulan ini
        $mutasiBlnIni = MutasiGas::whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->count();

        // Total stok masuk hari ini
        $stokMasukHariIni = MutasiGas::whereDate('tanggal', now())
            ->sum('stok_masuk');

        // Total stok keluar hari ini
        $stokKeluarHariIni = MutasiGas::whereDate('tanggal', now())
            ->sum('stok_keluar');

        // Total nilai mutasi hari ini
        $nilaiMutasiHariIni = MutasiGas::whereDate('tanggal', now())
            ->sum('total_harga');

        // Total nilai mutasi bulan ini
        $nilaiMutasiBlnIni = MutasiGas::whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('total_harga');

        // Mutasi per tipe gas (periode filter)
        $mutasiPerTipe = MutasiGas::selectRaw('
                tipe_id, 
                tipe_gas.nama,
                SUM(stok_masuk) as total_masuk,
                SUM(stok_keluar) as total_keluar,
                SUM(total_harga) as total_nilai,
                COUNT(*) as total_transaksi
            ')
            ->join('tipe_gas', 'mutasi_gas.tipe_id', '=', 'tipe_gas.id')
            ->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai])
            ->groupBy('tipe_id', 'tipe_gas.nama')
            ->get();

        // Mutasi per kode mutasi (periode filter)
        $mutasiPerKode = MutasiGas::selectRaw('
                kode_mutasi,
                COUNT(*) as total_transaksi,
                SUM(stok_masuk) as total_masuk,
                SUM(stok_keluar) as total_keluar,
                SUM(total_harga) as total_nilai
            ')
            ->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai])
            ->groupBy('kode_mutasi')
            ->get();

        // Data untuk dropdown filter
        $tipeGas = TipeGas::all();
        $kodeMutasiOptions = [
            'M' => 'Masuk',
            'K' => 'Keluar', 
            'P' => 'Pengembalian',
            'R' => 'Rusak',
            'A' => 'Adjustment',
            'H' => 'Harga'
        ];

        return view('mutasi.index', compact(
            'mutasiGas',
            'mutasiHariIni',
            'mutasiBlnIni',
            'stokMasukHariIni',
            'stokKeluarHariIni',
            'nilaiMutasiHariIni',
            'nilaiMutasiBlnIni',
            'mutasiPerTipe',
            'mutasiPerKode',
            'tipeGas',
            'kodeMutasiOptions',
            'tanggalMulai',
            'tanggalSelesai',
            'tipeGasFilter',
            'kodeMutasiFilter'
        ));
    }

    /**
     * Get kode mutasi description
     */
    private function getKodeMutasiDescription($kode)
    {
        $descriptions = [
            'M' => 'Masuk',
            'K' => 'Keluar',
            'P' => 'Pengembalian',
            'R' => 'Rusak',
            'A' => 'Adjustment',
            'H' => 'Harga'
        ];

        return $descriptions[$kode] ?? $kode;
    }

    /**
     * Get kode mutasi badge class
     */
    private function getKodeMutasiBadgeClass($kode)
    {
        $classes = [
            'M' => 'bg-success',
            'K' => 'bg-danger',
            'P' => 'bg-info',
            'R' => 'bg-warning',
            'A' => 'bg-secondary',
            'H' => 'bg-primary'
        ];

        return $classes[$kode] ?? 'bg-dark';
    }
}
