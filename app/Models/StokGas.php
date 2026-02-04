<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokGas extends Model
{
    use HasFactory;

    protected $table = 'stok_gas';

    protected $fillable = [
        'kode',
        'vendor_id',
        'tipe_gas_id',
        'jumlah_penuh',
        'jumlah_pengembalian',
        'jumlah_rusak',
        'harga_beli',
        'harga_jual',
        'tanggal_masuk'
    ];

    protected $casts = [
        'harga_beli' => 'decimal:2',
        'harga_jual' => 'decimal:2',
        'tanggal_masuk' => 'date'
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function tipeGas()
    {
        return $this->belongsTo(TipeGas::class);
    }

    public function penjualanGas()
    {
        return $this->hasMany(PenjualanGas::class, 'produk_id');
    }

    public function pengembalianGas()
    {
        return $this->hasMany(PengembalianGas::class, 'produk_id');
    }

    public function mutasiGas()
    {
        return $this->hasMany(MutasiGas::class, 'produk_id');
    }
}
