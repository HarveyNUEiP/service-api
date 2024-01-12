<?php

namespace App\Repositories\Contracts;

interface RoleRepository
{
    /**
     * Find role by name
     *
     * @param string $name
     * @return \Spatie\Permission\Models\Role
     */
    public function findByName(string $name);

    /**
     * Create role
     *
     * @param array $data
     * @return \Spatie\Permission\Models\Role
     */
    public function create(array $data);

    /**
     * Delete role
     *
     * @param string $name
     * @return bool
     */
    public function deleteByName(string $name): bool;

}