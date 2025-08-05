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
        Schema::create('stok_barangs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_barang')->unique();
            $table->integer('stok_awal')->default(0); // awal saat barang pertama kali ditambahkan
            $table->integer('jumlah_masuk')->default(0); // barang masuk tambahan
            $table->integer('stok_akhir')->default(0); // otomatis dihitung: stok_awal + masuk - keluar
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_barangs');
    }
};
