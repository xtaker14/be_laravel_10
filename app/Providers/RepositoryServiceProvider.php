<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\HubRepositoryInterface;
use App\Interfaces\RegionRepositoryInterface;
use App\Interfaces\CourierRepositoryInterface;
use App\Interfaces\VendorRepositoryInterface;
use App\Interfaces\RoutingRepositoryInterface;
use App\Repositories\HubRepository;
use App\Repositories\RegionRepository;
use App\Repositories\CourierRepository;
use App\Repositories\VendorRepository;
use App\Repositories\RoutingRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(HubRepositoryInterface::class, HubRepository::class);
        $this->app->bind(RegionRepositoryInterface::class, RegionRepository::class);
        $this->app->bind(CourierRepositoryInterface::class, CourierRepository::class);
        $this->app->bind(VendorRepositoryInterface::class, VendorRepository::class);
        $this->app->bind(RoutingRepositoryInterface::class, RoutingRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
