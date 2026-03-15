<?php

namespace App\Filament\Resources\Roles;

use App\Filament\Resources\Roles\Pages\CreateRole;
use App\Filament\Resources\Roles\Pages\EditRole;
use App\Filament\Resources\Roles\Pages\ListRoles;
use App\Filament\Resources\Roles\Schemas\RoleForm;
use App\Filament\Resources\Roles\Tables\RolesTable;
use Spatie\Permission\Models\Role;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-shield-check';

    protected static ?string $navigationLabel = 'Manajemen Role';

    protected static ?string $modelLabel = 'Role';

    protected static ?string $pluralModelLabel = 'Roles';

    protected static \UnitEnum|string|null $navigationGroup = 'Pengaturan';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return RoleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RolesTable::configure($table);
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
            'index' => ListRoles::route('/'),
            'create' => CreateRole::route('/create'),
            'edit' => EditRole::route('/{record}/edit'),
        ];
    }

    // Hanya Super Admin yang bisa akses
    public static function canViewAny(): bool
    {
        return true; // Allow access but hide navigation for unauthorized users
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->hasRole('super-admin') ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->hasRole('super-admin') ?? false;
    }

    public static function canEdit($record): bool
    {
        // Tidak bisa edit super-admin role
        if ($record->name === 'super-admin') {
            return false;
        }
        return auth()->user()?->hasRole('super-admin') ?? false;
    }

    public static function canDelete($record): bool
    {
        // Tidak bisa hapus super-admin role
        if ($record->name === 'super-admin') {
            return false;
        }
        return auth()->user()?->hasRole('super-admin') ?? false;
    }
}
