<?php

namespace App\Filament\Widgets;

use App\Models\PengajuanBarang;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PengajuanBarangStatsWidget extends BaseWidget
{
    protected static ?int $sort = 5;
    
    protected function getStats(): array
    {
        // Total pengajuan
        $totalPengajuan = PengajuanBarang::count();
        
        // Pengajuan pending
        $pending = PengajuanBarang::where('status', 'pending')->count();
        
        // Pengajuan approved
        $approved = PengajuanBarang::where('status', 'approved')->count();
        
        // Pengajuan rejected
        $rejected = PengajuanBarang::where('status', 'rejected')->count();
        
        // Pengajuan bulan ini
        $pengajuanBulanIni = PengajuanBarang::whereMonth('tanggal_pengajuan', now()->month)
            ->whereYear('tanggal_pengajuan', now()->year)
            ->count();

        return [
            Stat::make('Total Pengajuan', $totalPengajuan)
                ->description('Semua pengajuan barang')
                ->descriptionIcon('heroicon-o-document-text')
                ->color('primary'),
                
            Stat::make('Menunggu Persetujuan', $pending)
                ->description('Pengajuan pending')
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning'),
                
            Stat::make('Disetujui', $approved)
                ->description('Pengajuan approved')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),
                
            Stat::make('Ditolak', $rejected)
                ->description('Pengajuan rejected')
                ->descriptionIcon('heroicon-o-x-circle')
                ->color('danger'),
        ];
    }
}
