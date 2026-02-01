<?php

namespace App\Filament\Resources\TransaksiKeuangans\Schemas;

use App\JenisTransaksi;
use App\Models\KategoriTransaksi;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

use Filament\Schemas\Schema;

class TransaksiKeuanganForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Transaksi')
                    ->columns(2)
                    ->schema([
                        DatePicker::make('tanggal_transaksi')
                            ->label('Tanggal Transaksi')
                            ->required()
                            ->default(now())
                            ->native(false)
                            ->displayFormat('d F Y')
                            ->columnSpan(1),
                            
                        TextInput::make('nominal')
                            ->label('Nominal (Rp)')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->minValue(0)
                            ->step(0.01)
                            ->mask(\Filament\Support\RawJs::make('$money($input, \'.\', \',\', 2)'))
                            ->stripCharacters(',')
                            ->inputMode('decimal')
                            ->columnSpan(1),

                        Select::make('jenis')
                            ->label('Jenis Transaksi')
                            ->options(JenisTransaksi::class)
                            ->required()
                            ->native(false)
                            ->live()
                            ->afterStateUpdated(fn ($state, callable $set) => $set('kategori_id', null))
                            ->columnSpan(1),
                            
                        Select::make('kategori_id')
                            ->label('Kategori')
                            ->options(function ($get) {
                                $jenis = $get('jenis');
                                if (!$jenis) {
                                    return [];
                                }
                                return KategoriTransaksi::where('jenis', $jenis)
                                    ->where('is_active', true)
                                    ->pluck('nama', 'id');
                            })
                            ->required()
                            ->native(false)
                            ->searchable()
                            ->columnSpan(1),

                        Textarea::make('deskripsi')
                            ->label('Deskripsi / Keterangan')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
                    
                Section::make('Bukti Transaksi')
                    ->schema([
                        FileUpload::make('bukti_path')
                            ->label('Upload Bukti')
                            ->multiple()
                            ->reorderable()
                            ->appendFiles()
                            ->acceptedFileTypes(['image/*', 'application/pdf'])
                            // Kompresi Gambar: Resize ke max 1024px
                            ->imageResizeMode('contain')
                            ->imageResizeTargetWidth('1024')
                            ->imageResizeTargetHeight('1024')
                            // Batas ukuran file dinaikkan menjadi 50MB
                            ->maxSize(51200) 
                            ->disk('public')
                            ->directory('bukti-transaksi')
                            ->visibility('public')
                            ->downloadable()
                            ->previewable()
                            ->helperText('Upload foto bukti transfer/nota atau file PDF. Gambar akan otomatis dikompres. (Max: 50MB)'),
                    ])
                    ->columnSpanFull(),
                    
                Hidden::make('created_by')
                    ->default(auth()->id()),
            ]);
    }
}
