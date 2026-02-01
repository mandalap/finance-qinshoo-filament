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
        // Add soft deletes to transaksi_keuangan
        Schema::table('transaksi_keuangan', function (Blueprint $table) {
            $table->softDeletes();
        });

        // Add soft deletes to kategori_transaksi
        Schema::table('kategori_transaksi', function (Blueprint $table) {
            $table->softDeletes();
        });

        // Add soft deletes to pengajuan_barang
        Schema::table('pengajuan_barang', function (Blueprint $table) {
            $table->softDeletes();
        });

        // Add soft deletes to detail_barang_pengajuan
        Schema::table('detail_barang_pengajuan', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi_keuangan', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('kategori_transaksi', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('pengajuan_barang', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('detail_barang_pengajuan', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
