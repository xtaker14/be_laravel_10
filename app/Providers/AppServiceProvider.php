<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App;

use App\Interfaces\UserRepositoryInterface;
use App\Interfaces\RoleRepositoryInterface;

use App\Services\UserService;
use App\Services\RoleService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserService::class, function ($app) {
            return new UserService($app->make(UserRepositoryInterface::class));
        });
        $this->app->bind(RoleService::class, function ($app) {
            return new RoleService($app->make(RoleRepositoryInterface::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
    }
}
