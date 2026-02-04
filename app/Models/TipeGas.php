<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipeGas extends Model
{
    use HasFactory;

    protected $table = 'tipe_gas';

    protected $fillable = ['nama','harga_jual'];

    public function stokGas()
    {
        return $this->hasMany(StokGas::class, 'tipe_gas_id');
    }
}
