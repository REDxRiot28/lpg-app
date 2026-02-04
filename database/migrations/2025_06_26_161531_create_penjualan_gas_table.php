<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('penjualan_gas', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi');
            $table->string('nama_pembeli');
            $table->string('no_kk')->nullable();
            $table->string('no_telp')->nullable();
            $table->foreignId('produk_id')->constrained('stok_gas')->onDelete('cascade');
            $table->integer('jumlah');
            $table->decimal('harga_jual_satuan', 12, 2);
            $table->decimal('total_harga', 12, 2);
            $table->dateTime('tanggal_transaksi');
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualan_gas');
    }
};
