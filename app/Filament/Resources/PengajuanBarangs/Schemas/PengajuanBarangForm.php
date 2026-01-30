<?php

namespace App\Filament\Resources\PengajuanBarangs\Schemas;

use App\StatusPengajuan;
use App\TingkatUrgensi;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PengajuanBarangForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nomor_pengajuan')
                    ->required(),
                DatePicker::make('tanggal_pengajuan')
                    ->required(),
                TextInput::make('nama_pengaju')
                    ->required(),
                TextInput::make('divisi')
                    ->required(),
                TextInput::make('jabatan')
                    ->required(),
                TextInput::make('kontak'),
                TextInput::make('nama_barang')
                    ->required(),
                Textarea::make('spesifikasi_barang')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('jumlah')
                    ->required()
                    ->numeric(),
                TextInput::make('satuan')
                    ->required(),
                TextInput::make('estimasi_harga')
                    ->required()
                    ->numeric(),
                Textarea::make('tujuan_pengajuan')
                    ->required()
                    ->columnSpanFull(),
                DatePicker::make('tanggal_dibutuhkan')
                    ->required(),
                Select::make('tingkat_urgensi')
                    ->options(TingkatUrgensi::class)
                    ->default('normal')
                    ->required(),
                Select::make('status')
                    ->options(StatusPengajuan::class)
                    ->default('pending')
                    ->required(),
                Textarea::make('catatan_persetujuan')
                    ->columnSpanFull(),
                TextInput::make('disetujui_oleh')
                    ->numeric(),
                DateTimePicker::make('tanggal_persetujuan'),
            ]);
    }
}
