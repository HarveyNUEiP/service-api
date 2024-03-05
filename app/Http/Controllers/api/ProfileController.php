<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Services\Contracts\AccountManagementService;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function __construct(
        protected AccountManagementService $accountManagementService
    ) {}

    public function index()
    {
        return UserResource::make(
            $this->accountManagementService
                ->getUserByHrmIdWithRolesPermissions(Auth::id())
        );
    }
}
