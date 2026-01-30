<?php

namespace App\Filament\Resources\PengajuanBarangs;

use App\Filament\Resources\PengajuanBarangs\Pages\CreatePengajuanBarang;
use App\Filament\Resources\PengajuanBarangs\Pages\EditPengajuanBarang;
use App\Filament\Resources\PengajuanBarangs\Pages\ListPengajuanBarangs;
use App\Filament\Resources\PengajuanBarangs\Pages\ViewPengajuanBarang;
use App\Filament\Resources\PengajuanBarangs\Schemas\PengajuanBarangForm;
use App\Filament\Resources\PengajuanBarangs\Schemas\PengajuanBarangInfolist;
use App\Filament\Resources\PengajuanBarangs\Tables\PengajuanBarangsTable;
use App\Models\PengajuanBarang;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PengajuanBarangResource extends Resource
{
    protected static ?string $model = PengajuanBarang::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-clipboard-document-list';
    
    protected static ?string $navigationLabel = 'Pengajuan Barang';
    
    protected static ?string $modelLabel = 'Pengajuan Barang';
    
    protected static ?string $pluralModelLabel = 'Pengajuan Barang';
    
    protected static \UnitEnum|string|null $navigationGroup = 'Operasional Yayasan';
    
    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'nomor_pengajuan';

    public static function form(Schema $schema): Schema
    {
        return PengajuanBarangForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PengajuanBarangInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PengajuanBarangsTable::configure($table);
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
            'index' => ListPengajuanBarangs::route('/'),
            'view' => ViewPengajuanBarang::route('/{record}'),
        ];
    }
    
    // Disable create dan edit karena pengajuan hanya dari public form
    public static function canCreate(): bool
    {
        return false;
    }
    
    public static function canEdit($record): bool
    {
        return false;
    }
    
    public static function canDelete($record): bool
    {
        return false;
    }
    
    // Hanya Approver dan Super Admin yang bisa melihat
    public static function canViewAny(): bool
    {
        return auth()->user()?->hasAnyRole(['approver', 'super-admin']) ?? false;
    }
    
    public static function canView($record): bool
    {
        return auth()->user()?->hasAnyRole(['approver', 'super-admin']) ?? false;
    }
}
