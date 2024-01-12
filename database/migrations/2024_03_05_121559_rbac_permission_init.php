<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Permissions to be added.
     */
    private $permissions = [
        'accountManagement.view',
        'accountManagement.create',
        'accountManagement.update',
        'accountManagement.delete',
        'clientManagement.view',
        'clientManagement.create',
        'clientManagement.update',
        'clientManagement.delete',
        'polling.view',
        'polling.create',
        'polling.update',
        'polling.delete',
    ];

    /**
     * Roles to be added.
     */
    private $roles = [
        'admin',
        'salesManager',
        'sales',
        'consultant',
        'customerService',
        'marketing',
    ];

    /**
     * Permissions of roles.
     */
    private $permissionsOfRoles = [
        'admin' => [
            'accountManagement.view',
            'accountManagement.create',
            'accountManagement.update',
            'accountManagement.delete',
            'clientManagement.view',
            'clientManagement.create',
            'clientManagement.update',
            'clientManagement.delete',
            'polling.view',
            'polling.create',
            'polling.update',
            'polling.delete',
        ],
        'salesManager' => [
            'accountManagement.view',
            'clientManagement.view',
            'polling.view',
        ],
        'sales' => [
            'clientManagement.view',
            'polling.view',
        ],
        'consultant' => [
            'accountManagement.view',
            'clientManagement.view',
            'polling.view',
        ],
        'customerService' => [
            'clientManagement.view',
        ],
        'marketing' => [
            'clientManagement.view',
        ],
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $this->initializePermissions();

        $this->initializeRoles();

        $this->assignPermissionsToRoles();
    }

    /**
     * Reverse the migrations.
     * @throws \Exception
     */
    public function down(): void
    {
        throw new \Exception('This migration cannot be reversed.');
    }

    private function initializePermissions(): void
    {
        $permissions = [];

        foreach ($this->permissions as $permission) {
            $permissions[] = [
                'name' => $permission,
                'guard_name' => 'api',
            ];
        }

        Permission::insert($permissions);
    }

    private function initializeRoles(): void
    {
        $roles = [];

        foreach ($this->roles as $role) {
            $roles[] = [
                'name' => $role,
                'guard_name' => 'api',
            ];
        }

        Role::insert($roles);
    }

    private function assignPermissionsToRoles(): void
    {
        $rolePermissions = [];

        foreach ($this->permissionsOfRoles as $role => $permissions) {
            $roleId = Role::findByName($role)->id;

            foreach ($permissions as $permission) {
                $permissionId = Permission::where('name', $permission)->value('id');

                $rolePermissions[] = [
                    'role_id' => $roleId,
                    'permission_id' => $permissionId,
                ];
            }
        }

        DB::table('role_has_permissions')->insert($rolePermissions);
    }
};
