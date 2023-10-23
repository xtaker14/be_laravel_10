<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use Session;
use Yajra\DataTables\Facades\DataTables;
use App\Interfaces\RoutingRepositoryInterface;

class RoutingController extends Controller
{
    private RoutingRepositoryInterface $routingRepository;

    public function __construct(RoutingRepositoryInterface $routingRepository)
    {
        $this->routingRepository = $routingRepository;
    }

    public function index()
    {
        $hub = DB::table('hub')
        ->select('hub_id','name')
        ->where('organization_id', Session::get('orgid'))->get();
        
        return view('content.routing.transfer', ['hub' => $hub]);
    }

    public function update()
    {        
        return view('content.delivery-record.update');
    }

    public function codCollection(string $code)
    {
        $response = [];

        try {
            $routing = $this->routingRepository->getRoutingByCode($code);

            if ($routing) {
                $response['success'] = true; 
                $response['data'] = $routing;
                $response['error'] = "";
            } else {
                $response['success'] = false; 
                $response['data'] = [];
                $response['error'] = "Delivery Record Code Not Exist";
            }
        } catch (\Exception $e) {
            $response['success'] = false; 
            $response['data'] = [];
            $response['error'] = $e->getMessage();
        }

        return response()->json($response);
    }
}