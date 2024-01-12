<?php

namespace App\Providers;

use App\Services\HrmApiService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(125);

        Http::macro('hrm', function () {
            return Http::withOptions([
                'base_uri' => config('services.hrm.base_uri'),
                'verify' => config('services.hrm.verify'),
            ])
            ->withBasicAuth(
                config('services.hrm.api_user'),
                config('services.hrm.api_pass'),
            );
        });
    }
}
