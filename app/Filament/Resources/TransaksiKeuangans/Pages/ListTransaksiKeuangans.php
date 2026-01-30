<?php

namespace App\Filament\Resources\TransaksiKeuangans\Pages;

use App\Filament\Resources\TransaksiKeuangans\TransaksiKeuanganResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTransaksiKeuangans extends ListRecords
{
    protected static string $resource = TransaksiKeuanganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
    
    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\KeuanganStatsWidget::class,
        ];
    }
}
