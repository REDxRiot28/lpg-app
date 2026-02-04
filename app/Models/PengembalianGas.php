<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PengembalianGas extends Model
{
    use HasFactory;

    protected $table = 'pengembalian_gas';

    protected $fillable = ['kode', 'nama_pembeli', 'no_kk', 'no_telp', 'produk_id', 'jumlah', 'tanggal_pengembalian', 'keterangan'];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
    public function stokGas()
    {
        return $this->belongsTo(StokGas::class, 'produk_id');
    }
}
