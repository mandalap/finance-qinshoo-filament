<?php

namespace App\Filament\Resources\ActivityLogs\Tables;

use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Resources\ActivityLogs\ActivityLogResource;

class ActivityLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->with('causer'))
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('log_name')
                    ->label('Log Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'transaksi_keuangan' => 'success',
                        'pengajuan_barang' => 'warning',
                        'kategori_transaksi' => 'info',
                        default => 'gray',
                    })
                    ->sortable()
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('description')
                    ->label('Action')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'created' => 'success',
                        'updated' => 'warning',
                        'deleted' => 'danger',
                        default => 'gray',
                    })
                    ->sortable()
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('subject_type')
                    ->label('Model')
                    ->formatStateUsing(fn (string $state): string => class_basename($state))
                    ->sortable()
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('subject_id')
                    ->label('Record ID')
                    ->sortable()
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('causer.name')
                    ->label('User')
                    ->default('System')
                    ->sortable()
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('log_name')
                    ->label('Log Type')
                    ->options([
                        'transaksi_keuangan' => 'Transaksi Keuangan',
                        'pengajuan_barang' => 'Pengajuan Barang',
                        'kategori_transaksi' => 'Kategori Transaksi',
                    ]),
                    
                Tables\Filters\SelectFilter::make('description')
                    ->label('Action')
                    ->options([
                        'created' => 'Created',
                        'updated' => 'Updated',
                        'deleted' => 'Deleted',
                    ]),
                    
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('created_from')
                            ->label('From Date'),
                        \Filament\Forms\Components\DatePicker::make('created_until')
                            ->label('Until Date'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['created_from'], fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
                            ->when($data['created_until'], fn ($q, $date) => $q->whereDate('created_at', '<=', $date));
                    }),
            ])
            ->recordUrl(fn ($record) => ActivityLogResource::getUrl('view', ['record' => $record->id]))
            ->bulkActions([
                // No bulk actions for activity log
            ])
            ->defaultSort('created_at', 'desc')
            ->poll('30s'); // Auto refresh every 30 seconds
    }
}
