<?php

namespace App\Services;

use App\Repositories\Contracts\PermissionRepository;
use App\Repositories\Contracts\RoleRepository;
use App\Repositories\Contracts\UserRepository;
use App\Services\Contracts\RoleBasedAccessControlService as ContractsRoleBasedAccessControlService;

class RoleBasedAccessControlService implements ContractsRoleBasedAccessControlService
{
    public function __construct(
        protected UserRepository $userRepository,
        protected RoleRepository $roleRepository,
        protected PermissionRepository $permissionRepository
    ) {}

    /**
     * Create Permission
     *
     * @param array $data
     * @return \Spatie\Permission\Models\Permission
     * @throws \Exception
     */
    public function createPermission(array $data)
    {
        return $this->permissionRepository->create($data);
    }

    /**
     * Delete Permission
     *
     * @param string $name
     * @return bool
     * @throws \Exception
     */
    public function deletePermission(string $name): bool
    {
        return $this->permissionRepository->deleteByName($name);
    }

    /**
     * Create Role
     *
     * @param array $data
     * @return Role
     * @throws \Exception
     */
    public function createRole(array $data)
    {
        return $this->roleRepository->create($data);
    }

    /**
     * Delete Role
     *
     * @param string $name
     * @return bool
     * @throws \Exception
     */
    public function deleteRole(string $name): bool
    {
        return $this->roleRepository->deleteByName($name);
    }

    /**
     * Assign Permissions to Role
     *
     * @param string $roleName
     * @param string|int|array|\Spatie\Permission\Models\Permission|\Illuminate\Database\Eloquent\Collection $permissions
     * @return \Spatie\Permission\Models\Role
     * @throws \Exception
     */
    public function assignPermissionsToRole(string $roleName, $permissions)
    {
        try {
            $role = $this->roleRepository->findByName($roleName);

            return $role->givePermissionTo($permissions);

        } catch (\Exception $e) {
            throw new \Exception(
                message: "Failed to assign permissions to role",
                code: 400
            );
        }
    }

    /**
     * Assign Roles to User
     *
     * @param int $id  User ID
     * @param string|int|array|\Spatie\Permission\Models\Role|\Illuminate\Database\Eloquent\Collection $roles
     * @return \App\Models\User
     * @throws \Exception
     */
    public function assignRoleToUser(int $id, $roles)
    {
        try {
            $user = $this->userRepository->findById($id);

            return $user->assignRole($roles);

        } catch (\Exception $e) {
            throw new \Exception(
                message: "Failed to assign role to user",
                code: 400
            );
        }
    }

    /**
     * Synchronize User's Role Assignment
     *
     * @param int $id  User ID
     * @param string|int|array|\Spatie\Permission\Models\Role|\Illuminate\Database\Eloquent\Collection $roles
     * @return \App\Models\User
     * @throws \Exception
     */
    public function syncUserRoleAssignment(int $id, $roles)
    {
        try {
            $user = $this->userRepository->findById($id);

            return $user->syncRoles($roles);

        } catch (\Exception $e) {
            throw new \Exception(
                message: "Failed to sync user's role assignment",
                code: 400
            );
        }
    }

    /**
     * Synchronize User's Permission Assignment
     *
     * @param int $id  User ID
     * @param string|int|array|\Spatie\Permission\Models\Permission|\Illuminate\Database\Eloquent\Collection $permissions
     * @return \App\Models\User
     */
    public function syncUserPermissionAssignment(int $id, $permissions)
    {
        try {
            $user = $this->userRepository->findById($id);

            return $user->syncPermissions($permissions);

        } catch (\Exception $e) {
            throw new \Exception(
                message: "Failed to sync user's permission assignment",
                code: 400
            );
        }
    }

    /**
     * Update User's Role and Permissions
     *
     * @param int $id
     * @param string $roleName
     * @param array $permissionNames
     */
    public function updateUserRoleAndPermission(int $id, string $roleName, array $permissionNames)
    {
        $role = $this->roleRepository->findByName($roleName);

        $this->syncUserRoleAssignment($id, $role);

        $rolePermissionNames = $role->permissions->pluck('name')->toArray();

        $this->syncUserPermissionAssignment($id, array_diff($permissionNames, $rolePermissionNames));
    }

    /**
     * Initialize Admin User Role
     *
     * @return void
     */
    public function initializeAdminUserRole()
    {
        $admins = $this->userRepository->findAdminWithoutRole();

        foreach ($admins as $admin) {
            $admin->assignRole('admin');
        }
    }
}
