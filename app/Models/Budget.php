<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Budget extends Model
{
    use SoftDeletes;
    
    protected $table = 'budgets';
    
    protected $fillable = [
        'kategori_id',
        'tahun',
        'bulan',
        'nominal_budget',
        'keterangan',
        'is_active',
    ];
    
    protected $casts = [
        'tahun' => 'integer',
        'bulan' => 'integer',
        'nominal_budget' => 'decimal:2',
        'is_active' => 'boolean',
    ];
    
    // Relationships
    public function kategori()
    {
        return $this->belongsTo(KategoriTransaksi::class, 'kategori_id');
    }
    
    public function transaksi()
    {
        return $this->hasMany(TransaksiKeuangan::class, 'kategori_id', 'kategori_id')
            ->whereYear('tanggal_transaksi', $this->tahun)
            ->whereMonth('tanggal_transaksi', $this->bulan);
    }
    
    // Accessors
    public function getRealisasiAttribute()
    {
        return $this->transaksi()->sum('nominal') ?? 0;
    }
    
    public function getSisaBudgetAttribute()
    {
        return $this->nominal_budget - $this->getRealisasiAttribute();
    }
    
    public function getPersentaseRealisasiAttribute()
    {
        if ($this->nominal_budget == 0) return 0;
        return ($this->getRealisasiAttribute() / $this->nominal_budget) * 100;
    }
    
    public function getStatusAttribute()
    {
        $persentase = $this->getPersentaseRealisasiAttribute();
        
        if ($persentase >= 100) return 'over_budget';
        if ($persentase >= 90) return 'warning';
        if ($persentase >= 75) return 'caution';
        return 'safe';
    }
    
    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    public function scopeByPeriod($query, $tahun, $bulan)
    {
        return $query->where('tahun', $tahun)->where('bulan', $bulan);
    }
    
    public function scopeCurrentMonth($query)
    {
        return $query->where('tahun', date('Y'))->where('bulan', date('m'));
    }
}
