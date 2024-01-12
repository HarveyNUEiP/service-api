<?php

namespace App\Services;

use App\Services\Contracts\AccountManagementService;
use App\Services\Contracts\RoleBasedAccessControlService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AccountManagementServiceManager
{
    public function __construct(
        protected AccountManagementService $accountManagementService,
        protected RoleBasedAccessControlService $roleBasedAccessControlService,
    ) {}

    /**
     * Update user role and permission by id
     *
     * @param int $id
     * @param string $role
     * @param array $permissionNames
     * @param bool $is_valid
     * @return bool
     */
    public function updateUserByUserId(int $id, string $role, array $permissionNames, bool $is_valid)
    {
        try {
            DB::beginTransaction();

            $this->roleBasedAccessControlService
                ->updateUserRoleAndPermission($id, $role, $permissionNames);

            $this->accountManagementService
                ->update($id, ['is_valid' => $is_valid]);

            DB::commit();

            return true;

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error($e->getMessage());

            return false;
        }
    }
}