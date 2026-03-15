<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Schemas\Components\Section;
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
                            ->color(fn ($state) => match($state) {
                                'super-admin' => 'danger',
                                'approver' => 'warning',
                                'viewer' => 'info',
                                default => 'gray',
                            })
                            ->formatStateUsing(fn ($state) => match($state) {
                                'super-admin' => 'Super Admin',
                                'approver' => 'Approver',
                                'viewer' => 'Viewer',
                                default => $state,
                            }),
                            
                        TextEntry::make('created_at')
                            ->label('Dibuat')
                            ->dateTime('d F Y, H:i'),
                    ])
                    ->columns(2),
            ]);
    }
}
