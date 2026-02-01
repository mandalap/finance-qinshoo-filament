<?php

namespace App\Filament\Resources\TransaksiKeuangans\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TransaksiKeuanganInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Transaksi')
                    ->schema([
                        TextEntry::make('nomor_transaksi')
                            ->label('Nomor Transaksi')
                            ->weight('bold')
                            ->copyable(),
                            
                        TextEntry::make('tanggal_transaksi')
                            ->label('Tanggal Transaksi')
                            ->date('d F Y'),
                            
                        TextEntry::make('jenis')
                            ->label('Jenis Transaksi')
                            ->badge(),
                            
                        TextEntry::make('kategori.nama')
                            ->label('Kategori'),
                            
                        TextEntry::make('nominal')
                            ->label('Nominal')
                            ->money('IDR')
                            ->weight('bold')
                            ->size('lg'),
                    ])
                    ->columns(2),
                    
                Section::make('Detail')
                    ->schema([
                        TextEntry::make('deskripsi')
                            ->label('Deskripsi / Keterangan')
                            ->columnSpanFull(),
                    ]),
                    
                Section::make('Bukti Transaksi')
                    ->schema([
                        ImageEntry::make('bukti_path')
                            ->label('')
                            ->disk('public')
                            ->visibility('public')
                            ->columnSpanFull()
                            ->hidden(fn ($record) => !$record->bukti_path),
                            
                        TextEntry::make('bukti_status')
                            ->label('Status Bukti')
                            ->formatStateUsing(fn ($record) => $record->bukti_path ? '✅ Bukti tersedia' : '❌ Tidak ada bukti')
                            ->hidden(fn ($record) => $record->bukti_path),
                    ])
                    ->collapsible(),
                    
                Section::make('Informasi Sistem')
                    ->schema([
                        TextEntry::make('creator.name')
                            ->label('Dibuat Oleh'),
                            
                        TextEntry::make('created_at')
                            ->label('Tanggal Dibuat')
                            ->dateTime('d F Y H:i'),
                            
                        TextEntry::make('updated_at')
                            ->label('Terakhir Diupdate')
                            ->dateTime('d F Y H:i'),
                    ])
                    ->columns(3)
                    ->collapsible()
                    ->collapsed(),
            ]);
    }
}
