<?php

namespace App\Filament\Resources\KategoriTransaksis\Pages;

use App\Filament\Resources\KategoriTransaksis\KategoriTransaksiResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditKategoriTransaksi extends EditRecord
{
    protected static string $resource = KategoriTransaksiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
