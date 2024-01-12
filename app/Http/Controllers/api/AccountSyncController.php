<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Services\HrmSyncServiceManager;

class AccountSyncController extends Controller
{
    public function __construct(
        protected HrmSyncServiceManager $hrmSyncServiceManager
    ) {}

    /**
     * Manually sync account from HRM
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $this->hrmSyncServiceManager->syncAccount();

        return response()->json([
            'message' => 'Successfully synchronized!'
        ], 200);
    }
}
