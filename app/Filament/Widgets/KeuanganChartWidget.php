<?php

namespace App\Filament\Widgets;

use App\Models\TransaksiKeuangan;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Illuminate\Support\Facades\DB;

class KeuanganChartWidget extends ChartWidget
{
    protected ?string $heading = 'Tren Pemasukan vs Pengeluaran (6 Bulan Terakhir)';
    
    protected static ?int $sort = 2;
    
    // Disable polling untuk performa lebih baik
    protected ?string $pollingInterval = null;
    
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

    protected function getData(): array
    {
        $months = [];
        $pemasukan = [];
        $pengeluaran = [];
        
        // Jika ada filter custom, gunakan range tersebut
        if ($this->startDate && $this->endDate) {
            $startMonth = Carbon::parse($this->startDate)->startOfMonth();
            $endMonth = Carbon::parse($this->endDate)->endOfMonth();
        } else {
            // Default: 6 bulan terakhir
            $startMonth = now()->subMonths(5);
            $endMonth = now();
        }
        
        // Query optimized dengan groupBy (SQLite compatible)
        $query = TransaksiKeuangan::selectRaw("
            YEAR(tanggal_transaksi) as tahun,
            MONTH(tanggal_transaksi) as bulan,
            SUM(CASE WHEN jenis = 'pemasukan' THEN nominal ELSE 0 END) as pemasukan,
            SUM(CASE WHEN jenis = 'pengeluaran' THEN nominal ELSE 0 END) as pengeluaran
        ")
        ->whereBetween('tanggal_transaksi', [
            $startMonth->startOfMonth(),
            $endMonth->endOfMonth()
        ]);
        
        // Terapkan filter tanggal jika ada
        if ($this->startDate) {
            $query->whereDate('tanggal_transaksi', '>=', $this->startDate);
        }
        
        if ($this->endDate) {
            $query->whereDate('tanggal_transaksi', '<=', $this->endDate);
        }
        
        $monthlyData = $query
        ->groupByRaw("YEAR(tanggal_transaksi), MONTH(tanggal_transaksi)")
        ->orderBy('tahun', 'asc')
        ->orderBy('bulan', 'asc')
        ->get()
        ->keyBy(function($item) {
            return $item->tahun . '-' . str_pad($item->bulan, 2, '0', STR_PAD_LEFT);
        });
        
        // Generate labels dan data untuk 6 bulan terakhir
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthName = $date->locale('id')->format('M Y');
            $key = $date->format('Y-m');
            
            $data = $monthlyData->get($key);
            
            $months[] = $monthName;
            $pemasukan[] = $data?->pemasukan ?? 0;
            $pengeluaran[] = $data?->pengeluaran ?? 0;
        }
        
        return [
            'datasets' => [
                [
                    'label' => 'Pemasukan',
                    'data' => $pemasukan,
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
                [
                    'label' => 'Pengeluaran',
                    'data' => $pengeluaran,
                    'borderColor' => '#ef4444',
                    'backgroundColor' => 'rgba(239, 68, 68, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $months,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
    
    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'callback' => 'function(value) { return "Rp " + value.toLocaleString("id-ID"); }',
                    ],
                ],
            ],
        ];
    }
}
