<?php

namespace App\Filament\Resources\Budgets\Schemas;

use App\Models\Budget;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class BudgetInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('kategori_id')
                    ->numeric(),
                TextEntry::make('tahun'),
                TextEntry::make('bulan')
                    ->numeric(),
                TextEntry::make('nominal_budget')
                    ->numeric(),
                TextEntry::make('keterangan')
                    ->placeholder('-')
                    ->columnSpanFull(),
                IconEntry::make('is_active')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Budget $record): bool => $record->trashed()),
            ]);
    }
}
