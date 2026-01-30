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
            $table->uuid('uuid')->after('id')->nullable()->unique();
        });
        
        // Populate existing records
        $ids = \Illuminate\Support\Facades\DB::table('pengajuan_barang')->pluck('id');
        foreach ($ids as $id) {
            \Illuminate\Support\Facades\DB::table('pengajuan_barang')
                ->where('id', $id)
                ->update(['uuid' => \Illuminate\Support\Str::uuid()]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan_barang', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};
