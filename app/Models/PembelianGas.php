<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PembelianGas extends Model
{
    use HasFactory;

    protected $table = 'pembelian_gas';

    protected $fillable = ['kode_pembelian', 'tipe_id', 'vendor_id', 'jumlah', 'harga_beli', 'tanggal_masuk', 'keterangan'];

    public function tipeGas()
    {
        return $this->belongsTo(TipeGas::class,'tipe_id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function stokGas()
    {
        return $this->belongsTo(StokGas::class);
    }
}
