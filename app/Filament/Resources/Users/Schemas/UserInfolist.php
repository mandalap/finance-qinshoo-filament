<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi User')
                    ->schema([
                        TextEntry::make('name')
                            ->label('Nama Lengkap'),
                            
                        TextEntry::make('email')
                            ->label('Email')
                            ->copyable(),
                            
                        TextEntry::make('roles.name')
                            ->label('Role')
                            ->badge()
                            ->color(fn ($state) => $state === 'super-admin' ? 'danger' : 'success')
                            ->formatStateUsing(fn ($state) => $state === 'super-admin' ? 'Super Admin' : 'Approver'),
                            
                        TextEntry::make('created_at')
                            ->label('Dibuat')
                            ->dateTime('d F Y, H:i'),
                    ])
                    ->columns(2),
            ]);
    }
}
