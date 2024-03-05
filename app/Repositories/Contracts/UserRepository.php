<?php

namespace App\Repositories\Contracts;

interface UserRepository
{
    /**
     * Get all users
     * @return array
     */
    public function all(): array;

    /**
     * Get all users by pagination
     *
     * @param int $perPage
     * @param string $account
     * @param string $name
     * @param array $status
     * @param string $sort
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function allWithPagination(
        ?int $perPage,
        ?string $account,
        ?string $name,
        ?array $status,
        ?string $sort
    );

    /**
     * Find user by id
     *
     * @param string $id
     * @return \App\Models\User
     */
    public function findById(string $id);

    /**
     * Find user by id with roles and permissions
     *
     * @param string $id
     * @return \App\Models\User
     */
    public function findByIdWithRolesAndPermissions(string $id);

    /**
     * Find users by array of IDs
     *
     * @param array $ids
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findByIds(array $ids);

    /**
     * Find user by HRM id with roles and permissions
     *
     * @param int $hrmId
     * @return \App\Models\User
     */
    public function findByHrmId(int $hrmId);

    /**
     * Find user by HRM id with roles and permissions
     *
     * @param int $hrmId
     * @return \App\Models\User
     */
    public function findByHrmIdWithRolesAndPermissions(string $hrmId);

    /**
     * Find user by email
     *
     * @param string $email
     * @return \App\Models\User
     */
    public function findByEmail(string $email);

    /**
     * Find admin without role
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findAdminWithoutRole();

    /**
     * Create user
     *
     * @param array $data
     * @return \App\Models\User
     */
    public function create(array $data);

    /**
     * Insert users
     *
     * @param array|array<array> $data
     * @return bool
     */
    public function insert(array $data): bool;

    /**
     * Update user
     *
     * @param int $id
     * @param array $data
     * @return \App\Models\User
     */
    public function update(int $id, array $data);
}
