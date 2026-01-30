<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanBarang extends Model
{
    protected $table = 'pengajuan_barang';
    
    protected $fillable = [
        'nomor_pengajuan',
        'tanggal_pengajuan',
        'nama_pengaju',
        'divisi',
        'jabatan',
        'kontak',
        'tujuan_pengajuan',
        'tanggal_dibutuhkan',
        'tingkat_urgensi',
        'status',
        'catatan_persetujuan',
        'disetujui_oleh',
        'tanggal_persetujuan',
        'bukti_transaksi',
    ];
    
    protected $casts = [
        'tanggal_pengajuan' => 'date',
        'tanggal_dibutuhkan' => 'date',
        'tanggal_persetujuan' => 'datetime',
        'tingkat_urgensi' => \App\TingkatUrgensi::class,
        'status' => \App\StatusPengajuan::class,
    ];
    
    // Relationship
    public function approver()
    {
        return $this->belongsTo(\App\Models\User::class, 'disetujui_oleh');
    }
    
    public function detailBarang()
    {
        return $this->hasMany(\App\Models\DetailBarangPengajuan::class, 'pengajuan_barang_id');
    }
    
    // Auto-generate nomor pengajuan
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->nomor_pengajuan)) {
                $model->nomor_pengajuan = self::generateNomorPengajuan();
            }
            
            if (empty($model->tanggal_pengajuan)) {
                $model->tanggal_pengajuan = now();
            }
        });
    }
    
    public static function generateNomorPengajuan(): string
    {
        $prefix = 'PB';
        $year = date('Y');
        $month = date('m');
        
        // Format: PB/YYYY/MM/XXXX
        $lastPengajuan = self::whereYear('tanggal_pengajuan', $year)
            ->whereMonth('tanggal_pengajuan', $month)
            ->orderBy('id', 'desc')
            ->first();
        
        $sequence = $lastPengajuan ? (int) substr($lastPengajuan->nomor_pengajuan, -4) + 1 : 1;
        
        return sprintf('%s/%s/%s/%04d', $prefix, $year, $month, $sequence);
    }
}
