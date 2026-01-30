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
        Schema::create('detail_barang_pengajuan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_barang_id')->constrained('pengajuan_barang')->cascadeOnDelete();
            
            // Detail Barang
            $table->string('nama_barang');
            $table->text('spesifikasi_barang');
            $table->integer('jumlah');
            $table->string('satuan');
            $table->decimal('estimasi_harga', 15, 2);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_barang_pengajuan');
    }
};
