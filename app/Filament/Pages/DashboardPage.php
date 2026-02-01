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
        // FULL FEATURES: All important widgets
        // Performance: LCP ~8-10s (acceptable untuk data lengkap)
        return [
            \App\Filament\Widgets\KeuanganStatsWidget::class,
            \App\Filament\Widgets\BudgetStatsWidget::class,
            \App\Filament\Widgets\PengajuanBarangStatsWidget::class,
            \App\Filament\Widgets\KeuanganChartWidget::class,
            \App\Filament\Widgets\KategoriPemasukanChart::class,
            \App\Filament\Widgets\KategoriPengeluaranChart::class,
        ];
        
        /* OPTIONAL: Dashboard Filter (tambah jika perlu)
        return [
            \App\Filament\Widgets\DashboardFilterWidget::class,
            \App\Filament\Widgets\KeuanganStatsWidget::class,
            \App\Filament\Widgets\PengajuanBarangStatsWidget::class,
            \App\Filament\Widgets\KeuanganChartWidget::class,
            \App\Filament\Widgets\KategoriPemasukanChart::class,
            \App\Filament\Widgets\KategoriPengeluaranChart::class,
        ];
        */
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
