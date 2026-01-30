<?php

namespace App\Filament\Resources\PengajuanBarangs\RelationManagers;

use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DetailBarangRelationManager extends RelationManager
{
    protected static string $relationship = 'detailBarang';
    
    protected static ?string $title = 'Detail Barang';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama_barang')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nama_barang')
            ->columns([
                TextColumn::make('nama_barang')
                    ->label('Nama Barang')
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('spesifikasi_barang')
                    ->label('Spesifikasi')
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->spesifikasi_barang),
                    
                TextColumn::make('jumlah')
                    ->label('Jml')
                    ->suffix(fn ($record) => ' ' . $record->satuan),
                    
                TextColumn::make('estimasi_harga')
                    ->label('Harga')
                    ->money('IDR', locale: 'id'),
                    
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),
                    
                TextColumn::make('catatan')
                    ->label('Catatan Approval')
                    ->limit(20),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(fn ($record) => $record->update(['status' => 'approved']))
                    ->visible(fn ($record) => $record->status !== 'approved'),
                    
                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->form([
                        Textarea::make('catatan')
                            ->label('Alasan Penolakan')
                            ->required(),
                    ])
                    ->action(fn ($record, array $data) => $record->update([
                        'status' => 'rejected',
                        'catatan' => $data['catatan']
                    ]))
                    ->visible(fn ($record) => $record->status !== 'rejected'),
                    
                Action::make('pending')
                    ->label('Pending')
                    ->icon('heroicon-o-clock')
                    ->color('warning')
                    ->action(fn ($record) => $record->update(['status' => 'pending']))
                    ->visible(fn ($record) => $record->status !== 'pending'),
            ])
            ->toolbarActions([
                //
            ]);
    }
}
