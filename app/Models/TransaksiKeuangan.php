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
                $transaksi->nomor_transaksi = static::generateNomorTransaksi();
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
        $year = date('Y');
        $month = date('m');

        // Try to generate sequential number first
        $nomorTransaksi = \DB::transaction(function () use ($year, $month) {
            // ✅ FIX: Tambah withTrashed() supaya data soft-deleted ikut dihitung
            $lastTransaksi = static::withTrashed()
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->whereRaw("nomor_transaksi REGEXP '^TRX-[0-9]{4}-[0-9]{2}-[0-9]{4}$'")
                ->orderByRaw('CAST(SUBSTRING(nomor_transaksi, -4) AS UNSIGNED) DESC')
                ->lockForUpdate()
                ->first();

            if ($lastTransaksi) {
                // Extract sequence from last transaction number
                $lastNumber = $lastTransaksi->nomor_transaksi;
                $sequence = intval(substr($lastNumber, -4)) + 1;
            } else {
                $sequence = 1;
            }

            // Format: TRX-YYYY-MM-NNNN
            return sprintf('TRX-%s-%s-%04d', $year, $month, $sequence);
        });

        // ✅ FIX: Pengecekan exists juga harus pakai withTrashed()
        $exists = static::withTrashed()
            ->where('nomor_transaksi', $nomorTransaksi)
            ->exists();

        if ($exists) {
            // If duplicate, add microsecond timestamp suffix
            $microtime = (int)(microtime(true) * 10000);
            $suffix = base_convert($microtime, 10, 36);
            return sprintf('TRX-%s-%s-%s', $year, $month, strtoupper($suffix));
        }

        return $nomorTransaksi;
    }
}
