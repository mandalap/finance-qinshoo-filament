<?php

namespace App\Filament\Resources\Budgets\Schemas;

use App\Models\Budget;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BudgetInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Budget')
                    ->schema([
                        TextEntry::make('kategori.nama')
                            ->label('Kategori')
                            ->badge()
                            ->color('info'),
                        TextEntry::make('tahun')
                            ->label('Tahun'),
                        TextEntry::make('bulan')
                            ->label('Bulan')
                            ->formatStateUsing(fn ($state) => [
                                1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
                                4 => 'April', 5 => 'Mei', 6 => 'Juni',
                                7 => 'Juli', 8 => 'Agustus', 9 => 'September',
                                10 => 'Oktober', 11 => 'November', 12 => 'Desember',
                            ][$state] ?? $state),
                        IconEntry::make('is_active')
                            ->label('Status Aktif')
                            ->boolean(),
                    ])
                    ->columns(2),
                    
                Section::make('Detail Budget')
                    ->schema([
                        TextEntry::make('nominal_budget')
                            ->label('Nominal Budget')
                            ->money('IDR')
                            ->size('lg')
                            ->weight('bold')
                            ->color('primary'),
                        TextEntry::make('realisasi')
                            ->label('Realisasi')
                            ->money('IDR')
                            ->size('lg')
                            ->weight('bold')
                            ->color('warning'),
                        TextEntry::make('sisa_budget')
                            ->label('Sisa Budget')
                            ->money('IDR')
                            ->size('lg')
                            ->weight('bold')
                            ->color(fn ($state) => $state < 0 ? 'danger' : 'success'),
                        TextEntry::make('persentase_realisasi')
                            ->label('Persentase Terpakai')
                            ->suffix('%')
                            ->formatStateUsing(fn ($state) => number_format($state, 2))
                            ->badge()
                            ->color(fn ($state) => match(true) {
                                $state >= 100 => 'danger',
                                $state >= 90 => 'warning',
                                $state >= 75 => 'info',
                                default => 'success',
                            }),
                        TextEntry::make('keterangan')
                            ->label('Keterangan')
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                    
                Section::make('Informasi Sistem')
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Dibuat Pada')
                            ->dateTime('d M Y H:i')
                            ->placeholder('-'),
                        TextEntry::make('updated_at')
                            ->label('Diperbarui Pada')
                            ->dateTime('d M Y H:i')
                            ->placeholder('-'),
                        TextEntry::make('deleted_at')
                            ->label('Dihapus Pada')
                            ->dateTime('d M Y H:i')
                            ->visible(fn (Budget $record): bool => $record->trashed()),
                    ])
                    ->columns(2)
                    ->collapsed(),
            ]);
    }
}
