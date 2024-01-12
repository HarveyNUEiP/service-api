<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Services\AccountManagementServiceManager;
use App\Services\Contracts\AccountManagementService;
use App\Services\Contracts\RoleBasedAccessControlService;
use Illuminate\Http\Request;

class AccountManagementController extends Controller
{
    public function __construct(
        protected AccountManagementService $accountManagementService,
        protected RoleBasedAccessControlService $roleBasedAccessControlService,
        protected AccountManagementServiceManager $accountManagementServiceManager
    ) {}

    /**
     * Display All User
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->validate([
            'filter.perpage' => 'integer|min:1|max:100',
            'filter.account' => 'string',
            'filter.name' => 'string',
            'filter.status' => 'array',
            'filter.status.*' => 'in:enabled,disabled',
            'sort' => 'string',
        ]);

        if ($request->has('filter.status')) {
            $request->merge([
                'status' => array_map(function ($status) {
                    return $status === 'enabled' ? 1 : 0;
                }, $request->input('filter.status')),
            ]);
        }

        $data = $this->accountManagementService->allUsersWithPagination(
            perPage: $request->input('filter.perpage'),
            account: $request->input('filter.account'),
            name: $request->input('filter.name'),
            status: $request->input('status'),
            sort: $request->input('sort')
        );

        return response()->json(new UserCollection($data), 200);
    }

    /**
     * Display the specified user information.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(string $id)
    {
        return response()->json(
            UserResource::make(
                $this->accountManagementService
                    ->GetUserByIdWithRolesPermissions($id)
            ), 200
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'role' => 'required|string|exists:roles,name',
            'permission' => 'required|array|exists:permissions,name',
            'is_valid' => 'required|boolean',
        ]);

        $updateStatus = $this->accountManagementServiceManager->updateUserByUserId(
            $id,
            $request->input('role'),
            $request->input('permission'),
            $request->input('is_valid')
        );

        return response()->json([
            'message' => $updateStatus ? 'Update success' : 'Update failed',
        ], $updateStatus ? 200 : 400);
    }
}
