<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;

class TwoFactorAuthServiceManager
{
    public function __construct(
        protected TwoFactorAuthService $tfa,
        protected AccountManagementService $accountManagementService
    ) {}

    /**
     * Verify code and update verification time if User has binded TFA.
     *
     * @param User $user
     * @param string $secret
     * @param int $code
     * @return bool
     */
    public function verifyCodeAndUpdateVerificationAt(User $user, string $secret, int $code)
    {
        if (!$this->tfa->verify($secret, $code)) {
            return false;
        }

        if (isset($user->shared_secret)) {
            $this->accountManagementService->update($user->id, [
                'last_tfa_verification_at' => Carbon::now()->toDateTimeString(),
            ]);
        }

        return true;
    }
}