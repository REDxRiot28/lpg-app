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
        Schema::create('stok_gas', function (Blueprint $table) {
            $table->id();
            $table->string('kode');
            $table->foreignId('vendor_id')->constrained('vendor')->onDelete('cascade')->nullable();
            $table->foreignId('tipe_gas_id')->constrained('tipe_gas')->onDelete('cascade')->nullable();
            $table->integer('jumlah_penuh')->nullable();
            $table->integer('jumlah_pengembalian')->nullable();
            $table->integer('jumlah_rusak')->nullable();
            $table->decimal('harga_beli', 12, 2)->nullable();
            $table->decimal('harga_jual', 12, 2)->nullable();
            $table->date('tanggal_masuk')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_gas');
    }
};
