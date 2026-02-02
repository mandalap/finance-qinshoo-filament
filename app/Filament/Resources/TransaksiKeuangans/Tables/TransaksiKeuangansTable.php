<?php

namespace App\Filament\Resources\TransaksiKeuangans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class TransaksiKeuangansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            // PERFORMANCE FIX: Eager load relationships untuk avoid N+1 query
            ->modifyQueryUsing(fn ($query) => $query->with(['kategori', 'creator']))
            ->columns([
                TextColumn::make('nomor_transaksi')
                    ->label('No. Transaksi')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold'),
                    
                TextColumn::make('tanggal_transaksi')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                    
                TextColumn::make('jenis')
                    ->label('Jenis')
                    ->badge()
                    ->color(fn (\App\JenisTransaksi $state): string => $state->getColor())
                    ->formatStateUsing(fn (\App\JenisTransaksi $state): string => $state->getLabel())
                    ->sortable(),
                    
                TextColumn::make('kategori.nama')
                    ->label('Kategori')
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('nominal')
                    ->label('Nominal')
                    ->money('IDR')
                    ->sortable()
                    ->summarize([
                        \Filament\Tables\Columns\Summarizers\Sum::make()
                            ->money('IDR')
                            ->label('Total'),
                    ]),
                    
                TextColumn::make('deskripsi')
                    ->label('Deskripsi')
                    ->limit(40)
                    ->searchable()
                    ->toggleable(),
                    
                IconColumn::make('bukti_path')
                    ->label('Bukti')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('gray'),
                    
                TextColumn::make('creator.name')
                    ->label('Dibuat Oleh')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                TextColumn::make('deleted_at')
                    ->label('Dihapus')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->placeholder('—')
                    ->badge()
                    ->color('danger'),
            ])
            ->filters([
                SelectFilter::make('jenis')
                    ->label('Jenis Transaksi')
                    ->options([
                        'pemasukan' => 'Pemasukan',
                        'pengeluaran' => 'Pengeluaran',
                    ]),
                    
                SelectFilter::make('kategori_id')
                    ->label('Kategori')
                    ->relationship('kategori', 'nama')
                    ->searchable()
                    ->preload(),
                    
                Filter::make('tanggal_transaksi')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('dari')
                            ->label('Dari Tanggal')
                            ->native(false),
                        \Filament\Forms\Components\DatePicker::make('sampai')
                            ->label('Sampai Tanggal')
                            ->native(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['dari'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal_transaksi', '>=', $date),
                            )
                            ->when(
                                $data['sampai'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal_transaksi', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['dari'] ?? null) {
                            $indicators[] = 'Dari: ' . \Carbon\Carbon::parse($data['dari'])->format('d M Y');
                        }
                        if ($data['sampai'] ?? null) {
                            $indicators[] = 'Sampai: ' . \Carbon\Carbon::parse($data['sampai'])->format('d M Y');
                        }
                        return $indicators;
                    }),
                    
                Filter::make('bulan_ini')
                    ->label('Bulan Ini')
                    ->query(fn (Builder $query): Builder => $query->whereMonth('tanggal_transaksi', now()->month)
                        ->whereYear('tanggal_transaksi', now()->year))
                    ->toggle(),
                    
                Filter::make('tahun_ini')
                    ->label('Tahun Ini')
                    ->query(fn (Builder $query): Builder => $query->whereYear('tanggal_transaksi', now()->year))
                    ->toggle(),
                    
                // Filter untuk menampilkan data yang sudah dihapus (soft delete)
                Filter::make('trashed')
                    ->label('Tampilkan Data Terhapus')
                    ->query(fn (Builder $query): Builder => $query->onlyTrashed())
                    ->toggle(),
            ])
            ->recordActions([
                ViewAction::make(),
                Action::make('cetak')
                    ->label('Cetak Bukti')
                    ->icon('heroicon-o-printer')
                    ->color('secondary')
                    ->url(fn ($record) => route('transaksi.print', $record->id))
                    ->openUrlInNewTab()
                    ->hidden(fn ($record) => $record->trashed()),
                EditAction::make()
                    ->hidden(fn ($record) => $record->trashed()),
                    
                // Action untuk restore data yang sudah dihapus
                Action::make('restore')
                    ->label('Pulihkan')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(fn ($record) => $record->restore())
                    ->visible(fn ($record) => $record->trashed())
                    ->successNotificationTitle('Transaksi berhasil dipulihkan'),
                    
                // Action untuk hapus permanen
                Action::make('forceDelete')
                    ->label('Hapus Permanen')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Hapus Permanen Transaksi')
                    ->modalDescription('Apakah Anda yakin ingin menghapus transaksi ini secara permanen? Tindakan ini tidak dapat dibatalkan.')
                    ->modalSubmitActionLabel('Ya, Hapus Permanen')
                    ->action(fn ($record) => $record->forceDelete())
                    ->visible(fn ($record) => $record->trashed())
                    ->successNotificationTitle('Transaksi berhasil dihapus permanen'),
            ])
            ->toolbarActions([
                ExportAction::make()
                    ->exports([
                        ExcelExport::make('table')
                            ->fromTable()
                            ->only([
                                'nomor_transaksi',
                                'tanggal_transaksi',
                                'jenis',
                                'kategori.nama',
                                'deskripsi',
                                'creator.name',
                                'created_at',
                            ])
                            ->withColumns([
                                \pxlrbt\FilamentExcel\Columns\Column::make('nominal')
                                    ->heading('Nominal')
                                    ->formatStateUsing(fn ($state) => $state),
                            ])
                            ->withFilename(fn () => 'transaksi-keuangan-' . date('Y-m-d'))
                            ->withWriterType(\Maatwebsite\Excel\Excel::XLSX),
                    ]),
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    
                    // Bulk action untuk restore
                    \Filament\Actions\RestoreBulkAction::make()
                        ->label('Pulihkan Terpilih')
                        ->successNotificationTitle('Transaksi berhasil dipulihkan'),
                        
                    // Bulk action untuk force delete
                    \Filament\Actions\ForceDeleteBulkAction::make()
                        ->label('Hapus Permanen Terpilih')
                        ->modalHeading('Hapus Permanen Transaksi Terpilih')
                        ->modalDescription('Apakah Anda yakin ingin menghapus transaksi terpilih secara permanen? Tindakan ini tidak dapat dibatalkan.')
                        ->successNotificationTitle('Transaksi berhasil dihapus permanen'),
                ]),
            ])
            ->defaultSort('tanggal_transaksi', 'desc');
    }
}
