<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Interfaces\StatusRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Interfaces\RoleRepositoryInterface;

use App\Repositories\StatusRepository;
use App\Repositories\UserRepository;
use App\Repositories\RoleRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(StatusRepositoryInterface::class, StatusRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
