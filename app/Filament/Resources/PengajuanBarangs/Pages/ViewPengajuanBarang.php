<?php

namespace App\Filament\Resources\PengajuanBarangs\Pages;

use App\Filament\Resources\PengajuanBarangs\PengajuanBarangResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPengajuanBarang extends ViewRecord
{
    protected static string $resource = PengajuanBarangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
