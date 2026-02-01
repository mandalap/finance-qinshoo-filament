<?php


namespace App\Filament\Widgets;

use App\Models\Budget;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class BudgetStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 2;
    
    protected function getStats(): array
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;
        
        // Get budgets for current month
        $budgets = Budget::with('kategori')
            ->where('tahun', $currentYear)
            ->where('bulan', $currentMonth)
            ->where('is_active', true)
            ->get();
        
        // Calculate totals
        $totalBudget = $budgets->sum('nominal_budget');
        $totalRealisasi = $budgets->sum(fn($b) => $b->realisasi);
        $sisaBudget = $totalBudget - $totalRealisasi;
        $persentase = $totalBudget > 0 ? ($totalRealisasi / $totalBudget) * 100 : 0;
        
        // Count budgets by status
        $overBudget = $budgets->filter(fn($b) => $b->persentase_realisasi >= 100)->count();
        $warning = $budgets->filter(fn($b) => $b->persentase_realisasi >= 90 && $b->persentase_realisasi < 100)->count();
        
        // Get month name
        $monthName = now()->locale('id')->monthName;
        
        return [
            Stat::make('Total Budget ' . $monthName, 'Rp ' . Number::format($totalBudget, locale: 'id'))
                ->description($budgets->count() . ' kategori aktif')
                ->descriptionIcon('heroicon-m-rectangle-stack')
                ->color('primary')
                ->chart([7, 3, 4, 5, 6, 3, 5, 3]),
                
            Stat::make('Realisasi Budget', 'Rp ' . Number::format($totalRealisasi, locale: 'id'))
                ->description(number_format($persentase, 1) . '% dari total budget')
                ->descriptionIcon($persentase >= 90 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($persentase >= 100 ? 'danger' : ($persentase >= 90 ? 'warning' : 'success'))
                ->chart([3, 5, 3, 7, 6, 5, 4, 3]),
                
            Stat::make('Sisa Budget', 'Rp ' . Number::format($sisaBudget, locale: 'id'))
                ->description($sisaBudget < 0 ? 'Over budget!' : 'Tersisa untuk ' . $monthName)
                ->descriptionIcon($sisaBudget < 0 ? 'heroicon-m-exclamation-triangle' : 'heroicon-m-check-circle')
                ->color($sisaBudget < 0 ? 'danger' : 'success')
                ->chart([5, 4, 3, 5, 6, 7, 3, 4]),
                
            Stat::make('Status Budget', $overBudget + $warning . ' Perlu Perhatian')
                ->description($overBudget . ' over budget, ' . $warning . ' mendekati limit')
                ->descriptionIcon('heroicon-m-exclamation-circle')
                ->color($overBudget > 0 ? 'danger' : ($warning > 0 ? 'warning' : 'success'))
                ->chart([2, 3, 2, 4, 3, 2, 1, 2]),
        ];
    }
}
