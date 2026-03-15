<?php

namespace App\Filament\Resources\Roles\Schemas;

use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\CheckboxList;
use Filament\Schemas\Schema;

class RoleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Role')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->regex('/^[a-z0-9-]+$/')
                    ->validationAttribute('nama role')
                    ->helperText('Gunakan huruf kecil, angka, dan tanda strip (-). Contoh: viewer, finance-manager'),

                Section::make('Permission Transaksi Keuangan')
                    ->description('Atur akses untuk menu Transaksi Keuangan')
                    ->schema([
                        CheckboxList::make('permissions_transaksi')
                            ->label('Permissions')
                            ->options([
                                'view-transaksi' => 'Lihat Transaksi',
                                'create-transaksi' => 'Buat Transaksi',
                                'edit-transaksi' => 'Edit Transaksi',
                                'delete-transaksi' => 'Hapus Transaksi',
                            ])
                            ->bulkToggleable()
                            ->searchable()
                            ->columns(2)
                            ->afterStateHydrated(function ($component, $state, $record) {
                                if ($record && $record->exists) {
                                    $permissions = $record->permissions->pluck('name')->toArray();
                                    $transaksiPerms = ['view-transaksi', 'create-transaksi', 'edit-transaksi', 'delete-transaksi'];
                                    $component->state(array_values(array_intersect($permissions, $transaksiPerms)));
                                }
                            }),
                    ])
                    ->columns(1),

                Section::make('Permission Budget')
                    ->description('Atur akses untuk menu Budget')
                    ->schema([
                        CheckboxList::make('permissions_budget')
                            ->label('Permissions')
                            ->options([
                                'view-budget' => 'Lihat Budget',
                                'create-budget' => 'Buat Budget',
                                'edit-budget' => 'Edit Budget',
                                'delete-budget' => 'Hapus Budget',
                            ])
                            ->bulkToggleable()
                            ->columns(2)
                            ->afterStateHydrated(function ($component, $state, $record) {
                                if ($record && $record->exists) {
                                    $permissions = $record->permissions->pluck('name')->toArray();
                                    $budgetPerms = ['view-budget', 'create-budget', 'edit-budget', 'delete-budget'];
                                    $component->state(array_values(array_intersect($permissions, $budgetPerms)));
                                }
                            }),
                    ])
                    ->columns(1),

                Section::make('Permission Pengajuan Barang')
                    ->description('Atur akses untuk menu Pengajuan Barang')
                    ->schema([
                        CheckboxList::make('permissions_pengajuan')
                            ->label('Permissions')
                            ->options([
                                'view-pengajuan' => 'Lihat Pengajuan',
                                'approve-pengajuan' => 'Approve Pengajuan',
                                'reject-pengajuan' => 'Reject Pengajuan',
                            ])
                            ->bulkToggleable()
                            ->columns(2)
                            ->afterStateHydrated(function ($component, $state, $record) {
                                if ($record && $record->exists) {
                                    $permissions = $record->permissions->pluck('name')->toArray();
                                    $pengajuanPerms = ['view-pengajuan', 'approve-pengajuan', 'reject-pengajuan'];
                                    $component->state(array_values(array_intersect($permissions, $pengajuanPerms)));
                                }
                            }),
                    ])
                    ->columns(1),

                Section::make('Permission Kategori Transaksi')
                    ->description('Atur akses untuk menu Kategori Transaksi')
                    ->schema([
                        CheckboxList::make('permissions_kategori')
                            ->label('Permissions')
                            ->options([
                                'view-kategori' => 'Lihat Kategori',
                                'create-kategori' => 'Buat Kategori',
                                'edit-kategori' => 'Edit Kategori',
                                'delete-kategori' => 'Hapus Kategori',
                            ])
                            ->bulkToggleable()
                            ->columns(2)
                            ->afterStateHydrated(function ($component, $state, $record) {
                                if ($record && $record->exists) {
                                    $permissions = $record->permissions->pluck('name')->toArray();
                                    $kategoriPerms = ['view-kategori', 'create-kategori', 'edit-kategori', 'delete-kategori'];
                                    $component->state(array_values(array_intersect($permissions, $kategoriPerms)));
                                }
                            }),
                    ])
                    ->columns(1),

                Section::make('Permission User Management')
                    ->description('Atur akses untuk menu User Management')
                    ->schema([
                        CheckboxList::make('permissions_user')
                            ->label('Permissions')
                            ->options([
                                'view-user' => 'Lihat User',
                                'create-user' => 'Buat User',
                                'edit-user' => 'Edit User',
                                'delete-user' => 'Hapus User',
                            ])
                            ->bulkToggleable()
                            ->columns(2)
                            ->afterStateHydrated(function ($component, $state, $record) {
                                if ($record && $record->exists) {
                                    $permissions = $record->permissions->pluck('name')->toArray();
                                    $userPerms = ['view-user', 'create-user', 'edit-user', 'delete-user'];
                                    $component->state(array_values(array_intersect($permissions, $userPerms)));
                                }
                            }),
                    ])
                    ->columns(1),

                Section::make('Permission Laporan')
                    ->description('Atur akses untuk menu Laporan')
                    ->schema([
                        CheckboxList::make('permissions_laporan')
                            ->label('Permissions')
                            ->options([
                                'view-laporan-keuangan' => 'Lihat Laporan Keuangan',
                                'view-laporan-budget' => 'Lihat Laporan Budget',
                                'export-laporan' => 'Export Laporan',
                            ])
                            ->bulkToggleable()
                            ->columns(2)
                            ->afterStateHydrated(function ($component, $state, $record) {
                                if ($record && $record->exists) {
                                    $permissions = $record->permissions->pluck('name')->toArray();
                                    $laporanPerms = ['view-laporan-keuangan', 'view-laporan-budget', 'export-laporan'];
                                    $component->state(array_values(array_intersect($permissions, $laporanPerms)));
                                }
                            }),
                    ])
                    ->columns(1),
            ]);
    }
}
