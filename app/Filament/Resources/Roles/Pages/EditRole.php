<?php

namespace App\Filament\Resources\Roles\Pages;

use App\Filament\Resources\Roles\RoleResource;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRole extends EditRecord
{
    protected static string $resource = RoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function handleRecordUpdate($record, array $data): \Illuminate\Database\Eloquent\Model
    {
        // Extract permission fields from data
        $permissionFields = [
            'permissions_transaksi',
            'permissions_budget',
            'permissions_pengajuan',
            'permissions_kategori',
            'permissions_user',
            'permissions_laporan',
        ];

        $permissionsData = [];
        foreach ($permissionFields as $field) {
            if (isset($data[$field])) {
                $permissionsData[$field] = $data[$field];
                unset($data[$field]);
            }
        }

        // Update role without permission fields
        $record->update($data);

        // All valid permissions
        $allPermissions = [
            'permissions_transaksi' => [
                'view-transaksi', 'create-transaksi', 'edit-transaksi', 'delete-transaksi'
            ],
            'permissions_budget' => [
                'view-budget', 'create-budget', 'edit-budget', 'delete-budget'
            ],
            'permissions_pengajuan' => [
                'view-pengajuan', 'approve-pengajuan', 'reject-pengajuan'
            ],
            'permissions_kategori' => [
                'view-kategori', 'create-kategori', 'edit-kategori', 'delete-kategori'
            ],
            'permissions_user' => [
                'view-user', 'create-user', 'edit-user', 'delete-user'
            ],
            'permissions_laporan' => [
                'view-laporan-keuangan', 'view-laporan-budget', 'export-laporan'
            ],
        ];

        // Collect all valid permissions
        $validPermissions = [];
        foreach ($allPermissions as $field => $perms) {
            $validPermissions = array_merge($validPermissions, $perms);
        }

        // Remove all permissions that belong to these groups
        foreach ($validPermissions as $perm) {
            if ($record->hasPermissionTo($perm)) {
                $record->revokePermissionTo($perm);
            }
        }

        // Add new permissions
        foreach ($allPermissions as $field => $validPerms) {
            $selected = $permissionsData[$field] ?? [];
            if (is_array($selected)) {
                foreach ($selected as $permission) {
                    if (in_array($permission, $validPerms)) {
                        $record->givePermissionTo($permission);
                    }
                }
            }
        }

        return $record;
    }
}
