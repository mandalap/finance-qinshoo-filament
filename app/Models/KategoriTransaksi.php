<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriTransaksi extends Model
{
    protected $table = 'kategori_transaksi';
    
    protected $fillable = [
        'nama',
        'jenis',
        'deskripsi',
        'is_active',
    ];
    
    protected $casts = [
        'jenis' => \App\JenisTransaksi::class,
        'is_active' => 'boolean',
    ];
    
    // Relationship
    public function transaksi()
    {
        return $this->hasMany(TransaksiKeuangan::class, 'kategori_id');
    }
    
    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    public function scopePemasukan($query)
    {
        return $query->where('jenis', 'pemasukan');
    }
    
    public function scopePengeluaran($query)
    {
        return $query->where('jenis', 'pengeluaran');
    }
}
