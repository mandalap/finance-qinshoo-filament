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
        Schema::create('pengajuan_barang', function (Blueprint $table) {
            $table->id();
            
            // Nomor Pengajuan
            $table->string('nomor_pengajuan')->unique();
            $table->date('tanggal_pengajuan');
            
            // Data Pengaju
            $table->string('nama_pengaju');
            $table->string('divisi');
            $table->string('jabatan');
            $table->string('kontak')->nullable();
            
            // Detail Barang
            $table->string('nama_barang');
            $table->text('spesifikasi_barang');
            $table->integer('jumlah');
            $table->string('satuan');
            $table->decimal('estimasi_harga', 15, 2);
            
            // Kebutuhan
            $table->text('tujuan_pengajuan');
            $table->date('tanggal_dibutuhkan');
            $table->enum('tingkat_urgensi', ['normal', 'mendesak'])->default('normal');
            
            // Approval
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled'])->default('pending');
            $table->text('catatan_persetujuan')->nullable();
            $table->foreignId('disetujui_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('tanggal_persetujuan')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_barang');
    }
};
