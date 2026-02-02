<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class TransaksiKeuangan extends Model
{
    use SoftDeletes, LogsActivity;
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
        'bukti_path' => 'array',
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
                $maxRetries = 5;
                $attempt = 0;
                
                while ($attempt < $maxRetries) {
                    $nomorTransaksi = static::generateNomorTransaksi();
                    
                    // Check if this number already exists
                    $exists = static::where('nomor_transaksi', $nomorTransaksi)->exists();
                    
                    if (!$exists) {
                        $transaksi->nomor_transaksi = $nomorTransaksi;
                        break;
                    }
                    
                    $attempt++;
                    
                    // If still duplicate after retries, add random suffix
                    if ($attempt >= $maxRetries) {
                        $transaksi->nomor_transaksi = $nomorTransaksi . '-' . strtoupper(substr(uniqid(), -4));
                    }
                    
                    // Small delay to avoid race condition
                    usleep(100000); // 100ms
                }
            }
        });
    }
    
    public function getRouteKeyName()
    {
        return 'nomor_transaksi';
    }
    
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nomor_transaksi', 'tanggal_transaksi', 'jenis', 'kategori_id', 'nominal', 'deskripsi'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Transaksi {$eventName}")
            ->useLogName('transaksi_keuangan');
    }
    
    public static function generateNomorTransaksi()
    {
        return \DB::transaction(function () {
            $year = date('Y');
            $month = date('m');
            
            // Use lockForUpdate to prevent race conditions
            $lastTransaksi = static::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->orderBy('nomor_transaksi', 'desc')
                ->lockForUpdate()
                ->first();
            
            $sequence = $lastTransaksi ? intval(substr($lastTransaksi->nomor_transaksi, -4)) + 1 : 1;
            
            // Format: TRX-YYYY-MM-NNNN (URL-safe dengan dash)
            return sprintf('TRX-%s-%s-%04d', $year, $month, $sequence);
        });
    }
}
