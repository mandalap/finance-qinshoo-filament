<?php

namespace App\Filament\Resources\TransaksiKeuangans;

use App\Filament\Resources\TransaksiKeuangans\Pages\CreateTransaksiKeuangan;
use App\Filament\Resources\TransaksiKeuangans\Pages\EditTransaksiKeuangan;
use App\Filament\Resources\TransaksiKeuangans\Pages\ListTransaksiKeuangans;
use App\Filament\Resources\TransaksiKeuangans\Pages\ViewTransaksiKeuangan;
use App\Filament\Resources\TransaksiKeuangans\Schemas\TransaksiKeuanganForm;
use App\Filament\Resources\TransaksiKeuangans\Schemas\TransaksiKeuanganInfolist;
use App\Filament\Resources\TransaksiKeuangans\Tables\TransaksiKeuangansTable;
use App\Models\TransaksiKeuangan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TransaksiKeuanganResource extends Resource
{
    protected static ?string $model = TransaksiKeuangan::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-banknotes';
    
    protected static ?string $navigationLabel = 'Transaksi Keuangan';
    
    protected static ?string $modelLabel = 'Transaksi Keuangan';
    
    protected static ?string $pluralModelLabel = 'Transaksi Keuangan';
    
    protected static \UnitEnum|string|null $navigationGroup = 'Keuangan';
    
    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'nomor_transaksi';

    public static function form(Schema $schema): Schema
    {
        return TransaksiKeuanganForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TransaksiKeuanganInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TransaksiKeuangansTable::configure($table);
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
            'index' => ListTransaksiKeuangans::route('/'),
            'create' => CreateTransaksiKeuangan::route('/create'),
            'view' => ViewTransaksiKeuangan::route('/{record}'),
            'edit' => EditTransaksiKeuangan::route('/{record}/edit'),
        ];
    }

    // Permission menggunakan Spatie Permission
    public static function canViewAny(): bool
    {
        return true; // Allow access but hide navigation for unauthorized users
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->can('view-transaksi') ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->can('create-transaksi') ?? false;
    }

    public static function canEdit($record): bool
    {
        return auth()->user()?->can('edit-transaksi') ?? false;
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->can('delete-transaksi') ?? false;
    }

    public static function canView($record): bool
    {
        return auth()->user()?->can('view-transaksi') ?? false;
    }
}
