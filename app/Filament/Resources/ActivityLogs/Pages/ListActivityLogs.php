<?php

namespace App\Filament\Resources\ActivityLogs\Pages;

use App\Filament\Resources\ActivityLogs\ActivityLogResource;
use Filament\Resources\Pages\ListRecords;

class ListActivityLogs extends ListRecords
{
    protected static string $resource = ActivityLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No create action for activity log
        ];
    }
    
    public function getTitle(): string
    {
        return 'Activity Log';
    }
    
    public function getHeading(): string
    {
        return 'Activity Log';
    }
    
    protected function getHeaderWidgets(): array
    {
        return [
            // Could add stats widget here
        ];
    }
}
