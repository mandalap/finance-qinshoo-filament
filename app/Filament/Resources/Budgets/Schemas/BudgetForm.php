<?php

namespace App\Filament\Resources\Budgets\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class BudgetForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('kategori_id')
                    ->required()
                    ->numeric(),
                TextInput::make('tahun')
                    ->required(),
                TextInput::make('bulan')
                    ->required()
                    ->numeric(),
                TextInput::make('nominal_budget')
                    ->required()
                    ->numeric(),
                Textarea::make('keterangan')
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
