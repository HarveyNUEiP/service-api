<?php

namespace App\Http\Middleware;

use App\Services\HrmSyncServiceManager;
use Closure;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthenticateWithJWT
{
    public const HRM_NUEIP_COMPANY_ID = '121';

    public function __construct(
        protected HrmSyncServiceManager $hrmSyncServiceManager
    ) {}

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     */
    public function handle($request, Closure $next)
    {
        try {
            if (!Auth::guard('api')->user()) {
                $payload = JWTAuth::parseToken()->getPayload();
                $sub = $payload->get('sub');
                $companyId = $payload->get('company_id');

                $user = ($sub && $companyId == self::HRM_NUEIP_COMPANY_ID)
                    ? $this->hrmSyncServiceManager->syncAccountByHrmId($sub)
                    : null;

                if (!$user) {
                    throw new JWTException('Unauthenticated', 401);
                }

                Auth::guard('api')->setUser($user);
            }
        } catch (JWTException $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
        return $next($request);
    }
}
