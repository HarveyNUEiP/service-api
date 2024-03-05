<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ServiceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(\App\Services\Contracts\AccountManagementService::class, \App\Services\AccountManagementService::class);
        $this->app->bind(\App\Services\Contracts\RoleBasedAccessControlService::class, \App\Services\RoleBasedAccessControlService::class);
        $this->app->bind(\App\Services\Contracts\TwoFactorAuthService::class, \App\Services\TwoFactorAuthService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
