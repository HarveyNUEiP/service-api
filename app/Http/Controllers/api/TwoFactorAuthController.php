<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Contracts\AccountManagementService;
use App\Services\Contracts\TwoFactorAuthService;
use App\Services\TwoFactorAuthServiceManager;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TwoFactorAuthController extends Controller
{
    public function __construct(
        protected AccountManagementService $accountManagementService,
        protected TwoFactorAuthService $tfa,
        protected TwoFactorAuthServiceManager $tfaManager
    ) {}

    /**
     * Get secret and QR code image.
     */
    public function getQRcode(Request $request)
    {
        $secret = $this->tfa->createSecret();
        $base64Image = $this->tfa->getQRCode($request->user()->user_no, $secret);

        return [
            'secret' => $secret,
            'base64Image' => $base64Image,
        ];
    }

    /**
     * Bind two factor authentication.
     */
    public function bind(Request $request)
    {
        $request->validate([
            'secret' => 'required|string',
        ]);

        $this->accountManagementService->update(request()->user()->id, [
            'shared_secret' => $request->input('secret'),
            'last_tfa_verification_at' => Carbon::now()->toDateTimeString(),
        ]);

        return response()->json([
            'message' => 'Two factor authentication binding succeed.',
        ], 201);
    }

    /**
     * Two factor authentication verification.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|numeric|integer|digits:6',
        ]);

        $user = $request->user();
        $secret = $user->shared_secret ?? $request->input('secret', '');
        $code = $request->input('code');

        if (!$this->tfaManager->verifyCodeAndUpdateVerificationAt($user, $secret, $code)) {
            return response()->json([
                'message' => 'Two factor authentication verification failed.',
            ], 401);
        }

        return response()->json([
            'message' => 'Two factor authentication verification succeed.',
        ], 200);
    }

    /**
     * Unbind two factor authentication.
     */
    public function unbind(string $id)
    {
        $this->accountManagementService->update($id, [
            'shared_secret' => null,
            'last_tfa_verification_at' => null,
        ]);

        return response()->json([
            'message' => 'Two factor authentication unbinding succeed.',
        ], 202);
    }
}
