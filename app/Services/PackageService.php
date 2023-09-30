<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

use App\Models\User;
use App\Models\Status;
use App\Helpers\Main;

class PackageService
{
    private $auth;

    public function __construct($auth='api')
    { 
        $this->auth = auth($auth);
    }

    public function summary(Request $request, $routing, $add_filter=false)
    {
        $status_group = Status::STATUS_GROUP['package'];

        $baseQuery = $routing->routingdetails()
            ->with([
                'package',
                'package.packagehistories' => function($q) {
                    $q->orderBy('package_history_id', 'DESC')
                        ->limit(1);
                },
                // 'package.packagehistories.status',
            ])
            ->whereHas('package.status', function ($query) use ($status_group) {
                return $query->whereIn('code', [
                    Status::STATUS[$status_group]['ondelivery'],
                    Status::STATUS[$status_group]['delivered'], 
                    Status::STATUS[$status_group]['undelivered'],
                ]);
            });
            
        $query = is_callable($add_filter) ? $add_filter(clone $baseQuery) : clone $baseQuery;

        // $sql = $query->toSql();
        // $bindings = $query->getBindings();

        // foreach($bindings as $binding) {
        //     $sql = preg_replace('/\?/', is_numeric($binding) ? $binding : "'" . $binding . "'", $sql, 1);
        // }

        // return [
        //     'data' => [$sql, $bindings],
        //     'data' => $sql,
        // ];

        $routingDetails = $query->get();
        if ($routingDetails->isEmpty()) {
            return [
                'res' => 'error',
                'status_code' => 404,
                'msg' => 'Package ' . __('messages.not_found'),
                'trace_code' => 'EXCEPTION015',
            ];
        }

        // all delivery
        $totalPackages = count($routingDetails);
        $totalWeight = $routingDetails->sum('package.total_weight');
        $totalKoli = $routingDetails->sum('package.total_koli');
        $totalMoney = $routingDetails->sum('package.package_price') + $routingDetails->sum('package.shipping_price') + $routingDetails->sum('package.cod_price');
        
        // cod non-cod
        $codQuery = clone $baseQuery;
        $cod = $codQuery->whereHas('package', function($q) {
            return $q->where('package.cod_price', '>', 0);
        });
        $codCount = $cod->get()->count();
        $nonCodCount = $totalPackages - $codCount;

        // delivered
        $deliveredQuery = clone $baseQuery;
        // $deliveredPackages = $deliveredQuery->whereHas('package.packagehistories.status', function($q) use($status_group) {
        //     return $q->where('code', '=', Status::STATUS[$status_group]['delivered']);
        // })->get(); 
        $deliveredPackages = $deliveredQuery->whereHas('package.status', function($q) use($status_group) {
            return $q->where('code', '=', Status::STATUS[$status_group]['delivered']);
        })->get(); 

        $deliveredTotal = count($deliveredPackages);
        $deliveredWeight = $deliveredPackages->sum('package.total_weight');
        $deliveredKoli = $deliveredPackages->sum('package.total_koli');
        $deliveredMoney = $deliveredPackages->sum('package.package_price') + $deliveredPackages->sum('package.shipping_price') + $deliveredPackages->sum('package.cod_price');

        // undelivered
        $undeliveredQuery = clone $baseQuery;
        // $undeliveredPackages = $undeliveredQuery->whereHas('package.packagehistories.status', function($q) use($status_group) {
        //     return $q->where('code', '=', Status::STATUS[$status_group]['undelivered']);
        // })->get(); 
        $undeliveredPackages = $undeliveredQuery->whereHas('package.status', function($q) use($status_group) {
            return $q->where('code', '=', Status::STATUS[$status_group]['undelivered']);
        })->get(); 

        $undeliveredTotal = count($undeliveredPackages);
        $undeliveredWeight = $undeliveredPackages->sum('package.total_weight');
        $undeliveredKoli = $undeliveredPackages->sum('package.total_koli');
        $undeliveredMoney = $undeliveredPackages->sum('package.package_price') + $undeliveredPackages->sum('package.shipping_price') + $undeliveredPackages->sum('package.cod_price');

        return [
            'res' => 'success',
            'status_code' => 200,
            'msg' => __('messages.success'),
            'trace_code' => null,
            'data' => [
                'delivery_record' => $routing->code,
                'delivery' => [
                    'total' => $totalPackages,
                    'total_complete' => $deliveredTotal + $undeliveredTotal,
                    'total_weight' => number_format($totalWeight, 2),
                    'total_koli' => $totalKoli,
                    'total_money' => number_format($totalMoney, 2),
                ],
                'cod' => $codCount,
                'non_cod' => $nonCodCount,
                'delivered' => [
                    'total' => $deliveredTotal,
                    'total_weight' => number_format($deliveredWeight, 2),
                    'total_koli' => $deliveredKoli,
                    'total_money' => number_format($deliveredMoney, 2),
                ],
                'undelivered' => [
                    'total' => $undeliveredTotal,
                    'total_weight' => number_format($undeliveredWeight, 2),
                    'total_koli' => $undeliveredKoli,
                    'total_money' => number_format($undeliveredMoney, 2),
                ],
                'date' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
        ];
    }

    public function getOndelivery(Request $request, $routing, $add_filter=false)
    {
        $status_group = Status::STATUS_GROUP['package'];

        $query = $routing->routingdetails()
            ->with([
                'package', 
                'package.packagehistories' => function ($query){
                    $query->orderBy('package_history_id', 'DESC')
                        ->limit(1);
                }, 
                // 'package.packagehistories.status', 
            ])
            // ->whereHas('package.packagehistories.status', function ($query) use ($status_group) {
            //     return $query->where('code', '=', Status::STATUS[$status_group]['ondelivery']);
            // })
            // ->whereDoesntHave('package.packagehistories.status', function ($query) use ($status_group) {
            //     return $query->whereIn('code', [
            //         Status::STATUS[$status_group]['delivered'], 
            //         Status::STATUS[$status_group]['undelivered'],
            //     ]);
            // })
            ->whereHas('package.status', function ($query) use ($status_group) {
                return $query->where('code', '=', Status::STATUS[$status_group]['ondelivery']);
            });
        
        if (is_callable($add_filter)) {
            $query = $add_filter($query);
            $get_ondelivery = $query;
        }else{
            $get_ondelivery = $query->first();
        }
        
        if(!$get_ondelivery){
            return [
                'res' => 'error',
                'status_code' => 404,
                'msg' => 'Package ' . __('messages.not_found'),
                'trace_code' => 'EXCEPTION015',
            ];
        }

        return [
            'res' => 'success',
            'status_code' => 200,
            'msg' => __('messages.success'),
            'trace_code' => null,
            'data' => $get_ondelivery,
        ];
    }
}
