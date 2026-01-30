<?php

namespace App\Filament\Widgets;

use App\Models\TransaksiKeuangan;
use Filament\Widgets\ChartWidget;

class KategoriPemasukanChart extends ChartWidget
{
    protected ?string $heading = '🥧 Pemasukan per Kategori';
    
    protected static ?int $sort = 3;
    
    protected ?string $maxHeight = '300px';
    
    // Disable polling untuk performa
    protected ?string $pollingInterval = null;
    
    // Lazy loading untuk performa
    protected static bool $isLazy = true;
    
    public ?string $startDate = null;
    public ?string $endDate = null;

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
        $query = TransaksiKeuangan::where('transaksi_keuangan.jenis', 'pemasukan');
        
        // Apply date filter jika ada
        if ($this->startDate) {
            $query->whereDate('transaksi_keuangan.tanggal_transaksi', '>=', $this->startDate);
        }
        
        if ($this->endDate) {
            $query->whereDate('transaksi_keuangan.tanggal_transaksi', '<=', $this->endDate);
        }
        
        // Jika tidak ada filter, default ke bulan ini
        if (!$this->startDate && !$this->endDate) {
            $query->whereMonth('transaksi_keuangan.tanggal_transaksi', now()->month)
                  ->whereYear('transaksi_keuangan.tanggal_transaksi', now()->year);
        }
        
        $data = $query->join('kategori_transaksi', 'transaksi_keuangan.kategori_id', '=', 'kategori_transaksi.id')
            ->selectRaw('kategori_transaksi.nama, SUM(transaksi_keuangan.nominal) as total')
            ->groupBy('kategori_transaksi.nama')
            ->get();
        
        return [
            'datasets' => [
                [
                    'label' => 'Pemasukan',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => [
                        '#10b981',
                        '#3b82f6',
                        '#8b5cf6',
                        '#ec4899',
                        '#f59e0b',
                        '#14b8a6',
                    ],
                ],
            ],
            'labels' => $data->pluck('nama')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
