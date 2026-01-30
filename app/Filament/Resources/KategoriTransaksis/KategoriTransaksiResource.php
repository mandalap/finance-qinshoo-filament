<?php

namespace App\Filament\Resources\KategoriTransaksis;

use App\Filament\Resources\KategoriTransaksis\Pages\CreateKategoriTransaksi;
use App\Filament\Resources\KategoriTransaksis\Pages\EditKategoriTransaksi;
use App\Filament\Resources\KategoriTransaksis\Pages\ListKategoriTransaksis;
use App\Filament\Resources\KategoriTransaksis\Schemas\KategoriTransaksiForm;
use App\Filament\Resources\KategoriTransaksis\Tables\KategoriTransaksisTable;
use App\Models\KategoriTransaksi;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class KategoriTransaksiResource extends Resource
{
    protected static ?string $model = KategoriTransaksi::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-tag';
    
    protected static ?string $navigationLabel = 'Kategori Transaksi';
    
    protected static ?string $modelLabel = 'Kategori Transaksi';
    
    protected static ?string $pluralModelLabel = 'Kategori Transaksi';
    
    protected static \UnitEnum|string|null $navigationGroup = 'Keuangan';
    
    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'nama';

    public static function form(Schema $schema): Schema
    {
        return KategoriTransaksiForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KategoriTransaksisTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListKategoriTransaksis::route('/'),
            'create' => CreateKategoriTransaksi::route('/create'),
            'edit' => EditKategoriTransaksi::route('/{record}/edit'),
        ];
    }
}
