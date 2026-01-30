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
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Hidden;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

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
                    ->visible(fn ($record) => $record && $record->status && $record->status->value === 'approved'),
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

                Action::make('cetak')
                    ->label('Cetak Bukti')
                    ->icon('heroicon-o-printer')
                    ->color('secondary')
                    ->url(fn ($record) => route('pengajuan.print', $record->uuid))
                    ->openUrlInNewTab(),
                    
                Action::make('approve')
                    ->label('Proses Persetujuan')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->modalWidth('4xl')
                    ->fillForm(function ($record) {
                        return [
                            'catatan_persetujuan' => $record->catatan_persetujuan,
                            'items' => $record->detailBarang->map(function ($item) {
                                return [
                                    'item_id' => $item->id,
                                    'nama_barang' => $item->nama_barang,
                                    'spesifikasi_barang' => $item->spesifikasi_barang,
                                    'jumlah_info' => $item->jumlah . ' ' . $item->satuan,
                                    'estimasi_harga' => $item->estimasi_harga,
                                    'status' => $item->status ?? 'pending',
                                    'catatan' => $item->catatan,
                                ];
                            })->toArray(),
                        ];
                    })
                    ->form([
                        Repeater::make('items')
                            ->label('Detail Barang')
                            ->schema([
                                Hidden::make('item_id'),
                                TextInput::make('nama_barang')
                                    ->label('Barang')
                                    ->disabled()
                                    ->columnSpan(3),
                                TextInput::make('spesifikasi_barang')
                                    ->label('Spesifikasi')
                                    ->disabled()
                                    ->columnSpan(2),
                                TextInput::make('jumlah_info')
                                    ->label('Jumlah')
                                    ->disabled()
                                    ->columnSpan(1),
                                TextInput::make('estimasi_harga')
                                    ->label('Harga/Est')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->disabled()
                                    ->columnSpan(1),
                                Select::make('status')
                                    ->label('Keputusan')
                                    ->options([
                                        'approved' => 'Setujui',
                                        'rejected' => 'Tolak',
                                        'pending' => 'Tunda',
                                    ])
                                    ->required()
                                    ->selectablePlaceholder(false)
                                    ->columnSpan(1),
                                TextInput::make('catatan')
                                    ->label('Catatan Item')
                                    ->placeholder('Opsional')
                                    ->columnSpan(1),
                            ])
                            ->columns(3)
                            ->addable(false)
                            ->deletable(false)
                            ->reorderable(false)
                            ->columnSpanFull(),
                            
                        Textarea::make('catatan_persetujuan')
                            ->label('Catatan Umum Pengajuan')
                            ->placeholder('Catatan umum untuk seluruh pengajuan'),
                    ])
                    ->action(function ($record, array $data) {
                        // Update items status
                        $hasApproved = false;
                        $hasPending = false;
                        
                        if (isset($data['items'])) {
                            foreach ($data['items'] as $itemData) {
                                \App\Models\DetailBarangPengajuan::where('id', $itemData['item_id'])
                                    ->update([
                                        'status' => $itemData['status'],
                                        'catatan' => $itemData['catatan'],
                                    ]);
                                
                                if ($itemData['status'] === 'approved') $hasApproved = true;
                                if ($itemData['status'] === 'pending') $hasPending = true;
                            }
                        }
                        
                        // Determine parent status based on items
                        // Priority: Approved > Pending > Rejected
                        if ($hasApproved) {
                            $newStatus = 'approved';
                        } elseif ($hasPending) {
                            $newStatus = 'pending';
                            // If we revert to pending, maybe clear approval info? 
                            // For now let's keep it simple: if waiting for items, main status is pending.
                        } else {
                            // If no approved and no pending, implies all rejected
                            $newStatus = 'rejected';
                        }
                        
                        // Update parent status
                        $record->update([
                            'status' => $newStatus,
                            'catatan_persetujuan' => $data['catatan_persetujuan'] ?? null,
                            'disetujui_oleh' => auth()->id(),
                            'tanggal_persetujuan' => now(),
                        ]);
                    })
                    ->visible(fn ($record) => 
                        $record && $record->status && in_array($record->status->value, ['pending', 'approved']) && 
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
                        $record && $record->status && $record->status->value === 'pending' && 
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
                        $record && $record->status && in_array($record->status->value, ['pending', 'approved']) && 
                        (auth()->user()?->hasRole('approver') || auth()->user()?->hasRole('super-admin'))
                    ),
                    
                Action::make('upload_bukti')
                    ->label('Upload Bukti Transaksi')
                    ->icon('heroicon-o-document-arrow-up')
                    ->color('info')
                    ->form([
                        FileUpload::make('bukti_transaksi')
                            ->label('Bukti Transaksi')
                            ->disk('public')
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
                        $record && $record->status && $record->status->value === 'approved' && 
                        (auth()->user()?->hasRole('approver') || auth()->user()?->hasRole('super-admin'))
                    ),
            ])
            ->defaultSort('tanggal_pengajuan', 'desc')
            ->bulkActions([
                // Disable bulk delete untuk keamanan data
            ]);
    }
}
