<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Dashboard as BaseDashboard;

class DashboardPage extends BaseDashboard
{
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationLabel = 'Dashboard';
    protected static ?int $navigationSort = 0;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\DashboardFilterWidget::class,
            \App\Filament\Widgets\KeuanganStatsWidget::class,
            \App\Filament\Widgets\KeuanganChartWidget::class,
            \App\Filament\Widgets\KategoriPemasukanChart::class,
            \App\Filament\Widgets\KategoriPengeluaranChart::class,
            \App\Filament\Widgets\PengajuanBarangStatsWidget::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [];
    }

    public function getHeaderWidgetsColumns(): int | array
    {
        return [
            'md' => 2,
            'xl' => 3,
        ];
    }


}
