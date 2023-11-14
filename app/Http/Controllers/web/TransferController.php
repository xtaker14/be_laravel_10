<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Interfaces\TransferRepositoryInterface;
use App\Models\Hub;
use App\Models\Package;
use App\Models\PackageHistory;
use App\Models\Status;
use App\Models\Transfer;
use App\Models\TransferDetail;
use App\Models\TransferHistory;
use App\Models\UserHub;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Session;
use Yajra\DataTables\Facades\DataTables;

class TransferController extends Controller
{
    private TransferRepositoryInterface $transferRepository;

    public function __construct(TransferRepositoryInterface $transferRepository)
    {
        $this->transferRepository = $transferRepository;
    }

    public function index(Request $request)
    {
        $hub = DB::table('hub')
        ->select('hub_id','name')
        ->where('organization_id', Session::get('orgid'))->get();
        
        if(isset($request->hub))
        {
            $usershub = UserHub::where('hub_id', $request->hub)->first();
        }
        else
        {
            $usershub = UserHub::where('users_id', Session::get('userid'))->first();
        }
        
        $userhub = DB::table('usershub')
        ->select('hub.hub_id','hub.name')
        ->join('hub', 'hub.hub_id', '=', 'usershub.hub_id')
        ->where('users_id', Session::get('userid'))->get();

        $date = "";
        if(isset($request->date))
        {
            $date = $request->date;
        }

        if($request->ajax())
        {            
            $data = $this->transferRepository->dataTableTransfer($date);

            return datatables::of($data)
                ->addIndexColumn()
                ->editColumn('status', function($data){
                    return '<span class="badge bg-label-'.$data->status_label.'">'.ucwords($data->status).'</span>';
                })
                ->addColumn('action', function($data){
                    return '<a class="btn btn-label-warning" href=""><i class="tf-icons ti ti-book ti-xs me-1"></i>Print</a>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('content.routing.transfer', ['hub' => $hub, 'usershub' => $usershub, 'hubuser' => $userhub, 'date' => $date]);
    }

    public function create(Request $request)
    {
        $validator = $request->validate([
            'hub_origin' => 'required',
            'hub_dest'   => 'required',
            'waybill'    => 'required|string'
        ]);

        $hub_origin = $request->hub_origin;
        $hub_dest = $request->hub_dest;
        $waybill = $request->waybill;

        $hub = Hub::where('hub_id', $hub_dest)->get()->first();
        if(!$hub)
        {
            echo json_encode("NOT*Hub Not Found");
            return;
        }

        $package = Package::where('tracking_number', $waybill)->get()->first();
        $stats = Status::whereIn('code', ['RECEIVED'])->pluck('status_id')->toArray();
        if(!$package)
        {
            echo json_encode("NOT*Waybill Not Found");
            return;
        }
        elseif(!in_array($package->status_id, $stats))
        {
            echo json_encode("NOT*Waybill must have received status");
            return;
        }

        if($request->transfer_id == "")
        {
            $detail['from_hub_id']   = $hub_origin;
            $detail['to_hub_id']     = $hub->hub_id;
            $detail['code']          = "MBAG-DTX".date('Y-m-d').rand(10,1000);
            $detail['status_id']     = Status::where('code', 'MOVING')->first()->status_id;
            $detail['created_date']  = Carbon::now();
            $detail['modified_date'] = Carbon::now();
            $detail['created_by']    = Session::get('username');
            $detail['modified_by']   = Session::get('username');
            $transfer = Transfer::create($detail);

            $history['transfer_id']   = $transfer->transfer_id;
            $history['status_id']     = Status::where('code', 'MOVING')->first()->status_id;
            $history['created_date']  = Carbon::now();
            $history['modified_date'] = Carbon::now();
            $history['created_by']    = Session::get('username');
            $history['modified_by']   = Session::get('username');
            $transfer = TransferHistory::create($history);
        }

        $transfer_id = isset($transfer) ? $transfer->transfer_id:$request->transfer_id;

        if($request->transfer_id != "")
        {
            $detail = TransferDetail::where('transfer_id', $transfer_id)->where('package_id', $package->package_id)->get()->first();
            if($detail)
            {
                echo json_encode("NOT*Waybill Has Exist");
                return;
            }
        }

        $detail['transfer_id']   = $transfer_id;
        $detail['package_id']    = $package->package_id;
        $detail['created_date']  = Carbon::now();
        $detail['modified_date'] = Carbon::now();
        $detail['created_by']    = Session::get('username');
        $detail['modified_by']   = Session::get('username');
        TransferDetail::create($detail);

        Package::where('package_id', $package->package_id)
        ->update(['status_id' => Status::where('code', 'TRANSFER')->first()->status_id]);

        $history['package_id']    = $package->package_id;
        $history['status_id']     = Status::where('code', 'TRANSFER')->first()->status_id;
        $history['created_date']  = Carbon::now();
        $history['modified_date'] = Carbon::now();
        $history['created_by']    = Session::get('username');
        $history['modified_by']   = Session::get('username');
        PackageHistory::create($history);

        echo json_encode("OK*".$waybill."*".$transfer_id);
        return;
    }
}