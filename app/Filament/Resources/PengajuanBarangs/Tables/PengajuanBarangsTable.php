<?php

namespace App\Filament\Resources\PengajuanBarangs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;

class PengajuanBarangsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nomor_pengajuan')
                    ->label('No. Pengajuan')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold'),
                    
                TextColumn::make('tanggal_pengajuan')
                    ->label('Tgl. Pengajuan')
                    ->date('d M Y')
                    ->sortable(),
                    
                TextColumn::make('nama_pengaju')
                    ->label('Pengaju')
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('detailBarang')
                    ->label('Barang')
                    ->formatStateUsing(function ($record) {
                        $count = $record->detailBarang->count();
                        if ($count == 1) {
                            return $record->detailBarang->first()->nama_barang;
                        }
                        return $count . ' item barang';
                    })
                    ->limit(30),
                    
                TextColumn::make('total_estimasi')
                    ->label('Total Estimasi')
                    ->formatStateUsing(fn ($record) => 'Rp ' . number_format($record->detailBarang->sum('estimasi_harga'), 0, ',', '.'))
                    ->sortable(query: function ($query, $direction) {
                        return $query->withSum('detailBarang', 'estimasi_harga')
                            ->orderBy('detail_barang_sum_estimasi_harga', $direction);
                    }),
                    
                TextColumn::make('tingkat_urgensi')
                    ->label('Urgensi')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state->getLabel())
                    ->color(fn ($state) => $state->getColor()),
                    
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state->getLabel())
                    ->color(fn ($state) => $state->getColor())
                    ->sortable(),
                    
                TextColumn::make('approver.name')
                    ->label('Disetujui Oleh')
                    ->placeholder('-')
                    ->toggleable(),
                    
                TextColumn::make('tanggal_persetujuan')
                    ->label('Tgl. Persetujuan')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-')
                    ->toggleable(),
                    
                IconColumn::make('bukti_transaksi')
                    ->label('Bukti Transaksi')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('gray')
                    ->visible(fn ($record) => $record->status->value === 'approved'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Menunggu Persetujuan',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                        'cancelled' => 'Dibatalkan',
                    ]),
                    
                SelectFilter::make('tingkat_urgensi')
                    ->label('Tingkat Urgensi')
                    ->options([
                        'normal' => 'Normal',
                        'mendesak' => 'Mendesak',
                    ]),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('Lihat'),
                    
                Action::make('approve')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->form([
                        Textarea::make('catatan_persetujuan')
                            ->label('Catatan Persetujuan')
                            ->placeholder('Opsional: tambahkan catatan persetujuan'),
                    ])
                    ->action(function ($record, array $data) {
                        $record->update([
                            'status' => 'approved',
                            'catatan_persetujuan' => $data['catatan_persetujuan'] ?? null,
                            'disetujui_oleh' => auth()->id(),
                            'tanggal_persetujuan' => now(),
                        ]);
                    })
                    ->visible(fn ($record) => 
                        $record->status->value === 'pending' && 
                        (auth()->user()?->hasRole('approver') || auth()->user()?->hasRole('super-admin'))
                    ),
                    
                Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->form([
                        Textarea::make('catatan_persetujuan')
                            ->label('Alasan Penolakan')
                            ->required()
                            ->placeholder('Wajib: jelaskan alasan penolakan'),
                    ])
                    ->action(function ($record, array $data) {
                        $record->update([
                            'status' => 'rejected',
                            'catatan_persetujuan' => $data['catatan_persetujuan'],
                            'disetujui_oleh' => auth()->id(),
                            'tanggal_persetujuan' => now(),
                        ]);
                    })
                    ->visible(fn ($record) => 
                        $record->status->value === 'pending' && 
                        (auth()->user()?->hasRole('approver') || auth()->user()?->hasRole('super-admin'))
                    ),
                    
                Action::make('cancel')
                    ->label('Batalkan')
                    ->icon('heroicon-o-minus-circle')
                    ->color('gray')
                    ->requiresConfirmation()
                    ->form([
                        Textarea::make('catatan_persetujuan')
                            ->label('Alasan Pembatalan')
                            ->required()
                            ->placeholder('Wajib: jelaskan alasan pembatalan'),
                    ])
                    ->action(function ($record, array $data) {
                        $record->update([
                            'status' => 'cancelled',
                            'catatan_persetujuan' => $data['catatan_persetujuan'],
                            'disetujui_oleh' => auth()->id(),
                            'tanggal_persetujuan' => now(),
                        ]);
                    })
                    ->visible(fn ($record) => 
                        in_array($record->status->value, ['pending', 'approved']) && 
                        (auth()->user()?->hasRole('approver') || auth()->user()?->hasRole('super-admin'))
                    ),
                    
                Action::make('upload_bukti')
                    ->label('Upload Bukti Transaksi')
                    ->icon('heroicon-o-document-arrow-up')
                    ->color('info')
                    ->form([
                        FileUpload::make('bukti_transaksi')
                            ->label('Bukti Transaksi')
                            ->directory('bukti-transaksi')
                            ->acceptedFileTypes(['image/*', 'application/pdf'])
                            ->maxSize(5120) // 5MB
                            ->required()
                            ->helperText('Upload bukti transaksi (foto/PDF, maks 5MB)'),
                    ])
                    ->action(function ($record, array $data) {
                        $record->update([
                            'bukti_transaksi' => $data['bukti_transaksi'],
                        ]);
                    })
                    ->visible(fn ($record) => 
                        $record->status->value === 'approved' && 
                        (auth()->user()?->hasRole('approver') || auth()->user()?->hasRole('super-admin'))
                    ),
            ])
            ->defaultSort('tanggal_pengajuan', 'desc')
            ->bulkActions([
                // Disable bulk delete untuk keamanan data
            ]);
    }
}
