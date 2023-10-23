<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Interfaces\TransferRepositoryInterface;
use App\Models\Hub;
use App\Models\Package;
use App\Models\Status;
use App\Models\Transfer;
use App\Models\TransferDetail;
use App\Models\TransferHistory;
use App\Models\UserHub;
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
        
        $usershub = UserHub::where('users_id', Session::get('userid'))->first();

        if($request->ajax())
        {
            $data = $this->transferRepository->dataTableTransfer();

            return datatables::of($data)
                ->addIndexColumn()
                ->editColumn('status', function($data){
                    return '<span class="badge bg-label-'.$data->status->label.'">'.ucwords($data->status->name).'</span>';
                })
                ->addColumn('action', function($data){
                    return '<a class="btn btn-label-warning" href=""><i class="tf-icons ti ti-book ti-xs me-1"></i>Print</a>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('content.routing.transfer', ['hub' => $hub, 'usershub' => $usershub]);
    }

    public function create(Request $request)
    {
        $validator = $request->validate([
            'hub_origin'  => 'required',
            'hub_dest'  => 'required',
            'waybill'   => 'required|string'
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
        if(!$package)
        {
            echo json_encode("NOT*Waybill Not Found");
            return;
        }

        if($request->transfer_id == "")
        {
            $detail['from_hub_id']   = $hub_origin;
            $detail['to_hub_id']     = $hub->hub_id;
            $detail['code']          = "MBAG-DTX".date('Y-m-d').rand(10,1000);
            $detail['status_id']     = Status::where('code', 'MOVING')->first()->status_id;
            $detail['created_date']  = date('Y-m-d H:i:s');
            $detail['modified_date'] = date('Y-m-d H:i:s');
            $detail['created_by']    = Session::get('username');
            $detail['modified_by']   = Session::get('username');
            $transfer = Transfer::create($detail);

            $detail['transfer_id']   = $hub_origin;
            $detail['status_id']     = Status::where('code', 'MOVING')->first()->status_id;
            $detail['created_date']  = date('Y-m-d H:i:s');
            $detail['modified_date'] = date('Y-m-d H:i:s');
            $detail['created_by']    = Session::get('username');
            $detail['modified_by']   = Session::get('username');
            $transfer = TransferHistory::create($detail);
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
        $detail['created_date']  = date('Y-m-d H:i:s');
        $detail['modified_date'] = date('Y-m-d H:i:s');
        $detail['created_by']    = Session::get('username');
        $detail['modified_by']   = Session::get('username');
        TransferDetail::create($detail);

        echo json_encode("OK*".$waybill."*".$transfer_id);
        return;
    }
}