<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Vendor;
use App\Models\StokGas;
use App\Models\TipeGas;
use App\Models\MutasiGas;
use Illuminate\Support\Str;
use App\Models\PembelianGas;
use App\Models\PenjualanGas;
use Illuminate\Http\Request;
use App\Models\PengembalianGas;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistics
        $penjualanHariIni = PenjualanGas::whereDate('tanggal_transaksi', now())->sum('total_harga');
        $transaksiBlnIni = PenjualanGas::whereMonth('tanggal_transaksi', now()->month)->count();
        $totalStokPenuh = StokGas::sum('jumlah_penuh');
        $totalStokKosong = StokGas::sum('jumlah_pengembalian');
        // $totalStokRusak = StokGas::sum('jumlah_rusak');
        $totalVendor = Vendor::count();
        
        $penjualanBlnIni = PenjualanGas::whereMonth('tanggal_transaksi', now()->month)->sum('jumlah');
        $pengembalianBlnIni = PengembalianGas::whereMonth('tanggal_pengembalian', now()->month)->sum('jumlah');
        
        // Recent transactions for today
        $transaksiHariIniList = PenjualanGas::with(['stokGas.tipeGas'])
            ->whereDate('tanggal_transaksi', Carbon::today())
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Additional analytics
        $analytics = [
            'total_revenue_month' => PenjualanGas::whereMonth('tanggal_transaksi', now()->month)->sum('total_harga'),
            'total_cost_month' => PembelianGas::whereMonth('tanggal_masuk', now()->month)->sum(DB::raw('jumlah * harga_beli')),
            'profit_margin' => 0,
            'top_selling_gas' => $this->getTopSellingGas(),
            'low_stock_alerts' => $this->getLowStockAlerts(),
        ];

        // Calculate profit margin
        if ($analytics['total_revenue_month'] > 0) {
            $analytics['profit_margin'] = (($analytics['total_revenue_month'] - $analytics['total_cost_month']) / $analytics['total_revenue_month']) * 100;
        }

        return view('dashboard', compact(
            'penjualanHariIni', 
            'transaksiBlnIni', 
            'totalStokPenuh', 
            'totalStokKosong', 
            // 'totalStokRusak', 
            'totalVendor',
            'penjualanBlnIni', 
            'pengembalianBlnIni', 
            'transaksiHariIniList', 
            'analytics'
        ));
    }

    private function getTopSellingGas()
    {
        return PenjualanGas::select('stok_gas.tipe_gas_id', DB::raw('SUM(penjualan_gas.jumlah) as total_sold'))
            ->join('stok_gas', 'penjualan_gas.produk_id', '=', 'stok_gas.id')
            ->join('tipe_gas', 'stok_gas.tipe_gas_id', '=', 'tipe_gas.id')
            ->whereMonth('penjualan_gas.tanggal_transaksi', now()->month)
            ->groupBy('stok_gas.tipe_gas_id')
            ->orderBy('total_sold', 'desc')
            ->with(['stokGas.tipeGas'])
            ->limit(5)
            ->get();
    }

    private function getLowStockAlerts()
    {
        return StokGas::with(['tipeGas'])
            ->where('jumlah_penuh', '<', 10) // Alert when stock is below 10
            ->orderBy('jumlah_penuh', 'asc')
            ->get();
    }

    public function getRealtimeStats()
    {
        // API endpoint for real-time updates
        return response()->json([
            'penjualan_hari_ini' => PenjualanGas::whereDate('tanggal_transaksi', now())->sum('total_harga'),
            'transaksi_hari_ini' => PenjualanGas::whereDate('tanggal_transaksi', now())->count(),
            'stok_penuh' => StokGas::sum('jumlah_penuh'),
            'stok_kosong' => StokGas::sum('jumlah_pengembalian'),
            'last_updated' => now()->format('Y-m-d H:i:s')
        ]);
    }
}
