<?php

namespace App\Filament\Resources\Roles\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class RolesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Role')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color(fn (Model $record): string => match($record->name) {
                        'super-admin' => 'danger',
                        'approver' => 'warning',
                        'viewer' => 'info',
                        default => 'gray',
                    }),

                TextColumn::make('users_count')
                    ->label('Jumlah User')
                    ->counts('users')
                    ->sortable(),

                TextColumn::make('permissions_count')
                    ->label('Jumlah Permission')
                    ->counts('permissions')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Diupdate')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('name')
            ->searchable();
    }
}
