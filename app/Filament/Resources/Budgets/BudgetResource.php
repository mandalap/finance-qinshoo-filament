<?php

namespace App\Filament\Resources\Budgets;

use App\Filament\Resources\Budgets\Pages\CreateBudget;
use App\Filament\Resources\Budgets\Pages\EditBudget;
use App\Filament\Resources\Budgets\Pages\ListBudgets;
use App\Filament\Resources\Budgets\Pages\ViewBudget;
use App\Filament\Resources\Budgets\Schemas\BudgetForm;
use App\Filament\Resources\Budgets\Schemas\BudgetInfolist;
use App\Filament\Resources\Budgets\Tables\BudgetsTable;
use App\Models\Budget;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BudgetResource extends Resource
{
    protected static ?string $model = Budget::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return BudgetForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return BudgetInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BudgetsTable::configure($table);
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
            'index' => ListBudgets::route('/'),
            'create' => CreateBudget::route('/create'),
            'view' => ViewBudget::route('/{record}'),
            'edit' => EditBudget::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    // Permission menggunakan Spatie Permission
    public static function canViewAny(): bool
    {
        return true; // Allow access but hide navigation for unauthorized users
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->can('view-budget') ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->can('create-budget') ?? false;
    }

    public static function canEdit($record): bool
    {
        return auth()->user()?->can('edit-budget') ?? false;
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->can('delete-budget') ?? false;
    }

    public static function canView($record): bool
    {
        return auth()->user()?->can('view-budget') ?? false;
    }
}
