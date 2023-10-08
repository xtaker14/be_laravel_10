<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\HubRepositoryInterface;
use App\Interfaces\RegionRepositoryInterface;
use App\Repositories\HubRepository;
use App\Repositories\RegionRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(HubRepositoryInterface::class, HubRepository::class);
        $this->app->bind(RegionRepositoryInterface::class, RegionRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
