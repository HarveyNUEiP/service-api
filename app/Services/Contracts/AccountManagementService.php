<?php

namespace App\Services\Contracts;

interface AccountManagementService
{
    public function allUsersWithPagination(
        ?int $perPage = null,
        ?string $account = null,
        ?string $name = null,
        ?array $status = null,
        ?string $sort = null
    );

    public function allUsers(): array;

    /**
     * Get user by id
     *
     * @param int $id
     * @return \App\Models\User
     */
    public function getUserById(int $id);

    /**
     * Get user by HRM id
     *
     * @param int $hrmId
     * @return \App\Models\User
     */
    public function getUserByHrmId(int $hrmId);

    /**
     * Get users by array of IDs
     *
     * @param int $hrmId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserByIds(array $ids);

    /**
     * Get user by id with roles and permissions
     *
     * @param int $id
     * @return \App\Models\User
     */
    public function getUserByIdWithRolesPermissions(int $id);

    /**
     * Get user by HRM id with roles and permissions
     *
     * @param int $hrmId
     * @return \App\Models\User
     */
    public function getUserByHrmIdWithRolesPermissions(int $hrmId);

    /**
     * Get user by email
     *
     * @param string $email
     * @return \App\Models\User
     */
    public function getUserByEmail(string $email);

    /**
     * Create user
     *
     * @param array $data
     * @return \App\Models\User
     */
    public function create(array $data);

    /**
     * Update user
     *
     * @param int $id
     * @param array $data
     * @return \App\Models\User
     */
    public function insert(array $data): bool;

    /**
     * Update user
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data);
}