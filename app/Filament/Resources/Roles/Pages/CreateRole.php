<?php

namespace App\Filament\Resources\Roles\Pages;

use App\Filament\Resources\Roles\RoleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRole extends CreateRecord
{
    protected static string $resource = RoleResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
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

        // Create role without permission fields
        $record = static::getModel()::create($data);

        // Assign permissions
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

        foreach ($allPermissions as $field => $validPermissions) {
            $selected = $permissionsData[$field] ?? [];
            if (is_array($selected)) {
                foreach ($selected as $permission) {
                    if (in_array($permission, $validPermissions)) {
                        $record->givePermissionTo($permission);
                    }
                }
            }
        }

        return $record;
    }
}
