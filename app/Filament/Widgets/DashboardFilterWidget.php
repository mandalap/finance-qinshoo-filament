<?php

namespace App\Filament\Widgets;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;

class DashboardFilterWidget extends Widget implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.widgets.dashboard-filter-widget';
    
    protected static ?int $sort = 0; // Tampilkan di paling atas
    
    protected int | string | array $columnSpan = 'full'; // Full width
    
    public ?string $filterType = 'bulan_ini';
    public ?string $startDate = null;
    public ?string $endDate = null;
    public ?int $selectedMonth = null;
    public ?int $selectedYear = null;

    public function mount(): void
    {
        // Set default filter ke bulan ini
        $this->selectedMonth = now()->month;
        $this->selectedYear = now()->year;
        $this->filterType = 'bulan_ini';
        $this->applyFilter();
    }

    protected function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('filterType')
                ->label('Filter Periode')
                ->options([
                    'hari_ini' => 'Hari Ini',
                    'minggu_ini' => 'Minggu Ini',
                    'bulan_ini' => 'Bulan Ini',
                    'tahun_ini' => 'Tahun Ini',
                    'bulan' => 'Bulan Tertentu',
                    'tahun' => 'Tahun Tertentu',
                    'custom' => 'Custom Range',
                ])
                ->default('bulan_ini')
                ->live()
                ->afterStateUpdated(fn () => $this->applyFilter())
                ->columnSpan(1),

            Select::make('selectedMonth')
                ->label('Bulan')
                ->options([
                    1 => 'Januari',
                    2 => 'Februari',
                    3 => 'Maret',
                    4 => 'April',
                    5 => 'Mei',
                    6 => 'Juni',
                    7 => 'Juli',
                    8 => 'Agustus',
                    9 => 'September',
                    10 => 'Oktober',
                    11 => 'November',
                    12 => 'Desember',
                ])
                ->default(now()->month)
                ->visible(fn ($get) => $get('filterType') === 'bulan')
                ->live()
                ->afterStateUpdated(fn () => $this->applyFilter())
                ->columnSpan(1),

            Select::make('selectedYear')
                ->label('Tahun')
                ->options(function () {
                    $years = [];
                    $currentYear = now()->year;
                    for ($i = $currentYear; $i >= $currentYear - 5; $i--) {
                        $years[$i] = $i;
                    }
                    return $years;
                })
                ->default(now()->year)
                ->visible(fn ($get) => in_array($get('filterType'), ['bulan', 'tahun']))
                ->live()
                ->afterStateUpdated(fn () => $this->applyFilter())
                ->columnSpan(1),

            DatePicker::make('startDate')
                ->label('Dari Tanggal')
                ->native(false)
                ->displayFormat('d M Y')
                ->visible(fn ($get) => $get('filterType') === 'custom')
                ->live()
                ->afterStateUpdated(fn () => $this->applyFilter())
                ->columnSpan(1),

            DatePicker::make('endDate')
                ->label('Sampai Tanggal')
                ->native(false)
                ->displayFormat('d M Y')
                ->visible(fn ($get) => $get('filterType') === 'custom')
                ->live()
                ->afterStateUpdated(fn () => $this->applyFilter())
                ->columnSpan(1),
        ]);
    }

    public function applyFilter(): void
    {
        $now = now();

        switch ($this->filterType) {
            case 'hari_ini':
                $this->startDate = $now->format('Y-m-d');
                $this->endDate = $now->format('Y-m-d');
                break;

            case 'minggu_ini':
                $this->startDate = $now->copy()->startOfWeek()->format('Y-m-d');
                $this->endDate = $now->copy()->endOfWeek()->format('Y-m-d');
                break;

            case 'bulan_ini':
                $this->startDate = $now->copy()->startOfMonth()->format('Y-m-d');
                $this->endDate = $now->copy()->endOfMonth()->format('Y-m-d');
                break;

            case 'tahun_ini':
                $this->startDate = $now->copy()->startOfYear()->format('Y-m-d');
                $this->endDate = $now->copy()->endOfYear()->format('Y-m-d');
                break;

            case 'bulan':
                if ($this->selectedMonth && $this->selectedYear) {
                    $date = Carbon::create($this->selectedYear, $this->selectedMonth, 1);
                    $this->startDate = $date->startOfMonth()->format('Y-m-d');
                    $this->endDate = $date->endOfMonth()->format('Y-m-d');
                }
                break;

            case 'tahun':
                if ($this->selectedYear) {
                    $date = Carbon::create($this->selectedYear, 1, 1);
                    $this->startDate = $date->startOfYear()->format('Y-m-d');
                    $this->endDate = $date->endOfYear()->format('Y-m-d');
                }
                break;

            case 'custom':
                // startDate dan endDate sudah di-set dari form
                break;
        }

        // Simpan filter ke session untuk diakses widget
        Session::put('dashboard_filter', [
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
        ]);

        // Refresh widgets
        $this->dispatch('refresh-widgets');
    }
}
