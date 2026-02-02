<?php

namespace App\Filament\Widgets;

use App\Models\TransaksiKeuangan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Illuminate\Support\Facades\DB;

class KeuanganStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;
    
    // Enable polling untuk auto-refresh setiap 30 detik
    protected static ?string $pollingInterval = '30s';
    
    public ?string $startDate = null;
    public ?string $endDate = null;
    
    // Lazy loading untuk performa
    protected static bool $isLazy = true;
    
    public function mount(): void
    {
        // Ambil filter dari session jika ada
        $filter = session('dashboard_filter', []);
        $this->startDate = $filter['startDate'] ?? null;
        $this->endDate = $filter['endDate'] ?? null;
    }
    
    protected function getListeners(): array
    {
        return [
            'refresh-widgets' => 'refreshData',
        ];
    }
    
    public function refreshData(): void
    {
        // Baca ulang filter dari session
        $filter = session('dashboard_filter', []);
        $this->startDate = $filter['startDate'] ?? null;
        $this->endDate = $filter['endDate'] ?? null;
    }
    
    protected function getStats(): array
    {
        // Cache key berdasarkan filter
        $cacheKey = 'keuangan_stats_' . md5(($this->startDate ?? '') . '_' . ($this->endDate ?? ''));
        
        // Cache selama 30 detik (sesuai polling interval)
        return cache()->remember($cacheKey, 30, function () {
            // Optimasi: Gunakan single query dengan conditional aggregation
            $query = TransaksiKeuangan::query();
            
            if ($this->startDate) {
                $query->whereDate('tanggal_transaksi', '>=', $this->startDate);
            }
            
            if ($this->endDate) {
                $query->whereDate('tanggal_transaksi', '<=', $this->endDate);
            }
            
            // Single query untuk mendapatkan semua data sekaligus
            $stats = $query->selectRaw("
                SUM(CASE WHEN jenis = 'pemasukan' THEN nominal ELSE 0 END) as total_pemasukan,
                SUM(CASE WHEN jenis = 'pengeluaran' THEN nominal ELSE 0 END) as total_pengeluaran
            ")->first();
            
            $totalPemasukan = $stats->total_pemasukan ?? 0;
            $totalPengeluaran = $stats->total_pengeluaran ?? 0;
            $saldo = $totalPemasukan - $totalPengeluaran;
        
        // Query untuk bulan ini dan bulan lalu (optimized)
        $bulanIni = now();
        $bulanLalu = now()->subMonth();
        
        $monthlyStats = TransaksiKeuangan::selectRaw("
            MONTH(tanggal_transaksi) as bulan,
            YEAR(tanggal_transaksi) as tahun,
            SUM(CASE WHEN jenis = 'pemasukan' THEN nominal ELSE 0 END) as pemasukan,
            SUM(CASE WHEN jenis = 'pengeluaran' THEN nominal ELSE 0 END) as pengeluaran
        ")
        ->where(function($q) use ($bulanIni, $bulanLalu) {
            $q->where(function($q2) use ($bulanIni) {
                $q2->whereMonth('tanggal_transaksi', $bulanIni->month)
                   ->whereYear('tanggal_transaksi', $bulanIni->year);
            })
            ->orWhere(function($q2) use ($bulanLalu) {
                $q2->whereMonth('tanggal_transaksi', $bulanLalu->month)
                   ->whereYear('tanggal_transaksi', $bulanLalu->year);
            });
        })
        ->groupByRaw("MONTH(tanggal_transaksi), YEAR(tanggal_transaksi)")
        ->get()
        ->keyBy(function($item) {
            return $item->tahun . '-' . $item->bulan;
        });
        
        $keyBulanIni = $bulanIni->year . '-' . $bulanIni->month;
        $keyBulanLalu = $bulanLalu->year . '-' . $bulanLalu->month;
        
        $pemasukanBulanIni = $monthlyStats->get($keyBulanIni)?->pemasukan ?? 0;
        $pengeluaranBulanIni = $monthlyStats->get($keyBulanIni)?->pengeluaran ?? 0;
        $pemasukanBulanLalu = $monthlyStats->get($keyBulanLalu)?->pemasukan ?? 0;
        $pengeluaranBulanLalu = $monthlyStats->get($keyBulanLalu)?->pengeluaran ?? 0;
        
        // Hitung persentase perubahan
        $perubahanPemasukan = 0;
        if ($pemasukanBulanLalu > 0) {
            $perubahanPemasukan = (($pemasukanBulanIni - $pemasukanBulanLalu) / $pemasukanBulanLalu) * 100;
        } elseif ($pemasukanBulanIni > 0) {
            $perubahanPemasukan = 100;
        }
        
        $perubahanPengeluaran = 0;
        if ($pengeluaranBulanLalu > 0) {
            $perubahanPengeluaran = (($pengeluaranBulanIni - $pengeluaranBulanLalu) / $pengeluaranBulanLalu) * 100;
        } elseif ($pengeluaranBulanIni > 0) {
            $perubahanPengeluaran = 100;
        }
        
        // Label periode
        $periodeLabel = 'Total Keseluruhan';
        if ($this->startDate || $this->endDate) {
            $periodeLabel = 'Periode: ';
            if ($this->startDate) {
                $periodeLabel .= date('d M Y', strtotime($this->startDate));
            }
            if ($this->endDate) {
                $periodeLabel .= ' - ' . date('d M Y', strtotime($this->endDate));
            }
        }

        return [
            Stat::make('Saldo Saat Ini', 'Rp ' . number_format($saldo, 0, ',', '.'))
                ->description($periodeLabel)
                ->descriptionIcon('heroicon-o-banknotes')
                ->color($saldo >= 0 ? 'success' : 'danger'),
                
            Stat::make('Total Pemasukan', 'Rp ' . number_format($totalPemasukan, 0, ',', '.'))
                ->description(
                    'Bulan ini: Rp ' . number_format($pemasukanBulanIni, 0, ',', '.') . 
                    ' (' . ($perubahanPemasukan >= 0 ? '+' : '') . number_format($perubahanPemasukan, 1) . '% vs bulan lalu)'
                )
                ->descriptionIcon($perubahanPemasukan >= 0 ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down')
                ->color('success'),
                
            Stat::make('Total Pengeluaran', 'Rp ' . number_format($totalPengeluaran, 0, ',', '.'))
                ->description(
                    'Bulan ini: Rp ' . number_format($pengeluaranBulanIni, 0, ',', '.') . 
                    ' (' . ($perubahanPengeluaran >= 0 ? '+' : '') . number_format($perubahanPengeluaran, 1) . '% vs bulan lalu)'
                )
                ->descriptionIcon($perubahanPengeluaran >= 0 ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down')
                ->color('danger'),
        ];
        }); // Close cache()->remember()
    }
}
