<?php

namespace App\Services\Contracts;

interface RoleBasedAccessControlService
{
    /**
     * Create Permission
     *
     * @param array $data
     * @return \Spatie\Permission\Models\Permission
     * @throws \Exception
     */
    public function createPermission(array $data);

    /**
     * Delete Permission
     *
     * @param string $name
     * @return bool
     * @throws \Exception
     */
    public function deletePermission(string $name): bool;

    /**
     * Create Role
     *
     * @param array $data
     * @return \Spatie\Permission\Models\Role
     * @throws \Exception
     */
    public function createRole(array $data);

    /**
     * Delete Role
     *
     * @param string $name
     * @return bool
     * @throws \Exception
     */
    public function deleteRole(string $name);

    /**
     * Assign Permissions to Role
     *
     * @param string $roleName
     * @param string|int|array|\Spatie\Permission\Models\Permission|\Illuminate\Database\Eloquent\Collection $permissions
     */
    public function assignPermissionsToRole(string $roleName, $permissions);

    /**
     * Assign Roles to User
     *
     * @param int $id
     * @param string|int|array|\Spatie\Permission\Models\Role|\Illuminate\Database\Eloquent\Collection $roles
     */
    public function assignRoleToUser(int $id, $roles);

    /**
     * Assign Permissions to User
     *
     * @param int $id
     * @param string|int|array|\Spatie\Permission\Models\Permission|\Illuminate\Database\Eloquent\Collection $permissions
     */
    public function syncUserRoleAssignment(int $id, $roles);

    /**
     * Assign Permissions to User
     *
     * @param int $id
     * @param string|int|array|\Spatie\Permission\Models\Permission|\Illuminate\Database\Eloquent\Collection $permissions
     */
    public function syncUserPermissionAssignment(int $id, $permissions);

    /**
     * Update User's Role and Permission
     *
     * @param int $id
     * @param string $roleName
     * @param array $permissionNames
     */
    public function updateUserRoleAndPermission(int $id, string $roleName, array $permissionNames);

    /**
     * Initialize admin User Role
     *
     * @return void
     */
    public function initializeAdminUserRole();
}