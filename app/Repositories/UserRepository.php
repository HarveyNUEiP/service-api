<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UserRepository as ContractsUserRepository;

class UserRepository implements ContractsUserRepository
{
    public function __construct(
        protected User $user
    ) {}

    /**
     * Get all users
     *
     * @return array
     */
    public function all(): array
    {
        return $this->user->all()->toArray();
    }

    /**
     * Get all users with roles
     *
     * @param int $perPage
     * @param string $account   Considered as `user_no`
     * @param string $name
     * @param array $status
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function allWithPagination(
        ?int $perPage,
        ?string $account,
        ?string $name,
        ?array $status,
        ?string $sort
    ) {
        $query = $this->user
            ->with('roles')
            ->when($account, function ($query) use ($account) {
                return $query->where('user_no', 'like', '%' . $account . '%');
            })
            ->when($name, function ($query) use ($name) {
                return $query->where('chinese_name', 'like', '%' . $name . '%');
            })
            ->when(!is_null($status), function ($query) use ($status) {
                return $query->whereIn('is_valid', $status);
            });

        if ($sort) {
            $query->sort($sort);
        }

        return $query->paginate($perPage);
    }

    /**
     * Find user by id
     *
     * @param string $id
     * @return \App\Models\User
     */
    public function findById(string $id): User
    {
        return $this->user
            ->where('id', $id)
            ->first();
    }

    /**
     * Find users by array of IDs
     *
     * @param array $ids
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findByIds(array $ids)
    {
        return $this->user
            ->whereIn('id', $ids)
            ->get();
    }

    /**
     * Find user by HRM id
     *
     * @param int $hrmId
     * @return \App\Models\User
     */
    public function findByHrmId(int $hrmId): User
    {
        return $this->user
            ->where('hrm_id', $hrmId)
            ->first();
    }

    /**
     * Find user by id with roles and permissions
     *
     * @param string $id
     * @return \App\Models\User
     */
    public function findByIdWithRolesAndPermissions(string $id)
    {
        return $this->user
            ->with(['roles.permissions', 'permissions'])
            ->where('id', $id)
            ->first();
    }

    /**
     * Find user by HRM id with roles and permissions
     *
     * @param string $hrmId
     * @return \App\Models\User
     */
    public function findByHrmIdWithRolesAndPermissions(string $hrmId)
    {
        return $this->user
            ->with(['roles.permissions', 'permissions'])
            ->where('hrm_id', $hrmId)
            ->first();
    }

    /**
     * Find user by email
     *
     * @param string $email
     * @return \App\Models\User
     */
    public function findByEmail(string $email)
    {
        return $this->user
            ->where('email', 'like', '%' . $email . '%')
            ->first();
    }

    /**
     * Get admin User without any role
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findAdminWithoutRole()
    {
        return $this->user
            ->where('user_no', 'admin')
            ->whereDoesntHave('roles')
            ->get();
    }

    /**
     * Create user
     *
     * @param array $data
     * @return \App\Models\User
     */
    public function create(array $data): User
    {
        return $this->user->create($data);
    }

    /**
     * Insert user
     *
     * @param array|array<array> $data
     * @return bool
     */
    public function insert(array $data): bool
    {
        return $this->user->insert($data);
    }

    /**
     * Update user
     *
     * @param int $id
     * @param array $attributes
     * @return \App\Models\User
     * @throws \Exception
     */
    public function update(int $id, array $attributes)
    {
        try {
            $user = $this->user->where('id', $id)->firstOrFail();

            return $user->update($attributes);

        } catch (\Exception $e) {
            throw $e;
        }
    }
}
