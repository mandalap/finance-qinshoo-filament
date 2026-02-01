<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing nomor_transaksi from slash format to dash format
        // TRX/2026/01/0001 -> TRX-2026-01-0001
        DB::table('transaksi_keuangan')
            ->whereNotNull('nomor_transaksi')
            ->get()
            ->each(function ($transaksi) {
                $newNomor = str_replace('/', '-', $transaksi->nomor_transaksi);
                DB::table('transaksi_keuangan')
                    ->where('id', $transaksi->id)
                    ->update(['nomor_transaksi' => $newNomor]);
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to slash format
        // TRX-2026-01-0001 -> TRX/2026/01/0001
        DB::table('transaksi_keuangan')
            ->whereNotNull('nomor_transaksi')
            ->get()
            ->each(function ($transaksi) {
                $newNomor = str_replace('-', '/', $transaksi->nomor_transaksi);
                DB::table('transaksi_keuangan')
                    ->where('id', $transaksi->id)
                    ->update(['nomor_transaksi' => $newNomor]);
            });
    }
};
