<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanGas extends Model
{
    use HasFactory;

    protected $table = 'penjualan_gas';

    protected $fillable = [
        'kode_transaksi',
        'nama_pembeli',
        'no_kk',
        'no_telp',
        'produk_id',
        'jumlah',
        'harga_jual_satuan',
        'total_harga',
        'tanggal_transaksi',
        'keterangan'
    ];

    protected $casts = [
        'harga_jual_satuan' => 'decimal:2',
        'total_harga' => 'decimal:2',
        'tanggal_transaksi' => 'datetime'
    ];

    public function stokGas()
    {
        return $this->belongsTo(StokGas::class, 'produk_id');
    }
    
}
