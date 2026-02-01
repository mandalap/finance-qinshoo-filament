<?php

namespace App\Filament\Resources\ActivityLogs\Schemas;

use Filament\Infolists;
use Filament\Schemas\Schema;

class ActivityLogInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Infolists\Components\Section::make('Activity Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('id')
                            ->label('Activity ID'),
                            
                        Infolists\Components\TextEntry::make('log_name')
                            ->label('Log Type')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'transaksi_keuangan' => 'success',
                                'pengajuan_barang' => 'warning',
                                'kategori_transaksi' => 'info',
                                default => 'gray',
                            }),
                            
                        Infolists\Components\TextEntry::make('description')
                            ->label('Action')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'created' => 'success',
                                'updated' => 'warning',
                                'deleted' => 'danger',
                                default => 'gray',
                            }),
                            
                        Infolists\Components\TextEntry::make('subject_type')
                            ->label('Model Type')
                            ->formatStateUsing(fn (string $state): string => class_basename($state)),
                            
                        Infolists\Components\TextEntry::make('subject_id')
                            ->label('Record ID'),
                            
                        Infolists\Components\TextEntry::make('causer.name')
                            ->label('Performed By')
                            ->default('System'),
                            
                        Infolists\Components\TextEntry::make('causer.email')
                            ->label('User Email')
                            ->default('-'),
                            
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Date & Time')
                            ->dateTime('d/m/Y H:i:s'),
                    ])
                    ->columns(2),
                    
                Infolists\Components\Section::make('Changes Detail')
                    ->schema([
                        Infolists\Components\TextEntry::make('properties')
                            ->label('Properties')
                            ->formatStateUsing(function ($state) {
                                if (empty($state)) {
                                    return 'No changes recorded';
                                }
                                
                                $html = '<div class="space-y-2">';
                                
                                // Old values
                                if (isset($state['old']) && !empty($state['old'])) {
                                    $html .= '<div class="p-3 bg-red-50 dark:bg-red-900/20 rounded-lg">';
                                    $html .= '<strong class="text-red-700 dark:text-red-400">Before:</strong><br>';
                                    foreach ($state['old'] as $key => $value) {
                                        $html .= '<span class="text-sm">' . htmlspecialchars($key) . ': ' . htmlspecialchars(is_array($value) ? json_encode($value) : $value) . '</span><br>';
                                    }
                                    $html .= '</div>';
                                }
                                
                                // New values
                                if (isset($state['attributes']) && !empty($state['attributes'])) {
                                    $html .= '<div class="p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">';
                                    $html .= '<strong class="text-green-700 dark:text-green-400">After:</strong><br>';
                                    foreach ($state['attributes'] as $key => $value) {
                                        $html .= '<span class="text-sm">' . htmlspecialchars($key) . ': ' . htmlspecialchars(is_array($value) ? json_encode($value) : $value) . '</span><br>';
                                    }
                                    $html .= '</div>';
                                }
                                
                                $html .= '</div>';
                                
                                return new \Illuminate\Support\HtmlString($html);
                            })
                            ->columnSpanFull(),
                    ]),
                    
                Infolists\Components\Section::make('Additional Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('batch_uuid')
                            ->label('Batch UUID')
                            ->default('-'),
                            
                        Infolists\Components\TextEntry::make('updated_at')
                            ->label('Last Updated')
                            ->dateTime('d/m/Y H:i:s')
                            ->default('-'),
                    ])
                    ->columns(2)
                    ->collapsed(),
            ]);
    }
}
