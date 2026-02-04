<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Vendor;
use App\Models\StokGas;
use App\Models\TipeGas;
use App\Models\MutasiGas;
use Illuminate\Support\Str;
use App\Models\PembelianGas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PembelianGasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Bagian ini tidak memerlukan perubahan, biarkan seperti apa adanya.
        $vendors = Vendor::all();
        $tipeGas = TipeGas::all(); // Ini akan otomatis mengambil data harga_jual yang baru

        // ... (semua query statistik Anda tidak perlu diubah) ...
        $totalPembelianHariIni = PembelianGas::whereDate('tanggal_masuk', now())->count();
        $totalPembelianBlnIni = PembelianGas::whereMonth('tanggal_masuk', now()->month)->count();
        $totalPengeluaranHariIni = PembelianGas::whereDate('tanggal_masuk', now())->selectRaw('SUM(jumlah * harga_beli) as total')->value('total') ?? 0;
        $totalPengeluaranBlnIni = PembelianGas::whereMonth('tanggal_masuk', now()->month)->selectRaw('SUM(jumlah * harga_beli) as total')->value('total') ?? 0;
        $totalTabungHariIni = PembelianGas::whereDate('tanggal_masuk', now())->sum('jumlah');
        $totalTabungBlnIni = PembelianGas::whereMonth('tanggal_masuk', now()->month)->sum('jumlah');
        $pembelianBlnIniPerTipe = PembelianGas::selectRaw('tipe_id, tipe_gas.nama, SUM(pembelian_gas.jumlah) as total_beli')->join('tipe_gas', 'pembelian_gas.tipe_id', '=', 'tipe_gas.id')->whereMonth('pembelian_gas.tanggal_masuk', now()->month)->groupBy('tipe_id', 'tipe_gas.nama')->get();
        $stokPenuhPerTipe = StokGas::selectRaw('tipe_gas_id, SUM(jumlah_penuh) as total_penuh')->groupBy('tipe_gas_id')->with('tipeGas')->get();
        $pembelianPerVendor = PembelianGas::selectRaw('vendor_id, vendor.nama_vendor, SUM(pembelian_gas.jumlah) as total_beli, SUM(pembelian_gas.jumlah * pembelian_gas.harga_beli) as total_harga')->join('vendor', 'pembelian_gas.vendor_id', '=', 'vendor.id')->whereMonth('pembelian_gas.tanggal_masuk', now()->month)->groupBy('vendor_id', 'vendor.nama_vendor')->get();
        $pembelians = PembelianGas::with(['tipeGas', 'vendor'])->orderBy('tanggal_masuk', 'desc')->get()->groupBy('kode_pembelian');

        return view('transaksi.pembelian.index', compact(
            'pembelians',
            'totalPembelianHariIni',
            'totalPembelianBlnIni',
            'totalPengeluaranHariIni',
            'totalPengeluaranBlnIni',
            'totalTabungHariIni',
            'totalTabungBlnIni',
            'pembelianBlnIniPerTipe',
            'stokPenuhPerTipe',
            'pembelianPerVendor',
            'vendors',
            'tipeGas'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi tetap sama
        $request->validate([
            'vendor_id' => 'required|exists:vendor,id',
            'items' => 'required|array|min:1',
            'items.*.tipe_id' => 'required|exists:tipe_gas,id',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.harga_beli' => 'required|numeric|min:0',
            'items.*.tanggal_masuk' => 'required|date',
        ]);

        try {
            DB::beginTransaction();

            $kodePembelian = 'PB-' . strtoupper(Str::random(8));

            foreach ($request->items as $item) {
                // 1. Ambil data master Tipe Gas untuk mendapatkan harga jualnya
                $tipeGas = TipeGas::find($item['tipe_id']);
                if (!$tipeGas) {
                    throw new \Exception('Tipe gas dengan ID ' . $item['tipe_id'] . ' tidak ditemukan.');
                }

                $totalHarga = $item['jumlah'] * $item['harga_beli'];

                // Simpan ke pembelian_gas (tidak ada perubahan)
                PembelianGas::create([
                    'kode_pembelian' => $kodePembelian,
                    'vendor_id' => $request->vendor_id,
                    'tipe_id' => $item['tipe_id'],
                    'jumlah' => $item['jumlah'],
                    'harga_beli' => $item['harga_beli'],
                    'tanggal_masuk' => $item['tanggal_masuk'],
                    'keterangan' => $request->keterangan,
                ]);

                // Update atau buat stok gas
                $stok = StokGas::where('tipe_gas_id', $item['tipe_id'])
                    ->where('vendor_id', $request->vendor_id)
                    ->first();

                    // untuk buat stok baru 
                if (!$stok) {
                    $stok = new StokGas();
                    $stok->kode = 'SG-' . strtoupper(Str::random(6));
                    $stok->tipe_gas_id = $item['tipe_id'];
                    $stok->vendor_id = $request->vendor_id;
                    $stok->jumlah_penuh = 0;
                    $stok->jumlah_pengembalian = 0;
                    $stok->jumlah_rusak = 0;
                }
                
                $stokAwal = $stok->jumlah_penuh ?? 0;
                
                $stok->jumlah_penuh = ($stok->jumlah_penuh ?? 0) + $item['jumlah'];
                $stok->harga_beli = $item['harga_beli'];
                $stok->tanggal_masuk = $item['tanggal_masuk'];

                // =======================================================
                // INI BAGIAN YANG DIPERBAIKI
                // =======================================================
                // HAPUS LOGIKA LAMA:
                // $stok->harga_jual = $item['harga_beli'] + ($item['harga_beli'] * 0.11);
                
                // GUNAKAN LOGIKA BARU:
                // Ambil harga jual dari master data Tipe Gas yang sudah kita query di atas
                $stok->harga_jual = $tipeGas->harga_jual; 
                // =======================================================

                $stok->save();
                
                // Catat mutasi (tidak ada perubahan)
                $this->catatMutasi([
                    'kode' => $kodePembelian,
                    'tipe_id' => $item['tipe_id'],
                    'produk_id' => $stok->id,
                    'stok_awal' => $stokAwal,
                    'stok_masuk' => $item['jumlah'],
                    'stok_keluar' => 0,
                    'stok_akhir' => $stok->jumlah_penuh,
                    'total_harga' => $totalHarga,
                    'kode_mutasi' => 'M',
                    'ket_mutasi' => 'Pembelian - ' . $kodePembelian,
                    'tanggal' => $item['tanggal_masuk'],
                ]);
            }

            DB::commit();

            return redirect()->back()->with('success', 'Pembelian berhasil disimpan!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal menyimpan pembelian: ' . $e->getMessage());
        }
    }

    public function printStruk($kodePembelian)
    {
        $pembelianItems = PembelianGas::with(['vendor', 'tipeGas'])
                            ->where('kode_pembelian', $kodePembelian)
                            ->get();

        if ($pembelianItems->isEmpty()) {
            return redirect()->route('transaksi.pembelian.index')->with('error', 'Transaksi pembelian tidak ditemukan.');
        }

        $firstItem = $pembelianItems->first();
        $totalHargaPembelian = $pembelianItems->sum(function ($item) {
            return $item->jumlah * $item->harga_beli;
        });

        $data = [
            'kode_pembelian' => $kodePembelian,
            'tanggal_masuk' => $firstItem->tanggal_masuk,
            'vendor' => $firstItem->vendor,
            'keterangan' => $firstItem->keterangan,
            'items' => $pembelianItems,
            'total_harga' => $totalHargaPembelian,
        ];

        // Tampilkan view struk pembelian
        return view('transaksi.pembelian.struk', $data);
    }

    /**
     * Method untuk mencatat mutasi stok
     */
    private function catatMutasi($data)
    {
        // Tidak ada perubahan di sini
        try {
            // Kita perlu memastikan 'kode' ada di $fillable model MutasiGas
            MutasiGas::create($data); 
        } catch (\Exception $e) {
            Log::error('Error saat mencatat mutasi: ' . $e->getMessage());
            throw $e;
        }
    }
}