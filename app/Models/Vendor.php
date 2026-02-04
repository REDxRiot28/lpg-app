<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $table = 'vendor';

    protected $fillable = [
        'kode_vendor',
        'nama_vendor',
        'alamat',
        'telepon',
        'email',
        'kontak_person',
        'status_aktif'
    ];

    protected $casts = [
        'status_aktif' => 'boolean'
    ];

    public function stokGas()
    {
        return $this->hasMany(StokGas::class);
    }

    public function pembelianGas()
    {
        return $this->hasMany(PembelianGas::class);
    }
}
