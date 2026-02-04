<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TipeGas;

class TipeGasSeeder extends Seeder
{
    public function run(): void
    {
        TipeGas::create(['nama' => 'Gas LPG 3 Kg', 'harga_jual' => 21000]);
        TipeGas::create(['nama' => 'Gas LPG 12 Kg', 'harga_jual' => 215000]);
        TipeGas::create(['nama' => 'Gas Bright 5.5 Kg', 'harga_jual' => 110000]);
        TipeGas::create(['nama' => 'Gas Bright 12 Kg', 'harga_jual' => 230000]);
    }
}