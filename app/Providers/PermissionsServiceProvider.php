<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use App\Models\Privilege;
use App\Models\Feature;
use Illuminate\Support\ServiceProvider;
use DB;

class PermissionsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //custom @can gate by privilage
        $privileges = DB::table('privilege')
        ->join('feature','privilege.feature_id','=','feature.feature_id')
        ->join('permission','privilege.permission_id','=','permission.permission_id')
        ->select('feature.name as feature_name', 'permission.name as permission_name')
        ->groupBy('privilege.feature_id', 'privilege.permission_id')
        ->get();

        $privileges->map(function ($privilege) {
            Gate::define($privilege->feature_name.'.'.$privilege->permission_name, function ($user) use ($privilege) {
                return $user->hasFeaturePermission($privilege->feature_name, $privilege->permission_name);
            });
        });

        //custom @can gate by feature
        Feature::get()->map(function ($feature) {
            Gate::define($feature->name, function ($user) use ($feature) {
                return $user->hasFeature($feature->name);
            });
        });
    }
}
