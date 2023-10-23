<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Routing;
use App\Models\RoutingDetail;
use App\Models\RoutingHistory;
use App\Models\Spot;
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
        ->select('courier_id', 'a.name as full_name', 'vehicle_type')
        ->join('partner as b', 'a.partner_id','=','b.partner_id')
        ->join('userspartner as c', 'a.users_partner_id','=','c.users_partner_id')
        ->join('users as d', 'c.users_id','=','d.users_id')
        ->where('b.organization_id', Session::get('orgid'))->get();
        
        return view('content.delivery-record.create', ['hub' => $hub, 'courier' => $courier]);
    }

    public function update()
    {
        $courier = DB::table('courier as b')
        ->select('b.courier_id', 'b.name as full_name', 'vehicle_type')
        ->join('userspartner as c', 'b.users_partner_id','=','c.users_partner_id')
        ->join('users as d', 'c.users_id','=','d.users_id')
        ->where('b.hub_id', 2)
        ->get();

        $header = "";
        $detail = [];
        
        if(request()->has('code'))
        {
            $header = DB::table('routing as a')
            ->select('a.code', 'b.name as courier', 'a.courier_id', 'a.status_id', 'c.name as status')
            ->selectRaw('COUNT(d.routing_detail_id) as total_waybill')
            ->selectRaw('SUM(e.total_weight) as total_weight')
            ->selectRaw('SUM(e.total_koli) as total_koli')
            ->selectRaw('SUM(e.cod_price) as total_cod')
            ->join('courier as b', 'a.courier_id', '=', 'b.courier_id')
            ->join('status as c', 'a.status_id', '=', 'c.status_id')
            ->join('routingdetail as d', 'a.routing_id', '=', 'd.routing_id')
            ->join('package as e', 'd.package_id', '=', 'e.package_id')
            ->where('a.code', request()->get('code'))
            ->groupBy('a.routing_id')
            ->get()
            ->first();

            $detail = DB::table('routing as a')
            ->select('c.tracking_number as waybill', 'c.recipient_district as district', 'b.routing_detail_id as detail_id', 'a.courier_id')
            ->join('routingdetail as b', 'a.routing_id', '=', 'b.routing_id')
            ->join('package as c', 'b.package_id', '=', 'c.package_id')
            ->join('courier as d', 'a.courier_id', '=', 'd.courier_id')
            ->where('a.code', request()->get('code'))
            ->get();
        }

        return view('content.delivery-record.update', ['selected' => $header->courier_id ?? "", 'courier' => $courier, 'data' => $detail, 'header' => $header]);
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
            $data['spot_id']       = Spot::where('name', 'SPOT 1')->first()->spot_id;
            $data['status_id']     = Status::where('code', 'ASSIGNED')->first()->status_id;
            $data['code']          = 'DR-DTX'.date('Ymd').rand(1,1000);
            $data['created_date']  = date('Y-m-d H:i:s');
            $data['modified_date'] = date('Y-m-d H:i:s');
            $data['created_by']    = Session::get('fullname');
            $data['modified_by']   = Session::get('fullname');
            $routing = Routing::create($data);

            $detail['routing_id']    = $routing->routing_id;
            $detail['status_id']     = Status::where('code', 'ASSIGNED')->first()->status_id;
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
        $validator = $request->validate([
            'id'   => 'required'
        ]);

        DB::table('routingdetail')->where('routing_detail_id', $request->id)->delete();
        echo json_encode("OK*Success");
        return;
    }
}