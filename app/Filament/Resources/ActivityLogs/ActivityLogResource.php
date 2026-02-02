<?php

namespace App\Filament\Resources\ActivityLogs;

use App\Filament\Resources\ActivityLogs\Pages\ListActivityLogs;
use App\Filament\Resources\ActivityLogs\Pages\ViewActivityLog;
use App\Filament\Resources\ActivityLogs\Schemas\ActivityLogInfolist;
use App\Filament\Resources\ActivityLogs\Tables\ActivityLogsTable;
use Spatie\Activitylog\Models\Activity;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class ActivityLogResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-clipboard-document-list';
    
    protected static ?string $navigationLabel = 'Activity Log';
    
    protected static ?string $modelLabel = 'Activity Log';
    
    protected static ?string $pluralModelLabel = 'Activity Logs';
    
    protected static \UnitEnum|string|null $navigationGroup = 'System';
    
    protected static ?int $navigationSort = 99;

    protected static ?string $recordTitleAttribute = 'description';

    // Only show navigation for super admin
    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();
        if (!$user) {
            return false;
        }
        
        // Allow if user has super_admin role OR is user ID 1 (first admin)
        return $user->hasRole('super_admin') || $user->id === 1;
    }

    // Block direct access if not super admin
    public static function canViewAny(): bool
    {
        $user = auth()->user();
        if (!$user) {
            return false;
        }
        
        // Allow if user has super_admin role OR is user ID 1 (first admin)
        return $user->hasRole('super_admin') || $user->id === 1;
    }

    public static function infolist(Schema $schema): Schema
    {
        return ActivityLogInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ActivityLogsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListActivityLogs::route('/'),
            'view' => ViewActivityLog::route('/{record}'),
        ];
    }
    
    // Disable create, edit, delete
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
    
    public static function canDeleteAny(): bool
    {
        return false;
    }
}
