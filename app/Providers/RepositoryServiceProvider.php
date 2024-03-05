<?php

namespace App\Providers;

use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(\App\Repositories\Contracts\UserRepository::class, \App\Repositories\UserRepository::class);
        $this->app->bind(\App\Repositories\Contracts\RoleRepository::class, \App\Repositories\RoleRepository::class);
        $this->app->bind(\App\Repositories\Contracts\PermissionRepository::class, \App\Repositories\PermissionRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
