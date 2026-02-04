<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MutasiGas extends Model
{
    use HasFactory;

    protected $table = 'mutasi_gas';

    protected $fillable = [
        // 'kode',
        'tipe_id',
        'produk_id',
        'stok_awal',
        'stok_masuk',
        'stok_keluar',
        'stok_akhir',
        'total_harga',
        'kode_mutasi',
        'ket_mutasi',
        'tanggal',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'total_harga' => 'decimal:0',
    ];

    public function tipeGas()
    {
        return $this->belongsTo(TipeGas::class, 'tipe_id');
    }

    public function stokGas()
    {
        return $this->belongsTo(StokGas::class, 'produk_id');
    }

    /**
     * Get kode mutasi description
     */
    public function getKodeMutasiDescriptionAttribute()
    {
        $descriptions = [
            'M' => 'Masuk',
            'K' => 'Keluar',
            'P' => 'Pengembalian',
            'R' => 'Rusak',
            'A' => 'Adjustment',
            'H' => 'Edit Harga'
        ];

        return $descriptions[$this->kode_mutasi] ?? $this->kode_mutasi;
    }

    /**
     * Get kode mutasi badge class
     */
    public function getKodeMutasiBadgeClassAttribute()
    {
        $classes = [
            'M' => 'bg-success',
            'K' => 'bg-danger',
            'P' => 'bg-info',
            'R' => 'bg-warning',
            'A' => 'bg-secondary',
            'H' => 'bg-primary'
        ];

        return $classes[$this->kode_mutasi] ?? 'bg-dark';
    }
}
