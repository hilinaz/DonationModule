<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'system.full-control',
            'campaign.manage',
            'donor.manage',
            'donation.validate',
            'reporting.view',
            'reporting.compliance',
            'communication.manage',
            'campaign.messaging',
            'portal.access',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $rolePermissionMap = [
            'Admin' => $permissions,
            'Fundraising Manager' => [
                'campaign.manage',
                'donor.manage',
            ],
            'Finance' => [
                'donation.validate',
                'reporting.view',
            ],
            'Marketing' => [
                'communication.manage',
                'campaign.messaging',
            ],
            'Donor' => [
                'portal.access',
            ],
            'Auditor' => [
                'reporting.view',
                'reporting.compliance',
            ],
        ];

        foreach ($rolePermissionMap as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->syncPermissions($rolePermissions);
        }
    }
}
