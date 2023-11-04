<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Grid;
use App\Models\Inbound;
use App\Models\InboundDetail;
use App\Models\InboundType;
use App\Models\Package;
use App\Models\Status;
use App\Models\Transfer;
use App\Models\TransferDetail;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Session;
use Yajra\DataTables\Facades\DataTables;

class InboundController extends Controller
{
    public function index(Request $request)
    {
        $hub = DB::table('usershub')
        ->select('hub.hub_id','hub.name')
        ->join('hub', 'hub.hub_id', '=', 'usershub.hub_id')
        ->where('users_id', Session::get('userid'))->get();
        
        $inboundtype = DB::table('inboundtype')
        ->select('name')
        ->get();
        
        $date = "";
        if(isset($request->date))
        {
            $date = $request->date;
        }

        if ($request->ajax()) {
            $data = DB::table('inbound as a')
            ->select('a.inbound_id', 'a.code', 'a.created_date', 'a.created_by', 'b.name as type')
            ->selectRaw('COUNT(d.inbound_detail_id) as total_waybill')
            ->join('inboundtype as b', 'a.inbound_type_id', '=', 'b.inbound_type_id')
            ->join('inbounddetail as d', 'a.inbound_id', '=', 'd.inbound_id')
            ->join('package as e', 'd.package_id', '=', 'e.package_id')
            ->where('a.created_by', Session::get('username'))
            ->whereDate('a.created_date', $date == "" ? date('Y-m-d'):$date)
            ->groupBy('a.inbound_id')
            ->get();

            return DataTables::of($data)
                ->addColumn('inbound_id', function($data){
                    return $data->code;
                })
                ->addColumn('total_waybill', function($data){
                    return $data->total_waybill;
                })
                ->addColumn('type', function($data){
                    return $data->type;
                })
                ->addColumn('inbound_date', function($data){
                    return $data->created_date;
                })
                ->addColumn('created_by', function($data){
                    return $data->created_by;
                })
                ->addColumn('action', function($data){
                    return '<a class="btn btn-label-warning"><i class="tf-icons ti ti-eye ti-xs me-1"></i>View</a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('content.inbound.index', compact('hub', 'date', 'inboundtype'));
    }

    public function create(Request $request)
    {
        $validator = $request->validate([
            'hub'      => 'required|string',
            'location' => 'required|string',
            'waybill'  => 'required|string'
        ]);

        $hub      = $request->hub;
        $type     = $request->type;
        $location = $request->location;
        $waybill  = $request->waybill;

        $package = Package::where('tracking_number', $waybill)->get()->first();
        $stats = Status::whereIn('code', ['ENTRY'])->pluck('status_id')->toArray();

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

        $cekpackage = InboundDetail::where('package_id', $package->package_id)->first();
        if($cekpackage)
        {
            $routs = Inbound::where('routing_id', $cekpackage->routing_id)->first();
            echo json_encode("NOT*Waybill Has Routing On ".$routs->code);
            return;
        }

        $grid = Grid::where('hub_id', $hub)->where('name', $location)->get()->first();
        if(!$grid)
        {
            $data['hub_id']          = $package->hub_id;
            $data['name']            = $location;
            $data['created_date']    = Carbon::now();
            $data['modified_date']   = Carbon::now();
            $data['created_by']      = Session::get('username');
            $data['modified_by']     = Session::get('username');
            $grid = Grid::create($data);
        }

        if($request->inbound_id == "")
        {            
            $data['hub_id']          = $package->hub_id;
            $data['code']            = 'INB-'.date('Ymd').rand(1,1000);
            $data['inbound_type_id'] = InboundType::where('name', $type)->first()->inbound_type_id;
            $data['created_date']    = Carbon::now();
            $data['modified_date']   = Carbon::now();
            $data['created_by']      = Session::get('username');
            $data['modified_by']     = Session::get('username');
            $inbound = Inbound::create($data);
        }

        $inbound_id = isset($inbound) ? $inbound->inbound_id:$request->inbound_id;

        $detail['inbound_id']    = $inbound_id;
        $detail['package_id']    = $package->package_id;
        $detail['created_date']  = Carbon::now();
        $detail['modified_date'] = Carbon::now();
        $detail['created_by']    = Session::get('username');
        $detail['modified_by']   = Session::get('username');
        InboundDetail::create($detail);

        Package::where('package_id', $package->package_id)
        ->update(['status_id' => Status::where('code', 'RECEIVED')->first()->status_id]);

        echo json_encode("OK*".$waybill."*".$inbound_id);
        return;
    }

    public function create_transfer(Request $request)
    {
        $validator = $request->validate([
            'hub'      => 'required|string',
            'type'     => 'required|string',
            'location' => 'required|string',
            'mbag'     => 'required|string'
        ]);

        $hub      = $request->hub;
        $type     = $request->type;
        $location = $request->location;
        $mbag     = $request->mbag;

        $transfer = Transfer::where('code', $mbag)->get()->first();
        
        if(!$transfer)
        {
            echo json_encode("NOT*M-BAG Not Found");
            return;
        }

        $cektransfer = Inbound::where('transfer_id', $transfer->transfer_id)->first();
        if($cektransfer)
        {
            echo json_encode("NOT*M-BAG has already inbound");
            return;
        }

        $grid = Grid::where('hub_id', $hub)->where('name', $location)->get()->first();
        if(!$grid)
        {
            $data['hub_id']          = $hub;
            $data['name']            = $location;
            $data['created_date']    = Carbon::now();
            $data['modified_date']   = Carbon::now();
            $data['created_by']      = Session::get('username');
            $data['modified_by']     = Session::get('username');
            $grid = Grid::create($data);
        }

        if($request->inbound_id == "")
        {            
            $data['hub_id']          = $hub;
            $data['code']            = 'INB-'.date('Ymd').rand(1,1000);
            $data['inbound_type_id'] = InboundType::where('name', $type)->first()->inbound_type_id;
            $data['transfer_id']     = $transfer->transfer_id;
            $data['created_date']    = Carbon::now();
            $data['modified_date']   = Carbon::now();
            $data['created_by']      = Session::get('username');
            $data['modified_by']     = Session::get('username');
            $inbound = Inbound::create($data);
        }

        $inbound_id = isset($inbound) ? $inbound->inbound_id:$request->inbound_id;

        $transfer = TransferDetail::where('transfer_id', $transfer->transfer_id)->get();
        
        $package = [];
        foreach($transfer as $tf)
        {
            $detail['inbound_id']    = $inbound_id;
            $detail['package_id']    = $tf->package_id;
            $detail['created_date']  = Carbon::now();
            $detail['modified_date'] = Carbon::now();
            $detail['created_by']    = Session::get('username');
            $detail['modified_by']   = Session::get('username');
            InboundDetail::create($detail);
            
            $pack = Package::where('package_id', $tf->package_id)->get()->first();
            $package[] = $pack->tracking_number;
        }

        echo json_encode("OK*".json_encode($package)."*".$inbound_id);
        return;
    }
}