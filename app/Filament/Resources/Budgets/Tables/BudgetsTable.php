<?php

namespace App\Filament\Resources\Budgets\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class BudgetsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            // PERFORMANCE FIX: Eager load kategori relationship
            ->modifyQueryUsing(fn ($query) => $query->with('kategori'))
            ->columns([
                TextColumn::make('kategori.nama')
                    ->label('Kategori')
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('tahun')
                    ->label('Tahun')
                    ->sortable(),
                    
                TextColumn::make('bulan')
                    ->label('Bulan')
                    ->formatStateUsing(fn ($state) => \Carbon\Carbon::create()->month($state)->format('F'))
                    ->sortable(),
                    
                TextColumn::make('nominal_budget')
                    ->label('Budget')
                    ->money('IDR')
                    ->sortable(),
                    
                TextColumn::make('realisasi')
                    ->label('Realisasi')
                    ->money('IDR')
                    ->sortable(false),
                    
                TextColumn::make('sisa_budget')
                    ->label('Sisa')
                    ->money('IDR')
                    ->sortable(false)
                    ->color(fn ($state) => $state < 0 ? 'danger' : 'success'),
                    
                TextColumn::make('persentase_realisasi')
                    ->label('% Terpakai')
                    ->sortable(false)
                    ->formatStateUsing(fn ($state) => number_format($state, 1) . '%')
                    ->color(fn ($state) => match(true) {
                        $state >= 100 => 'danger',
                        $state >= 80 => 'warning',
                        $state >= 50 => 'info',
                        default => 'success',
                    }),
                    
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                    
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
