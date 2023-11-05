<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\RoutingDelivery;
use App\Models\Status;
use App\Helpers\Main;

class RoutingService
{
    private $auth;

    public function __construct($auth='api')
    { 
        $this->auth = auth($auth);
    }

    public function getByCourier(Request $request, $courier, $add_filter=false)
    {   
        // $user = $this->auth->user(); 
        $status_group = [
            'package' => Status::STATUS_GROUP['package'],
            'routing' => Status::STATUS_GROUP['routing'],
        ];

        $query = $courier->routings();

        if (is_callable($add_filter)) {
            $query = $add_filter($query);
            $routing = $query;
        }else{
            $routing = $query->first();
        }

        if(!$routing){
            return [
                'res' => 'error',
                'status_code' => 404,
                'msg' => __('messages.not_found'),
                'trace_code' => 'EXCEPTION015',
            ];
        }

        return [
            'res' => 'success',
            'status_code' => 200,
            'msg' => __('messages.success'),
            'trace_code' => null,
            'data' => $routing,
        ];
    }

    public function getAssignedByCourier(Request $request, $courier, $add_filter=false)
    {   
        // $user = $this->auth->user(); 
        $status_group = [
            'package' => Status::STATUS_GROUP['package'],
            'routing' => Status::STATUS_GROUP['routing'],
        ];

        $query = $courier->routings()
            ->whereHas('status', function ($query) use ($status_group) {
                return $query->where('code', '=', Status::STATUS[$status_group['routing']]['assigned']);
            });

        if (is_callable($add_filter)) {
            $query = $add_filter($query);
            $routing = $query;
        }else{
            $routing = $query->first();
        }

        if(!$routing){
            return [
                'res' => 'error',
                'status_code' => 404,
                'msg' => __('messages.not_found'),
                'trace_code' => 'EXCEPTION015',
            ];
        }

        return [
            'res' => 'success',
            'status_code' => 200,
            'msg' => __('messages.success'),
            'trace_code' => null,
            'data' => $routing,
        ];
    }

    public function getInprogressByCourier(Request $request, $courier, $add_filter=false)
    {   
        // $user = $this->auth->user(); 
        $status_group = [
            'package' => Status::STATUS_GROUP['package'],
            'routing' => Status::STATUS_GROUP['routing'],
        ];

        $query = $courier->routings()
            // ->whereHas('routinghistories.status', function ($query) use ($status_group) {
            //     return $query->where('code', '=', Status::STATUS[$status_group['routing']]['inprogress']);
            // })
            // ->whereDoesntHave('routinghistories.status', function ($query) use ($status_group) {
            //     return $query->where('code', '=', Status::STATUS[$status_group['routing']]['collected']);
            // })
            ->whereHas('status', function ($query) use ($status_group) {
                return $query->where('code', '=', Status::STATUS[$status_group['routing']]['inprogress']);
            });

        if (is_callable($add_filter)) {
            $query = $add_filter($query);
            $routing = $query;
        }else{
            $routing = $query->first();
        }

        if(!$routing){
            return [
                'res' => 'error',
                'status_code' => 404,
                'msg' => __('messages.not_found'),
                'trace_code' => 'EXCEPTION015',
            ];
        }

        return [
            'res' => 'success',
            'status_code' => 200,
            'msg' => __('messages.success'),
            'trace_code' => null,
            'data' => $routing,
        ];
    }

    public function summaryDrByRouting(Request $request, $routing, $add_filter = false)
    {
        $status_group = Status::STATUS_GROUP['package'];

        $baseQuery = $routing->routingdetails()
            ->with([
                'package',
                'package.packagehistories' => function ($q) {
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
                    Status::STATUS[$status_group]['return'],
                ]);
            });

        $query = is_callable($add_filter) ? $add_filter(clone $baseQuery) : clone $baseQuery; 

        $routingDetails = $query->get();
        if ($routingDetails->isEmpty()) {
            return [
                'res' => 'error',
                'status_code' => 404,
                'msg' => __('messages.not_found'),
                'trace_code' => 'EXCEPTION015',
            ];
        }

        // all delivery
        $totalPackages = count($routingDetails);
        $totalWeight = $routingDetails->sum('package.total_weight');
        $totalKoli = $routingDetails->sum('package.total_koli');
        // $totalMoney = $routingDetails->sum('package.package_price') + $routingDetails->sum('package.shipping_price') + $routingDetails->sum('package.cod_price');
        $totalMoney = $routingDetails->sum('package.cod_price');

        // cod non-cod
        $codQuery = clone $baseQuery;
        $cod = $codQuery->whereHas('package', function ($q) {
            return $q->where('package.cod_price', '>', 0);
        });
        $codCount = $cod->get()->count();
        $nonCodCount = $totalPackages - $codCount;

        // delivered
        $deliveredQuery = clone $baseQuery;
        // $deliveredPackages = $deliveredQuery->whereHas('package.packagehistories.status', function($q) use($status_group) {
        //     return $q->where('code', '=', Status::STATUS[$status_group]['delivered']);
        // })->get(); 
        $deliveredPackages = $deliveredQuery->whereHas('package.status', function ($q) use ($status_group) {
            return $q->where('code', '=', Status::STATUS[$status_group]['delivered']);
        })->get();

        $deliveredTotal = count($deliveredPackages);
        $deliveredWeight = $deliveredPackages->sum('package.total_weight');
        $deliveredKoli = $deliveredPackages->sum('package.total_koli');
        // $deliveredMoney = $deliveredPackages->sum('package.package_price') + $deliveredPackages->sum('package.shipping_price') + $deliveredPackages->sum('package.cod_price');
        $deliveredMoney = $deliveredPackages->sum('package.cod_price');

        // undelivered
        $undeliveredQuery = clone $baseQuery;
        // $undeliveredPackages = $undeliveredQuery->whereHas('package.packagehistories.status', function($q) use($status_group) {
        //     return $q->where('code', '=', Status::STATUS[$status_group]['undelivered']);
        // })->get(); 
        $undeliveredPackages = $undeliveredQuery->whereHas('package.status', function ($q) use ($status_group) {
            return $q->whereIn('code', [
                Status::STATUS[$status_group]['undelivered'],
                Status::STATUS[$status_group]['return'],
            ]);
        })->get();

        $undeliveredTotal = count($undeliveredPackages);
        $undeliveredWeight = $undeliveredPackages->sum('package.total_weight');
        $undeliveredKoli = $undeliveredPackages->sum('package.total_koli');
        // $undeliveredMoney = $undeliveredPackages->sum('package.package_price') + $undeliveredPackages->sum('package.shipping_price') + $undeliveredPackages->sum('package.cod_price');
        $undeliveredMoney = $undeliveredPackages->sum('package.cod_price');

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

    public function counterRoutingDelivery($routing_id, $params)
    {
        // gunakan DB transaction untuk menggunakan function ini
    
        // Cari atau buat record RoutingDelivery dalam transaksi
        // $routing_delivery = RoutingDelivery::firstOrNew(['routing_id' => $routing_id]);

        // handling race condition
        $routing_delivery = RoutingDelivery::where('routing_id', $routing_id)->lockForUpdate()->firstOrNew(['routing_id' => $routing_id]);

        // Update counter
        if (!empty($params['delivery'])) {
            $routing_delivery->delivery += $params['delivery'];
        }
        if (!empty($params['delivered'])) {
            $routing_delivery->delivered += $params['delivered'];
            $routing_delivery->delivery -= $params['delivered'];
        }
        if (!empty($params['undelivered'])) {
            $routing_delivery->undelivered += $params['undelivered'];
            $routing_delivery->delivery -= $params['undelivered'];
        }
        if (!empty($params['total_delivery'])) {
            $routing_delivery->total_delivery += $params['total_delivery'];
        }
        if (!empty($params['total_cod_price'])) {
            $routing_delivery->total_cod_price += $params['total_cod_price'];
        }
        if (!empty($params['total_shipping_price'])) {
            $routing_delivery->total_shipping_price += $params['total_shipping_price'];
        }
        if (!empty($params['total_package_price'])) {
            $routing_delivery->total_package_price += $params['total_package_price'];
        }

        // Set kolom created/modified by/at jika data baru dibuat
        if (!$routing_delivery->exists) {
            Main::setCreatedModifiedVal(true, $routing_delivery); 
        }

        // Simpan perubahan dalam transaksi
        $routing_delivery->save();

        return $routing_delivery;
    }

}
