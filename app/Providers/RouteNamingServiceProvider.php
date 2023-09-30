<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteNamingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $routes = Route::getRoutes();
 
        foreach ($routes as $route) {
            $uri = $route->uri(); 
            $exp_uri = explode('/',$uri);
            if($exp_uri[0] == 'api'){
                $name = str_replace('/', '.', $uri); 
                $route->name($name);
                // Re-cache rute
                // \Illuminate\Support\Facades\Artisan::call('route:cache');
            } 
        }
    }
}
