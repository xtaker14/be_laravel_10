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
        ->join('userspartner as c', 'a.users_partner_id','=','c.users_partner_id')
        ->join('users as d', 'c.users_id','=','d.users_id')
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
        ->join('userspartner as c', 'a.users_partner_id','=','c.users_partner_id')
        ->join('users as d', 'c.users_id','=','d.users_id')
        ->where('b.organization_id', Session::get('orgid'))->get();

        $data = [];
        
        if(request()->has('courier'))
        {
            $data = DB::table('routing as a')
            ->select('c.tracking_number as waybill', 'g.name as district')
            ->join('routingdetail as b', 'a.routing_id', '=', 'b.routing_id')
            ->join('package as c', 'b.package_id', '=', 'c.package_id')
            ->join('courier as d', 'a.courier_id', '=', 'd.courier_id')
            ->join('spot as e', 'a.spot_id', '=', 'e.spot_id')
            ->join('spotarea as f', 'e.spot_id', '=', 'f.spot_id')
            ->join('district as g', 'f.district_id', '=', 'g.district_id')
            ->where('d.courier_id', request()->get('courier'))
            ->get();
        }

        return view('content.delivery-record.update', ['hub' => $hub, 'courier' => $courier, 'data' => $data]);
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
        elseif($package->status_id != Status::where('code', 'ENTRY')->first()->status_id)
        {
            echo json_encode("NOT*Invalid Status");
            return;
        }

        if($request->dr_id == "")
        {
            $data['courier_id']    = $courier;
            $data['status_id']     = Status::where('code', 'ROUTING')->first()->status_id;
            $data['code']          = 'DR-DTX'.date('Ymd').rand(1,1000);
            $data['created_date']  = date('Y-m-d H:i:s');
            $data['modified_date'] = date('Y-m-d H:i:s');
            $data['created_by']    = Session::get('fullname');
            $data['modified_by']   = Session::get('fullname');
            $routing = Routing::create($data);

            $detail['routing_id']    = $routing->routing_id;
            $detail['status_id']     = Status::where('code', 'ROUTING')->first()->status_id;
            $detail['created_date']  = date('Y-m-d H:i:s');
            $detail['modified_date'] = date('Y-m-d H:i:s');
            $detail['created_by']    = Session::get('fullname');
            $detail['modified_by']   = Session::get('fullname');
            RoutingHistory::create($detail);
        }

        $routing_id = isset($routing) ? $routing->routing_id:$request->dr_id;

        $cekdetail = RoutingDetail::where('package_id', $package->package_id)->where('routing_id', $routing_id)->first();
        if($cekdetail)
        {
            echo json_encode("NOT*Duplicate Waybill");
            return;
        }

        $detail['routing_id']    = $routing_id;
        $detail['package_id']    = $package->package_id;
        $detail['created_date']  = date('Y-m-d H:i:s');
        $detail['modified_date'] = date('Y-m-d H:i:s');
        $detail['created_by']    = Session::get('fullname');
        $detail['modified_by']   = Session::get('fullname');
        RoutingDetail::create($detail);

        Package::where('package_id', $package->package_id)
        ->update(['status_id' => Status::where('code', 'ROUTING')->first()->status_id]);

        echo json_encode("OK*".$waybill."*".$routing_id);
        return;
    }

    public function update_process(Request $request)
    {
        echo json_encode("OK");
        return;
    }
}