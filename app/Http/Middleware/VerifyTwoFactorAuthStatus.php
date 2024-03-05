<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VerifyTwoFactorAuthStatus
{
    public const VALIDATE_DURATION = 7;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        $tfaEnabled = isset($user->shared_secret);

        if (!$tfaEnabled || $user->last_tfa_verification_at < Carbon::now()->subDays(self::VALIDATE_DURATION)) {
            return response()->json([
                'message' => 'Two factor authentication is not verified',
                'status' => $tfaEnabled ? 'unauthenticated' : 'unbinding',
            ], 401);
        }

        return $next($request);
    }
}
