<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pembelian_gas', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pembelian');
            $table->foreignId('tipe_id')->constrained('tipe_gas')->onDelete('cascade');
            $table->foreignId('vendor_id')->constrained('vendor')->onDelete('cascade');
            $table->integer('jumlah');
            $table->decimal('harga_beli', 12, 0);
            $table->date('tanggal_masuk');
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembelian_gas');
    }
};
