<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Routing;
use App\Models\RoutingDetail;
use App\Models\RoutingHistory;
use App\Models\Status;
use DB;
use Illuminate\Http\Request;
use Session;
use Yajra\DataTables\Facades\DataTables;

class DeliveryrecordController extends Controller
{
    public function index()
    {
        $hub = DB::table('hub')
        ->select('hub_id','name')
        ->where('organization_id', Session::get('orgid'))->get();
        
        $courier = DB::table('courier as a')
        ->select('courier_id', 'full_name', 'vehicle_type')
        ->join('partner as b', 'a.partner_id','=','b.partner_id')
        ->join('users as c', 'a.users_id','=','c.users_id')
        ->where('b.organization_id', Session::get('orgid'))->get();
        
        return view('content.delivery-record.create', ['hub' => $hub, 'courier' => $courier]);
    }

    public function update()
    {
        $hub = DB::table('hub')
        ->select('hub_id','name')
        ->where('organization_id', Session::get('orgid'))->get();
        
        $courier = DB::table('courier as a')
        ->select('courier_id', 'full_name', 'vehicle_type')
        ->join('partner as b', 'a.partner_id','=','b.partner_id')
        ->join('users as c', 'a.users_id','=','c.users_id')
        ->where('b.organization_id', Session::get('orgid'))->get();

        return view('content.delivery-record.update', ['hub' => $hub, 'courier' => $courier]);
    }

    public function create_process(Request $request)
    {
        $validator = $request->validate([
            'courier'   => 'required|string',
            'transport' => 'required|string',
            'date'      => 'required|string',
            'waybill'   => 'required|string'
        ]);

        $courier = $request->courier;
        $waybill = $request->waybill;

        $package = Package::where('tracking_number', $waybill)->get()->first();
        if(!$package)
        {
            echo json_encode("NOT*Waybill Not Found");
            return;
        }

        $data['spot_id']       = '';
        $data['courier_id']    = $courier;
        $data['code']          = 'DR-DTX'.date('Ymd').rand(1,1000);
        $data['created_date']  = date('Y-m-d H:i:s');
        $data['modified_date'] = date('Y-m-d H:i:s');
        $data['created_by']    = Session::get('userid');
        $data['modified_by']   = Session::get('userid');
        $routing = Routing::create($data);

        $detail['routing_id']    = $routing->id;
        $detail['package_id']    = $package->package_id;
        $detail['created_date']  = date('Y-m-d H:i:s');
        $detail['modified_date'] = date('Y-m-d H:i:s');
        $detail['created_by']    = Session::get('userid');
        $detail['modified_by']   = Session::get('userid');
        RoutingDetail::create($detail);

        $detail['routing_id']    = $routing->routing_id;
        $detail['status_id']     = Status::where('code', 'ROUTING')->first()->status_id;
        $detail['created_date']  = date('Y-m-d H:i:s');
        $detail['modified_date'] = date('Y-m-d H:i:s');
        $detail['created_by']    = Session::get('fullname');
        $detail['modified_by']   = Session::get('fullname');
        RoutingHistory::create($detail);

        echo json_encode("OK*".$waybill);
        return;
    }
}