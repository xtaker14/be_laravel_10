<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\HubRepositoryInterface;
use App\Interfaces\RoutingRepositoryInterface;
use App\Interfaces\PackageRepositoryInterface;
use Carbon\Carbon;

class DashboardController extends Controller
{
    private HubRepositoryInterface $hubRepository;
    private RoutingRepositoryInterface $routingRepository;
    private PackageRepositoryInterface $packageRepository;

    public function __construct(HubRepositoryInterface $hubRepository, PackageRepositoryInterface $packageRepository, RoutingRepositoryInterface $routingRepository)
    {
        $this->hubRepository = $hubRepository;
        $this->routingRepository = $routingRepository;
        $this->packageRepository = $packageRepository;
    }

    public function index(Request $request)
    {
        $hubs = $this->hubRepository->getAllHub();

        $total_dr = $this->routingRepository->countRouting();

        return view('content.dashboard', compact('hubs','total_dr'));
    }

    public function summary(Request $request)
    {
        try {
            $origin = $request->input('originFilter');
            $created = $request->input('createdFilter');

            if ($created != "All") {
                $explode_created = explode(" - ",$created);
                $created_start = Carbon::createFromFormat('m/d/Y', $explode_created[0])->toDateString().' 00:00:00';
                $created_end = Carbon::createFromFormat('m/d/Y', $explode_created[1])->toDateString().' 23:59:59';
            } else {
                $created_start = "All";
                $created_end = "All";
            }

            $createdFilter = [
                'created_start' => $created_start,
                'created_end' => $created_end,
            ];

            $summary = $this->packageRepository->summaryStatus($origin, $createdFilter);

            $response['success'] = true; 
            $response['data'] = $summary;
            $response['error'] = "";
        } catch (\Exception $e) {
            $response['success'] = false; 
            $response['data'] = [];
            $response['error'] = $e->getMessage();
        }

        return response()->json($response);
    }

    public function orderTracking(Request $request)
    {
        try {
            $waybill = $request->input('waybill');

            $package = $this->packageRepository->getPackageInformation($waybill);

            if ($package) {
                $response['success'] = true; 
                $response['data'] = $package;
                $response['error'] = "";
            } else {
                $response['success'] = false; 
                $response['data'] = [];
                $response['error'] = "Waybill tidak di temukan";
            }

        } catch (\Exception $e) {
            $response['success'] = false; 
            $response['data'] = [];
            $response['error'] = $e->getMessage();
        }

        return response()->json($response);
    }

    public function routingTracking(Request $request)
    {
        try {
            $routing = $request->input('routing');

            $routing = $this->routingRepository->getRoutingInformation($routing);

            if ($routing) {
                $response['success'] = true; 
                $response['data'] = $routing;
                $response['error'] = "";
            } else {
                $response['success'] = false; 
                $response['data'] = [];
                $response['error'] = "Delivery Record tidak di temukan";
            }

        } catch (\Exception $e) {
            $response['success'] = false; 
            $response['data'] = [];
            $response['error'] = $e->getMessage();
        }

        return response()->json($response);
    }
}