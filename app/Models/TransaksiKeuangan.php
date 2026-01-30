<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class TransaksiKeuangan extends Model
{
    protected $table = 'transaksi_keuangan';
    
    protected $fillable = [
        'nomor_transaksi',
        'tanggal_transaksi',
        'jenis',
        'kategori_id',
        'nominal',
        'deskripsi',
        'bukti_path',
        'created_by',
    ];
    
    protected $casts = [
        'tanggal_transaksi' => 'date',
        'nominal' => 'decimal:2',
        'jenis' => \App\JenisTransaksi::class,
    ];
    
    // Relationships
    public function kategori()
    {
        return $this->belongsTo(KategoriTransaksi::class, 'kategori_id');
    }
    
    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }
    
    // Accessors
    public function getBuktiUrlAttribute()
    {
        return $this->bukti_path ? Storage::url($this->bukti_path) : null;
    }
    
    // Scopes
    public function scopePemasukan($query)
    {
        return $query->where('jenis', 'pemasukan');
    }
    
    public function scopePengeluaran($query)
    {
        return $query->where('jenis', 'pengeluaran');
    }
    
    public function scopeByPeriod($query, $startDate, $endDate)
    {
        return $query->whereBetween('tanggal_transaksi', [$startDate, $endDate]);
    }
    
    public function scopeByMonth($query, $year, $month)
    {
        return $query->whereYear('tanggal_transaksi', $year)
                     ->whereMonth('tanggal_transaksi', $month);
    }
    
    public function scopeByYear($query, $year)
    {
        return $query->whereYear('tanggal_transaksi', $year);
    }
    
    // Auto-generate nomor transaksi
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($transaksi) {
            if (empty($transaksi->nomor_transaksi)) {
                $transaksi->nomor_transaksi = static::generateNomorTransaksi();
            }
        });
    }
    
    public static function generateNomorTransaksi()
    {
        $year = date('Y');
        $month = date('m');
        
        $lastTransaksi = static::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();
        
        $sequence = $lastTransaksi ? intval(substr($lastTransaksi->nomor_transaksi, -4)) + 1 : 1;
        
        return sprintf('TRX/%s/%s/%04d', $year, $month, $sequence);
    }
}
