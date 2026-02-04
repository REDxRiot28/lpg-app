<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\StokGas;
use App\Models\TipeGas;
use App\Models\MutasiGas;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StokGasController extends Controller
{
    /**
     * Menampilkan halaman daftar stok.
     */
    public function index()
    {
        $vendors = Vendor::all();
        $tipeGas = TipeGas::all();
        // Bagian ini tidak memerlukan perubahan sama sekali.
        // Semua query statistik Anda sudah benar.
        $totalStokPenuh = StokGas::sum('jumlah_penuh');
        $totalStokPengembalian = StokGas::sum('jumlah_pengembalian');
        $totalStokRusak = StokGas::sum('jumlah_rusak');
        $totalNilaiStok = StokGas::selectRaw('SUM((jumlah_penuh + jumlah_pengembalian) * harga_beli) as total')->value('total') ?? 0;

        $stokPerTipe = TipeGas::withSum('stokGas', 'jumlah_penuh')
            ->withSum('stokGas', 'jumlah_pengembalian')
            ->withSum('stokGas', 'jumlah_rusak')
            ->get()
            ->map(function ($tipe) {
                $tipe->total_penuh = $tipe->stok_gas_sum_jumlah_penuh ?? 0;
                $tipe->total_pengembalian = $tipe->stok_gas_sum_jumlah_pengembalian ?? 0;
                return $tipe;
            });

        $stokPerVendor = StokGas::selectRaw('vendor_id, vendor.nama_vendor, SUM(jumlah_penuh) as total_penuh, SUM(jumlah_pengembalian) as total_pengembalian, SUM((jumlah_penuh + jumlah_pengembalian) * harga_beli) as nilai_stok')->join('vendor', 'stok_gas.vendor_id', '=', 'vendor.id')->groupBy('vendor_id', 'vendor.nama_vendor')->get();

        $stokMinimum = StokGas::where('jumlah_penuh', '<', 10)
            ->with(['tipeGas', 'vendor'])
            ->get();

        $stokGas = StokGas::with(['tipeGas', 'vendor'])
            ->orderBy('tipe_gas_id')
            ->orderBy('tanggal_masuk', 'asc')
            ->get();

        return view('stok.index', compact('vendors', 'tipeGas', 'stokGas', 'totalStokPenuh', 'totalStokPengembalian', 'totalStokRusak', 'totalNilaiStok', 'stokPerTipe', 'stokPerVendor', 'stokMinimum'));
    }

    /**
     * Memproses adjustment stok atau harga.
     */
    public function adjustment(Request $request)
    {
        // =======================================================
        // PERBAIKAN LOGIKA VALIDASI DAN PROSES ADJUSTMENT
        // =======================================================
        $request->validate([
            'stok_id' => 'required|exists:stok_gas,id',
            'tipe_adjustment' => 'required|in:penuh,pengembalian,rusak,harga_jual', // Ubah 'harga' menjadi 'harga_jual'
            'jumlah_adjustment' => 'nullable|integer',
            'keterangan' => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $stok = StokGas::findOrFail($request->stok_id);
            $tipeAdjustment = $request->tipe_adjustment;
            $kodeAdjustment = 'ADJ-' . strtoupper(Str::random(8));

            if ($tipeAdjustment === 'harga_jual') {
                // Adjustment Harga Jual (Logika Baru)
                $tipeGasMaster = TipeGas::find($stok->tipe_gas_id);
                if (!$tipeGasMaster) {
                    throw new \Exception('Master data Tipe Gas tidak ditemukan.');
                }

                $hargaJualLama = $stok->harga_jual;
                $hargaJualBaru = $tipeGasMaster->harga_jual; // Ambil dari master data

                if ($hargaJualLama == $hargaJualBaru) {
                    return redirect()->back()->with('error', 'Harga jual sudah sesuai dengan harga master terbaru. Tidak ada perubahan.');
                }

                $stok->harga_jual = $hargaJualBaru;
                $stok->save(); // updated_at akan terupdate otomatis

                $this->catatMutasi([
                    'kode' => $kodeAdjustment,
                    'tipe_id' => $stok->tipe_gas_id,
                    'produk_id' => $stok->id,
                    'stok_awal' => $stok->jumlah_penuh,
                    'stok_masuk' => 0,
                    'stok_keluar' => 0,
                    'stok_akhir' => $stok->jumlah_penuh,
                    'total_harga' => 0,
                    'kode_mutasi' => 'H', // H = Harga
                    'ket_mutasi' => 'Update Harga Jual: ' . number_format($hargaJualLama) . ' → ' . number_format($hargaJualBaru) . '. (' . $request->keterangan . ')',
                    'tanggal' => now(),
                ]);
            } else {
                // Adjustment Stok (Logika lama Anda, sudah benar)
                $jumlahAdjustment = (int) $request->jumlah_adjustment;
                if ($jumlahAdjustment == 0) {
                    return redirect()->back()->with('error', 'Jumlah adjustment tidak boleh nol.');
                }

                $kolomStok = 'jumlah_' . $tipeAdjustment;
                $stokAwal = $stok->$kolomStok;

                $stok->$kolomStok = max(0, $stok->$kolomStok + $jumlahAdjustment);
                $stok->save();

                $stokAkhir = $stok->$kolomStok;

                $this->catatMutasi([
                    'kode' => $kodeAdjustment,
                    'tipe_id' => $stok->tipe_gas_id,
                    'produk_id' => $stok->id,
                    'stok_awal' => $stokAwal,
                    'stok_masuk' => $jumlahAdjustment > 0 ? $jumlahAdjustment : 0,
                    'stok_keluar' => $jumlahAdjustment < 0 ? abs($jumlahAdjustment) : 0,
                    'stok_akhir' => $stokAkhir,
                    'total_harga' => 0,
                    'kode_mutasi' => 'A', // A = Adjustment
                    'ket_mutasi' => 'Adjustment ' . ucfirst($tipeAdjustment) . ' (' . ($jumlahAdjustment > 0 ? '+' : '') . $jumlahAdjustment . '). ' . $request->keterangan,
                    'tanggal' => now(),
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Adjustment berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()
                ->back()
                ->with('error', 'Gagal menyimpan adjustment: ' . $e->getMessage());
        }
    }

    /**
     * Method untuk mencatat mutasi stok
     */
    private function catatMutasi($data)
    {
        try {
            MutasiGas::create($data);
        } catch (\Exception $e) {
            Log::error('Error saat mencatat mutasi: ' . $e->getMessage());
            throw $e;
        }
    }
}
