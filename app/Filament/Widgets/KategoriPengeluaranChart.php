<?php

namespace App\Filament\Widgets;

use App\Models\TransaksiKeuangan;
use Filament\Widgets\ChartWidget;

class KategoriPengeluaranChart extends ChartWidget
{
    protected ?string $heading = '🥧 Pengeluaran per Kategori (Bulan Ini)';
    
    protected static ?int $sort = 4;
    
    protected ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $data = TransaksiKeuangan::where('transaksi_keuangan.jenis', 'pengeluaran')
            ->whereMonth('transaksi_keuangan.tanggal_transaksi', now()->month)
            ->whereYear('transaksi_keuangan.tanggal_transaksi', now()->year)
            ->join('kategori_transaksi', 'transaksi_keuangan.kategori_id', '=', 'kategori_transaksi.id')
            ->selectRaw('kategori_transaksi.nama, SUM(transaksi_keuangan.nominal) as total')
            ->groupBy('kategori_transaksi.nama')
            ->get();
        
        return [
            'datasets' => [
                [
                    'label' => 'Pengeluaran',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => [
                        '#ef4444',
                        '#f97316',
                        '#eab308',
                        '#84cc16',
                        '#06b6d4',
                        '#6366f1',
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
