<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\HubRepositoryInterface;
use App\Interfaces\RegionRepositoryInterface;
use App\Interfaces\CourierRepositoryInterface;
use App\Interfaces\ReconcileRepositoryInterface;
use App\Interfaces\TransferRepositoryInterface;
use App\Interfaces\VendorRepositoryInterface;
use App\Interfaces\RoutingRepositoryInterface;
use App\Interfaces\PackageRepositoryInterface;
use App\Interfaces\InboundTypeRepositoryInterface;
use App\Interfaces\InboundRepositoryInterface;
use App\Interfaces\StatusRepositoryInterface;
use App\Repositories\HubRepository;
use App\Repositories\RegionRepository;
use App\Repositories\CourierRepository;
use App\Repositories\ReconcileRepository;
use App\Repositories\TransferRepository;
use App\Repositories\VendorRepository;
use App\Repositories\RoutingRepository;
use App\Repositories\PackageRepository;
use App\Repositories\InboundTypeRepository;
use App\Repositories\InboundRepository;
use App\Repositories\StatusRepository;

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
        $this->app->bind(TransferRepositoryInterface::class, TransferRepository::class);
        $this->app->bind(ReconcileRepositoryInterface::class, ReconcileRepository::class);
        $this->app->bind(PackageRepositoryInterface::class, PackageRepository::class);
        $this->app->bind(InboundTypeRepositoryInterface::class, InboundTypeRepository::class);
        $this->app->bind(InboundRepositoryInterface::class, InboundRepository::class);
        $this->app->bind(StatusRepositoryInterface::class, StatusRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
