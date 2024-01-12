<?php

namespace App\Repositories;

use App\Repositories\Contracts\RoleRepository as ContractsRoleRepository;
use Spatie\Permission\Models\Role;

class RoleRepository implements ContractsRoleRepository
{
    public function __construct(
        protected Role $role
    ) {}

    /**
     * Find role by name
     *
     * @param string $name
     * @return \Spatie\Permission\Models\Role
     * @throws \Exception
     */
    public function findByName(string $name)
    {
        try {
            return $this->role
                ->where('name', $name)
                ->firstOrFail();

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Create role
     *
     * @param array $data
     * @return \Spatie\Permission\Models\Role
     * @throws \Exception
     */
    public function create(array $data)
    {
        return Role::create($data);
    }

    /**
     * Delete role
     *
     * @param string $name
     * @return bool
     * @throws \Exception
     */
    public function deleteByName(string $name): bool
    {
        try {
            $role = $this->role->where('name', $name)->firstOrFail();

            return $role->delete();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
