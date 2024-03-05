<?php

namespace App\Services\Contracts;

interface TwoFactorAuthService
{
    /**
     * Create a secret.
     */
    public function createSecret();

    /**
     * Verify the code.
     */
    public function verify(string $secret, int $code);

    /**
     * Get QR code.
     */
    public function getQRCode(string $label, string $secret);
}
