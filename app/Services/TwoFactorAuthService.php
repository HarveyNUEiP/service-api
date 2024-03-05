<?php

namespace App\Services;

use App\Services\Contracts\TwoFactorAuthService as ContractsTwoFactorAuthService;
use RobThree\Auth\TwoFactorAuth;

class TwoFactorAuthService implements ContractsTwoFactorAuthService
{
    protected $tfa;

    public function __construct()
    {
        $this->tfa = new TwoFactorAuth(issuer: 'NUEIP Service');
    }

    /**
     * Create a secret.
     */
    public function createSecret(int $bits = 160)
    {
        return $this->tfa->createSecret($bits);
    }

    /**
     * Verify the code.
     */
    public function verify(string $secret, int $code)
    {
        return $this->tfa->verifyCode($secret, $code);
    }

    /**
     * Get QR code.
     */
    public function getQRCode(string $label, string $secret)
    {
        return $this->tfa->getQRCodeImageAsDataUri($label, $secret);
    }
}
