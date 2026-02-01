<?php

namespace App\Filament\Resources\Budgets\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use App\Models\KategoriTransaksi;

class BudgetForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('kategori_id')
                    ->label('Kategori')
                    ->options(KategoriTransaksi::active()->pluck('nama', 'id'))
                    ->searchable()
                    ->required()
                    ->helperText('Pilih kategori transaksi untuk budget ini'),
                TextInput::make('tahun')
                    ->label('Tahun')
                    ->required()
                    ->numeric()
                    ->default(date('Y'))
                    ->minValue(2000)
                    ->maxValue(2100),
                Select::make('bulan')
                    ->label('Bulan')
                    ->required()
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
                    ->default(date('n')),
                TextInput::make('nominal_budget')
                    ->label('Nominal Budget')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->minValue(0)
                    ->step(0.01)
                    ->mask(\Filament\Support\RawJs::make('$money($input, \'.\', \',\', 2)'))
                    ->stripCharacters(',')
                    ->inputMode('decimal')
                    ->placeholder('5.000.000')
                    ->helperText('Format otomatis dengan pemisah ribuan. Contoh: 5.000.000'),
                Textarea::make('keterangan')
                    ->label('Keterangan')
                    ->columnSpanFull()
                    ->rows(3),
                Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true)
                    ->required(),
            ]);
    }
}
