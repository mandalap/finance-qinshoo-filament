<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailBarangPengajuan extends Model
{
    protected $table = 'detail_barang_pengajuan';
    
    protected $fillable = [
        'pengajuan_barang_id',
        'nama_barang',
        'spesifikasi_barang',
        'jumlah',
        'satuan',
        'estimasi_harga',
    ];
    
    protected $casts = [
        'estimasi_harga' => 'decimal:2',
    ];
    
    // Relationship
    public function pengajuan()
    {
        return $this->belongsTo(PengajuanBarang::class, 'pengajuan_barang_id');
    }
}
