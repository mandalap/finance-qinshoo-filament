<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class KategoriTransaksi extends Model
{
    use SoftDeletes, LogsActivity;
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
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nama', 'jenis', 'deskripsi', 'is_active'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Kategori {$eventName}")
            ->useLogName('kategori_transaksi');
    }
}
