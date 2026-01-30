<?php

namespace App\Filament\Widgets;

use App\Models\TransaksiKeuangan;
use Filament\Widgets\ChartWidget;

class PemasukanPengeluaranChart extends ChartWidget
{
    protected ?string $heading = '📊 Pemasukan vs Pengeluaran (6 Bulan Terakhir)';
    
    protected static ?int $sort = 2;
    
    protected ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $labels = [];
        $pemasukanData = [];
        $pengeluaranData = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $labels[] = $date->format('M Y');
            
            $pemasukanData[] = TransaksiKeuangan::where('jenis', 'pemasukan')
                ->whereMonth('tanggal_transaksi', $date->month)
                ->whereYear('tanggal_transaksi', $date->year)
                ->sum('nominal');
                
            $pengeluaranData[] = TransaksiKeuangan::where('jenis', 'pengeluaran')
                ->whereMonth('tanggal_transaksi', $date->month)
                ->whereYear('tanggal_transaksi', $date->year)
                ->sum('nominal');
        }
        
        return [
            'datasets' => [
                [
                    'label' => 'Pemasukan',
                    'data' => $pemasukanData,
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'fill' => true,
                ],
                [
                    'label' => 'Pengeluaran',
                    'data' => $pengeluaranData,
                    'borderColor' => '#ef4444',
                    'backgroundColor' => 'rgba(239, 68, 68, 0.1)',
                    'fill' => true,
                ],
            ],
            'labels' => $labels,
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
                ],
            ],
            'scales' => [
                'y' => [
                    'ticks' => [
                        'callback' => 'function(value) { return "Rp " + value.toLocaleString("id-ID"); }',
                    ],
                ],
            ],
        ];
    }
}
