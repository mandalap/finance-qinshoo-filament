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
                Section::make('Informasi Transaksi')
                    ->schema([
                        DatePicker::make('tanggal_transaksi')
                            ->label('Tanggal Transaksi')
                            ->required()
                            ->default(now())
                            ->native(false)
                            ->displayFormat('d F Y'),
                            
                        Select::make('jenis')
                            ->label('Jenis Transaksi')
                            ->options(JenisTransaksi::class)
                            ->required()
                            ->native(false)
                            ->live()
                            ->afterStateUpdated(fn ($state, callable $set) => $set('kategori_id', null)),
                            
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
                            ->searchable(),
                    ])
                    ->columns(3),
                    
                Section::make('Detail Transaksi')
                    ->schema([
                        TextInput::make('nominal')
                            ->label('Nominal (Rp)')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->minValue(0)
                            ->step(0.01),
                            
                        Textarea::make('deskripsi')
                            ->label('Deskripsi / Keterangan')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),
                    
                Section::make('Bukti Transaksi')
                    ->schema([
                        FileUpload::make('bukti_path')
                            ->label('Upload Bukti')
                            ->image()
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                null,
                                '16:9',
                                '4:3',
                                '1:1',
                            ])
                            // Image Optimization
                            ->resize(1920, 1920)
                            ->imageResizeMode('contain')
                            ->imageResizeTargetWidth('1920')
                            ->imageResizeTargetHeight('1920')
                            // File Settings
                            ->acceptedFileTypes(['image/*', 'application/pdf'])
                            ->maxSize(5120) // 5MB
                            ->directory('bukti-transaksi')
                            ->visibility('private')
                            ->downloadable()
                            ->openable()
                            ->previewable()
                            ->helperText('Upload foto bukti transfer/nota atau file PDF. Gambar akan otomatis diresize (Max: 5MB)'),
                    ]),
                    
                // Hidden field untuk created_by (auto-fill dengan user yang login)
                Hidden::make('created_by')
                    ->default(auth()->id()),
            ]);
    }
}
