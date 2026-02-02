<?php

namespace App\Filament\Resources\TransaksiKeuangans\Pages;

use App\Filament\Resources\TransaksiKeuangans\TransaksiKeuanganResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditTransaksiKeuangan extends EditRecord
{
    protected static string $resource = TransaksiKeuanganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make()
                ->after(function () {
                    // Clear cache dan refresh widgets setelah delete
                    cache()->flush();
                    $this->dispatch('refresh-widgets');
                }),
        ];
    }
    
    protected function afterSave(): void
    {
        // Clear cache untuk widget stats
        cache()->flush();
        
        // Dispatch event untuk refresh widgets di dashboard
        $this->dispatch('refresh-widgets');
    }
}
