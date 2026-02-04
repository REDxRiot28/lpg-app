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
        Schema::create('mutasi_gas', function (Blueprint $table) {
            $table->id();
            // $table->string('kode');
            $table->foreignId('tipe_id')->nullable()->constrained('tipe_gas')->onDelete('cascade');
            $table->foreignId('produk_id')->nullable()->constrained('stok_gas')->onDelete('cascade');
            $table->integer('stok_awal');
            $table->integer('stok_masuk')->default(0);
            $table->integer('stok_keluar')->default(0);
            $table->integer('stok_akhir');
            $table->decimal('total_harga', 12, 0);
            $table->string('kode_mutasi'); // K, M, P
            $table->string('ket_mutasi'); // Keluar, Masuk, Pengembalian
            $table->date('tanggal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mutasi_gas');
    }
};
