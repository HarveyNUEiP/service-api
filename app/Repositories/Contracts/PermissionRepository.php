<?php

namespace App\Repositories\Contracts;

interface PermissionRepository
{
    /**
     * Find Permission by name
     *
     * @param string $name
     * @return \Spatie\Permission\Models\Permission
     */
    public function findByName(string $name);

    /**
     * Create Permission
     *
     * @param array $data
     * @return \Spatie\Permission\Models\Permission
     */
    public function create(array $data);

    /**
     * Delete Permission
     *
     * @param string $name
     * @return bool
     */
    public function deleteByName(string $name): bool;
}