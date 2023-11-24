<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $organizationRepository = App::make('App\Interfaces\OrganizationRepositoryInterface');
        if (Auth::check()) {
            $organization = $organizationRepository->getOrganizationByUser(Auth::user()->users_id);
        } else {
            $organization = $organizationRepository->getOrganizationDefault();
        }

        view()->share('siteOrganization', $organization);
    }
}
