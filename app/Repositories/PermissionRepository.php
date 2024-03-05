<?php

namespace App\Repositories;

use Spatie\Permission\Models\Permission;
use App\Repositories\Contracts\PermissionRepository as ContractsPermissionRepository;

class PermissionRepository implements ContractsPermissionRepository
{
    public function __construct(
        protected Permission $permission
    ) {}

    /**
     * Find permission by name
     *
     * @param string $name
     * @return \Spatie\Permission\Models\Permission
     * @throws \Exception
     */
    public function findByName(string $name)
    {
        try {
            return $this->permission
                ->where('name', $name)
                ->firstOrFail();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Create permission
     *
     * @param array $data
     * @return \Spatie\Permission\Models\Permission
     * @throws \Exception
     */
    public function create(array $data)
    {
        return Permission::create($data);
    }

    /**
     * Delete permission
     *
     * @param string $name
     * @return bool
     * @throws \Exception
     */
    public function deleteByName(string $name): bool
    {
        try {
            $permission = $this->permission->where('name', $name)->firstOrFail();

            return $permission->delete();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}