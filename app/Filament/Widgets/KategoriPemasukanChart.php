<?php

namespace App\Filament\Widgets;

use App\Models\TransaksiKeuangan;
use Filament\Widgets\ChartWidget;

class KategoriPemasukanChart extends ChartWidget
{
    protected ?string $heading = '🥧 Pemasukan per Kategori (Bulan Ini)';
    
    protected static ?int $sort = 3;
    
    protected ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $data = TransaksiKeuangan::where('transaksi_keuangan.jenis', 'pemasukan')
            ->whereMonth('transaksi_keuangan.tanggal_transaksi', now()->month)
            ->whereYear('transaksi_keuangan.tanggal_transaksi', now()->year)
            ->join('kategori_transaksi', 'transaksi_keuangan.kategori_id', '=', 'kategori_transaksi.id')
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
