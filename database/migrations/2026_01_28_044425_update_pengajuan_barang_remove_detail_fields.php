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
        Schema::table('pengajuan_barang', function (Blueprint $table) {
            $table->dropColumn([
                'nama_barang',
                'spesifikasi_barang',
                'jumlah',
                'satuan',
                'estimasi_harga',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan_barang', function (Blueprint $table) {
            $table->string('nama_barang');
            $table->text('spesifikasi_barang');
            $table->integer('jumlah');
            $table->string('satuan');
            $table->decimal('estimasi_harga', 15, 2);
        });
    }
};
