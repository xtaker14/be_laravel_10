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
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Yajra\DataTables\Facades\DataTables;

class DeliveryrecordController extends Controller
{
    public function index(Request $request)
    {
        $hub = DB::table('usershub')
        ->select('hub.hub_id','hub.name')
        ->join('hub', 'hub.hub_id', '=', 'usershub.hub_id')
        ->where('users_id', Session::get('userid'))->get();
        
        $courier = DB::table('courier as a')
        ->select('courier_id', 'd.full_name', 'vehicle_type')
        ->join('userspartner as c', 'a.users_partner_id','=','c.users_partner_id')
        ->join('users as d', 'c.users_id','=','d.users_id')
        ->where('a.hub_id', Session::get('hubid'))->get();
        
        $date = "";
        if(isset($request->date))
        {
            $date = $request->date;
        }

        if ($request->ajax()) {
            $data = DB::table('routing as a')
            ->select('a.code', 'b.name as courier', 'a.courier_id', 'a.status_id', 'c.name as status', 'c.label as status_label')
            ->selectRaw('COUNT(d.routing_detail_id) as total_waybill')
            ->selectRaw('SUM(e.total_weight) as total_weight')
            ->selectRaw('SUM(e.total_koli) as total_koli')
            ->selectRaw('SUM(e.cod_price) as total_cod')
            ->join('courier as b', 'a.courier_id', '=', 'b.courier_id')
            ->join('status as c', 'a.status_id', '=', 'c.status_id')
            ->join('routingdetail as d', 'a.routing_id', '=', 'd.routing_id')
            ->join('package as e', 'd.package_id', '=', 'e.package_id')
            ->where('a.created_by', Session::get('username'))
            ->whereDate('a.created_date', $date == "" ? date('Y-m-d'):$date)
            ->groupBy('a.routing_id')
            ->get();

            return datatables::of($data)
                ->addColumn('record_id', function($data){
                    return $data->code;
                })
                ->addColumn('courier', function($data){
                    return $data->courier;
                })
                ->addColumn('total_waybill', function($data){
                    return $data->total_waybill;
                })
                ->addColumn('total_koli', function($data){
                    return $data->total_koli;
                })
                ->addColumn('total_weight', function($data){
                    return $data->total_weight;
                })
                ->addColumn('total_cod', function($data){
                    return $data->total_cod;
                })
                ->addColumn('status', function($data){
                    return '<span class="badge bg-label-'.$data->status_label.'">'.ucwords($data->status).'</span>';
                })
                ->addColumn('action', function($data){
                    return '<button type="button" id="qr" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#qrcode"><i class="tf-icons ti ti-eye ti-xs me-1"></i>View</button>';
                    // return '<a class="btn btn-label-warning"><i class="tf-icons ti ti-eye ti-xs me-1"></i>View</a>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('content.delivery-record.create', ['hub' => $hub, 'courier' => $courier, 'date' => $date]);
    }

    public function update()
    {
        $courier = [];
        $header = "";
        $detail = [];
        
        if(request()->has('code'))
        {
            $header = DB::table('routing as a')
            ->select('a.code', 'b.name as courier', 'a.courier_id', 'b.hub_id', 'a.status_id', 'c.name as status')
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

            if($header == null)
            {
                return redirect()->route('update-record')->with('failed', 'DR code not found');
            }

            $detail = DB::table('routing as a')
            ->select('c.tracking_number as waybill', 'c.recipient_district as district', 'b.routing_detail_id as detail_id', 'a.courier_id')
            ->join('routingdetail as b', 'a.routing_id', '=', 'b.routing_id')
            ->join('package as c', 'b.package_id', '=', 'c.package_id')
            ->join('courier as d', 'a.courier_id', '=', 'd.courier_id')
            ->where('a.code', request()->get('code'))
            ->get();

            $courier = DB::table('courier as b')
            ->select('b.courier_id', 'b.name as full_name', 'vehicle_type')
            ->join('userspartner as c', 'b.users_partner_id','=','c.users_partner_id')
            ->join('users as d', 'c.users_id','=','d.users_id')
            ->where('b.hub_id', $header->hub_id)
            ->get();
        }

        return view('content.delivery-record.update', ['selected' => $header->courier_id ?? "", 'courier' => $courier, 'data' => $detail, 'header' => $header]);
    }

    public function create_process(Request $request)
    {
        $validator = $request->validate([
            'hub'       => 'required|string',
            'courier'   => 'required|string',
            'transport' => 'required|string',
            'date'      => 'required|string',
            'waybill'   => 'required|string'
        ]);

        $hub     = $request->hub;
        $courier = $request->courier;
        $waybill = $request->waybill;

        $package = Package::where('tracking_number', $waybill)->get()->first();
        $stats = Status::whereIn('code', ['ENTRY', 'ASSIGNED'])->pluck('status_id')->toArray();

        if(!$package)
        {
            echo json_encode("NOT*Waybill Not Found");
            return;
        }
        elseif($package->hub_id != $hub)
        {
            echo json_encode("NOT*Cannot process waybill on other Hub");
            return;
        }
        elseif(!in_array($package->status_id, $stats))
        {
            echo json_encode("NOT*Invalid Status");
            return;
        }

        $cekpackage = RoutingDetail::where('package_id', $package->package_id)->first();
        if($cekpackage)
        {
            $routs = Routing::where('routing_id', $cekpackage->routing_id)->first();
            echo json_encode("NOT*Waybill Has Routing On ".$routs->code);
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
            $data['created_by']    = Session::get('username');
            $data['modified_by']   = Session::get('username');
            $routing = Routing::create($data);

            $detail['routing_id']    = $routing->routing_id;
            $detail['status_id']     = Status::where('code', 'ASSIGNED')->first()->status_id;
            $detail['created_date']  = date('Y-m-d H:i:s');
            $detail['modified_date'] = date('Y-m-d H:i:s');
            $detail['created_by']    = Session::get('username');
            $detail['modified_by']   = Session::get('username');
            RoutingHistory::create($detail);
        }

        $routing_id = isset($routing) ? $routing->routing_id:$request->dr_id;

        $detail['routing_id']    = $routing_id;
        $detail['package_id']    = $package->package_id;
        $detail['created_date']  = date('Y-m-d H:i:s');
        $detail['modified_date'] = date('Y-m-d H:i:s');
        $detail['created_by']    = Session::get('username');
        $detail['modified_by']   = Session::get('username');
        RoutingDetail::create($detail);

        Package::where('package_id', $package->package_id)
        ->update(['status_id' => Status::where('code', 'ROUTING')->first()->status_id]);

        echo json_encode("OK*".$waybill."*".$routing_id);
        return;
    }

    public function update_process(Request $request)
    {
        $validator = $request->validate([
            'courier'   => 'required',
            'code'   => 'required'
        ]);

        $updates = Routing::where('code', $request->code)
        ->update(['courier_id' => $request->courier]);

        echo json_encode("OK*Success");
        return;
    }
    
    public function drop_waybill(Request $request)
    {
        $validator = $request->validate([
            'id'   => 'required'
        ]);

        $routingdetail = RoutingDetail::where('routing_detail_id', $request->id)->first();
        
        $updates = Package::where('package_id', $routingdetail->package_id)
        ->update(['status_id' => Status::where('code', 'ENTRY')->first()->status_id]);

        DB::table('routingdetail')->where('routing_detail_id', $request->id)->delete();
        echo json_encode("OK*Success");
        return;
    }

    public function generate_qr(Request $request)
    {
        return QrCode::generate(
            $request->code
        );
    }
}