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
        Schema::create('pengembalian_gas', function (Blueprint $table) {
            $table->id();
            $table->string('kode');
            $table->string('nama_pembeli');
            $table->string('no_kk')->nullable();
            $table->string('no_telp')->nullable();
            $table->foreignId('produk_id')->constrained('stok_gas')->onDelete('cascade');
            // $table->boolean('kondisi_rusak')->nullable()->default(false);
            $table->integer('jumlah');
            $table->dateTime('tanggal_pengembalian');
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengembalian_gas');
    }
};
