<?php

namespace App\Services;

use App\Repositories\Contracts\UserRepository;
use App\Services\Contracts\AccountManagementService as ContractsAccountManagementService;

class AccountManagementService implements ContractsAccountManagementService
{
    public function __construct(
        protected UserRepository $userRepository
    ) {}

    /**
     * List all users by pagination
     *
     * @param ?int $perPage
     * @param ?string $account
     * @param ?string $name
     * @param ?array $status
     * @param ?string $sort
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function allUsersWithPagination(
        ?int $perPage = null,
        ?string $account = null,
        ?string $name = null,
        ?array $status= null,
        ?string $sort = null
    ) {
        return $this->userRepository->allWithPagination(
            $perPage,
            $account,
            $name,
            $status,
            $sort
        );
    }

    /**
     * List all users
     *
     * @return array
     */
    public function allUsers(): array
    {
        return $this->userRepository->all();
    }

    /**
     * Get user by id
     *
     * @param int $id
     * @return \App\Models\User
     */
    public function getUserById(int $id)
    {
        return $this->userRepository->findById($id);
    }


    /**
     * Get user by HRM id
     *
     * @param int $hrmId
     * @return \App\Models\User
     */
    public function getUserByHrmId(int $hrmId)
    {
        return $this->userRepository->findByHrmId($hrmId);
    }

    /**
     * Get users by array of IDs
     *
     * @param array $ids
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserByIds(array $ids)
    {
        return $this->userRepository->findByIds($ids);
    }

    /**
     * Get user by id
     *
     * @param int $id
     * @return \App\Models\User
     */
    public function getUserByIdWithRolesPermissions(int $id)
    {
        return $this->userRepository->findByIdWithRolesAndPermissions($id);
    }

    /**
     * Get user by HRM id with roles and permissions
     *
     * @param int $hrmId
     * @return \App\Models\User
     */
    public function getUserByHrmIdWithRolesPermissions(int $hrmId)
    {
        return $this->userRepository->findByHrmIdWithRolesAndPermissions($hrmId);
    }

    /**
     * Get user by email
     *
     * @param string $email
     * @return \App\Models\User
     */
    public function getUserByEmail(string $email)
    {
        return $this->userRepository->findByEmail($email);
    }

    /**
     * Create user
     *
     * @param array $data
     */
    public function create(array $data)
    {
        return $this->userRepository->create($data);
    }

    /**
     * Insert users
     *
     * @param array|array<array> $data
     */
    public function insert(array $data): bool
    {
        return $this->userRepository->insert($data);
    }

    /**
     * Update user
     *
     * @param int $id
     * @param array $data
     */
    public function update(int $id, array $data)
    {
        return $this->userRepository->update($id, $data);
    }
}
