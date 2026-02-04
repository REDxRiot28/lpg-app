<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\StokGas;
use App\Models\TipeGas;
use App\Models\MutasiGas;
use App\Models\Pelanggan;
use Illuminate\Support\Str;
use App\Models\PenjualanGas;
use Illuminate\Http\Request;
use App\Models\PengembalianGas;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransaksiGasController extends Controller
{
    /**
     * Menampilkan halaman utama transaksi (form, daftar penjualan, dan daftar pengembalian).
     */
    public function index()
    {
        // ------------------------------------------------------------------------
        // BAGIAN 1: PERSIAPAN DATA UNTUK FORM (tetap sama)
        // ------------------------------------------------------------------------
        $pelanggans = Pelanggan::orderBy('nama', 'asc')->get();
        $semuaTipeGas = TipeGas::orderBy('nama', 'asc')->get();
        $tipeGasTersedia = [];
        $tipeGasUntukPengembalian = [];

        foreach ($semuaTipeGas as $tipe) {
            $totalStok = StokGas::where('tipe_gas_id', $tipe->id)->sum('jumlah_penuh');
            if ($totalStok > 0) {
                $tipeGasTersedia[] = [
                    'id' => $tipe->id,
                    'nama' => $tipe->nama,
                    'total_stok' => $totalStok,
                    'harga_jual' => $tipe->harga_jual,
                ];
            }
            $tipeGasUntukPengembalian[] = ['id' => $tipe->id, 'nama' => $tipe->nama];
        }

        // ------------------------------------------------------------------------
        // BAGIAN 2: PERSIAPAN DATA UNTUK RIWAYAT DAN STATISTIK
        // ------------------------------------------------------------------------

        // Data Penjualan & Pengurutan Berdasarkan Tanggal Terbaru (DESC)
        $penjualans = PenjualanGas::with(['stokGas.tipeGas', 'stokGas.vendor'])
            ->orderBy('tanggal_transaksi', 'desc')
            ->get()
            ->groupBy('kode_transaksi');

        // **PERBAIKAN UTAMA DI SINI: Data Pengembalian digrupkan berdasarkan kode**
        $pengembaliansGrouped = PengembalianGas::with('stokGas.tipeGas')->orderBy('tanggal_pengembalian', 'desc')->get()->groupBy('kode'); // <-- DIUBAH: Grouping berdasarkan kode transaksi

        // Statistik (tetap sama)
        $totalPendapatanHariIni = PenjualanGas::whereDate('tanggal_transaksi', now())->sum('total_harga');
        $transaksiHariIni = PenjualanGas::whereDate('tanggal_transaksi', now())->distinct('kode_transaksi')->count();
        $totalPengembalianHariIni = PengembalianGas::whereDate('tanggal_pengembalian', now())->count();
        $transaksiBlnIni = PenjualanGas::whereMonth('tanggal_transaksi', now()->month)->distinct('kode_transaksi')->count();

        $stokPenuhPerTipe = StokGas::selectRaw('tipe_gas_id, SUM(jumlah_penuh) as total_penuh')->where('jumlah_penuh', '>', 0)->groupBy('tipe_gas_id')->with('tipeGas')->get();

        $pengembalianBlnIniPerTipe = PengembalianGas::selectRaw('stok_gas.tipe_gas_id, tipe_gas.nama, SUM(pengembalian_gas.jumlah) as total_kembali')->join('stok_gas', 'pengembalian_gas.produk_id', '=', 'stok_gas.id')->join('tipe_gas', 'stok_gas.tipe_gas_id', '=', 'tipe_gas.id')->whereMonth('pengembalian_gas.tanggal_pengembalian', now()->month)->groupBy('stok_gas.tipe_gas_id', 'tipe_gas.nama')->get();

        // Kirim $pengembaliansGrouped ke view dengan nama baru
        return view(
            'transaksi.penjualan.index',
            compact(
                'tipeGasTersedia',
                'tipeGasUntukPengembalian',
                'penjualans',
                'pengembaliansGrouped', // <-- VARIABEL BARU
                'pelanggans', // Kirim data pelanggan ke view
                'totalPendapatanHariIni',
                'transaksiHariIni',
                'transaksiBlnIni',
                'totalPengembalianHariIni',
                'stokPenuhPerTipe',
                'pengembalianBlnIniPerTipe',
            ),
        );
    }

    /**
     * Menyimpan transaksi penjualan dan (opsional) pengembalian baru.
     */
    public function store(Request $request)
    {
        // ------------------------------------------------------------------------
        // 1. VALIDASI
        // ------------------------------------------------------------------------

        $rules = [
            'nama_pembeli' => 'required|string|max:255',
            'no_kk' => 'nullable|string|max:20',
            'no_telp' => 'nullable|string|max:15',
            'keterangan' => 'nullable|string|max:255',
        ];

        $isPenjualan = isset($request->items_penjualan) && is_array($request->items_penjualan) && count($request->items_penjualan) > 0 && array_filter($request->items_penjualan, fn($item) => isset($item['tipe_gas_id']) && isset($item['jumlah']) && $item['jumlah'] > 0);
        $isPengembalian = isset($request->items_pengembalian) && is_array($request->items_pengembalian) && count($request->items_pengembalian) > 0 && array_filter($request->items_pengembalian, fn($item) => isset($item['tipe_gas_id']) && isset($item['jumlah_kembali']) && $item['jumlah_kembali'] > 0);

        if ($isPenjualan) {
            $rules['items_penjualan'] = 'required|array';
            $rules['items_penjualan.*.tipe_gas_id'] = 'required|exists:tipe_gas,id';
            $rules['items_penjualan.*.jumlah'] = 'required|integer|min:1';
        }

        if ($isPengembalian) {
            $rules['items_pengembalian'] = 'required|array';
            $rules['items_pengembalian.*.tipe_gas_id'] = 'required|exists:tipe_gas,id';
            $rules['items_pengembalian.*.jumlah_kembali'] = 'required|integer|min:1';
        }

        if (!$isPenjualan && !$isPengembalian) {
            return redirect()->back()->with('error', 'Minimal harus ada 1 item Penjualan atau 1 item Pengembalian yang diisi.');
        }

        $request->validate($rules);

        DB::beginTransaction();
        try {
            // --- LOGIKA PELANGGAN OTOMATIS ---
            if ($request->no_kk) {
                // Cek apakah pelanggan dengan No KK ini sudah ada
                $pelanggan = Pelanggan::where('no_kk', $request->no_kk)->first();

                if (!$pelanggan) {
                    // Jika belum ada, buat baru
                    Pelanggan::create([
                        'nama' => $request->nama_pembeli,
                        'no_kk' => $request->no_kk,
                        'no_telp' => $request->no_telp,
                    ]);
                } else {
                    // Opsional: Update nama atau no_telp jika berubah
                    $pelanggan->update([
                        'nama' => $request->nama_pembeli,
                        'no_telp' => $request->no_telp,
                    ]);
                }
            }
            $tanggalTransaksi = Carbon::now();

            // **PERBAIKAN 1: Buat satu kode transaksi utama**
            $kodeTransaksiUtama = 'TRX-' . strtoupper(Str::random(8));

            // ------------------------------------------------------------------------
            // 2. LOGIKA PENJUALAN
            // ------------------------------------------------------------------------
            if ($isPenjualan) {
                $kodePenjualan = $kodeTransaksiUtama;

                foreach ($request->items_penjualan as $item) {
                    $tipeGasId = $item['tipe_gas_id'];
                    $jumlahDibutuhkan = (int) $item['jumlah'];

                    // Skip item jika jumlah 0 (walaupun sudah divalidasi, ini untuk keamanan)
                    if ($jumlahDibutuhkan <= 0) {
                        continue;
                    }

                    $masterTipeGas = TipeGas::find($tipeGasId);
                    if (!$masterTipeGas) {
                        throw new \Exception("Tipe Gas ID {$tipeGasId} tidak ditemukan.");
                    }
                    $hargaJualMaster = $masterTipeGas->harga_jual;

                    // Cek ketersediaan total stok
                    $totalStokTipeIni = StokGas::where('tipe_gas_id', $tipeGasId)->sum('jumlah_penuh');
                    if ($totalStokTipeIni < $jumlahDibutuhkan) {
                        $namaGas = $masterTipeGas->nama;
                        throw new \Exception("Stok {$namaGas} tidak mencukupi untuk dijual. Stok tersedia: {$totalStokTipeIni}, dibutuhkan: {$jumlahDibutuhkan}.");
                    }

                    $stokTersedia = StokGas::where('tipe_gas_id', $tipeGasId)->where('jumlah_penuh', '>', 0)->orderBy('tanggal_masuk', 'asc')->get();

                    $sisaKebutuhan = $jumlahDibutuhkan;

                    // Loop melalui setiap batch stok dan kurangi
                    foreach ($stokTersedia as $stok) {
                        if ($sisaKebutuhan <= 0) {
                            break;
                        }

                        $stokAwalBatch = $stok->jumlah_penuh;
                        $jumlahDiambilDariBatch = min($sisaKebutuhan, $stok->jumlah_penuh);

                        PenjualanGas::create([
                            'kode_transaksi' => $kodePenjualan, // Kode Utama
                            'nama_pembeli' => $request->nama_pembeli,
                            'no_kk' => $request->no_kk,
                            'no_telp' => $request->no_telp,
                            'produk_id' => $stok->id,
                            'jumlah' => $jumlahDiambilDariBatch,
                            'harga_jual_satuan' => $hargaJualMaster,
                            'total_harga' => $jumlahDiambilDariBatch * $hargaJualMaster,
                            'tanggal_transaksi' => $tanggalTransaksi,
                            'keterangan' => $request->keterangan,
                        ]);

                        $stok->decrement('jumlah_penuh', $jumlahDiambilDariBatch);

                        $this->catatMutasi([
                            'produk_id' => $stok->id,
                            'tipe_id' => $stok->tipe_gas_id,
                            'stok_awal' => $stokAwalBatch,
                            'stok_masuk' => 0,
                            'stok_keluar' => $jumlahDiambilDariBatch,
                            'stok_akhir' => $stok->jumlah_penuh,
                            'total_harga' => $jumlahDiambilDariBatch * $hargaJualMaster,
                            'kode_mutasi' => 'K',
                            'ket_mutasi' => 'Penjualan - ' . $kodePenjualan,
                            'tanggal' => $tanggalTransaksi,
                        ]);

                        $sisaKebutuhan -= $jumlahDiambilDariBatch;
                    }
                }
            }

            // ------------------------------------------------------------------------
            // 3. LOGIKA PENGEMBALIAN
            // ------------------------------------------------------------------------
            if ($isPengembalian) {
                $kodePengembalian = $kodeTransaksiUtama; // Kode Utama

                foreach ($request->items_pengembalian as $item) {
                    $tipeGasId = $item['tipe_gas_id'];
                    $jumlahPengembalian = (int) $item['jumlah_kembali'];

                    // Skip item jika jumlah 0
                    if ($jumlahPengembalian <= 0) {
                        continue;
                    }

                    // Cari record StokGas PERTAMA
                    $stok = StokGas::where('tipe_gas_id', $tipeGasId)->first();

                    if (!$stok) {
                        $tipeGasNama = TipeGas::find($tipeGasId)->nama ?? 'yang dipilih';
                        throw new \Exception("Tidak ada data stok sama sekali untuk Tipe Gas {$tipeGasNama}. Pengembalian tidak bisa diproses.");
                    }

                    $stokPengembalianAwal = $stok->jumlah_pengembalian ?? 0;

                    // Catat di tabel PengembalianGas
                    PengembalianGas::create([
                        'kode' => $kodePengembalian, // Kode Utama
                        'nama_pembeli' => $request->nama_pembeli,
                        'no_kk' => $request->no_kk ?? null,
                        'no_telp' => $request->no_telp ?? null,
                        'produk_id' => $stok->id,
                        'jumlah' => $jumlahPengembalian,
                        'tanggal_pengembalian' => $tanggalTransaksi,
                        'keterangan' => $request->keterangan ?? null,
                    ]);

                    // Tambahkan ke jumlah_pengembalian
                    $stok->jumlah_pengembalian = ($stok->jumlah_pengembalian ?? 0) + $jumlahPengembalian;
                    $stok->save();

                    // Catat mutasi
                    $this->catatMutasi([
                        'produk_id' => $stok->id,
                        'tipe_id' => $stok->tipe_gas_id,
                        'stok_awal' => $stokPengembalianAwal,
                        'stok_masuk' => $jumlahPengembalian,
                        'stok_keluar' => 0,
                        'stok_akhir' => $stok->jumlah_pengembalian,
                        'total_harga' => 0,
                        'kode_mutasi' => 'M',
                        'ket_mutasi' => 'Pengembalian - ' . $kodePengembalian,
                        'tanggal' => $tanggalTransaksi,
                    ]);
                }
            }

            DB::commit();

            // Pesan Sukses yang lebih informatif
            $msg = [];
            if ($isPenjualan && $isPengembalian) {
                $msg[] = "Transaksi penjualan dan pengembalian ({$kodeTransaksiUtama}) berhasil disimpan.";
            } elseif ($isPenjualan) {
                $msg[] = "Transaksi penjualan ({$kodeTransaksiUtama}) berhasil disimpan.";
            } elseif ($isPengembalian) {
                $msg[] = "Transaksi pengembalian ({$kodeTransaksiUtama}) berhasil disimpan.";
            }

            // Menggunakan nama route yang diperbarui
            return redirect()->route('transaksi.penjualan.index')->with('success', implode(' ', $msg));
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error saat transaksi gabungan: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'Gagal menyimpan transaksi: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function printStruk($kodeTransaksi)
    {
        // Ambil semua item penjualan dengan kode transaksi ini
        $penjualanItems = PenjualanGas::with(['stokGas.tipeGas'])
            ->where('kode_transaksi', $kodeTransaksi)
            ->get();

        // Ambil semua item pengembalian dengan kode transaksi ini
        $pengembalianItems = PengembalianGas::with(['stokGas.tipeGas'])
            ->where('kode', $kodeTransaksi)
            ->get();

        // Cek apakah ada data sama sekali
        if ($penjualanItems->isEmpty() && $pengembalianItems->isEmpty()) {
            return redirect()->route('transaksi.penjualan.index')->with('error', 'Transaksi tidak ditemukan.');
        }

        // Ambil informasi dasar (dari salah satu item jika ada)
        $firstItem = $penjualanItems->first() ?? $pengembalianItems->first();

        $data = [
            'kode_transaksi' => $kodeTransaksi,
            'tanggal_transaksi' => $penjualanItems->first()->tanggal_transaksi ?? $pengembalianItems->first()->tanggal_pengembalian,
            'nama_pembeli' => $firstItem->nama_pembeli,
            'no_kk' => $firstItem->no_kk,
            'no_telp' => $firstItem->no_telp,
            'keterangan' => $firstItem->keterangan,
            'penjualan_items' => $penjualanItems,
            'pengembalian_items' => $pengembalianItems,
            'total_penjualan' => $penjualanItems->sum('total_harga'),
        ];

        // Tampilkan view struk
        return view('transaksi.penjualan.struk', $data);
    }
    /**
     * Method untuk mencatat mutasi stok
     */
    private function catatMutasi($data)
    {
        try {
            MutasiGas::create([
                'produk_id' => $data['produk_id'],
                'tipe_id' => $data['tipe_id'],
                'stok_awal' => $data['stok_awal'],
                'stok_masuk' => $data['stok_masuk'],
                'stok_keluar' => $data['stok_keluar'],
                'stok_akhir' => $data['stok_akhir'],
                'total_harga' => $data['total_harga'],
                'kode_mutasi' => $data['kode_mutasi'],
                'ket_mutasi' => $data['ket_mutasi'],
                'tanggal' => $data['tanggal'],
            ]);
        } catch (\Exception $e) {
            Log::error('Error saat mencatat mutasi: ' . $e->getMessage());
            throw $e;
        }
    }
}
