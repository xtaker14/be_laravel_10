<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Interfaces\TransferRepositoryInterface;
use App\Models\Hub;
use App\Models\HubArea;
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
use SimpleSoftwareIO\QrCode\Facades\QrCode;
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
                    return '<button type="button" id="'.$data->transfer_id.'" onClick="qrcode(this.id)" class="btn btn-warning qrcode" data-bs-toggle="modal" data-bs-target="#qrcode"><i class="tf-icons ti ti-book ti-xs me-1"></i>Print</button>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('content.transfer.transfer', ['hub' => $hub, 'usershub' => $usershub, 'hubuser' => $userhub, 'date' => $date]);
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
            echo json_encode("NOT*Hub destination not found");
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

        $hubarea = DB::table('hubarea')
        ->join('city', 'hubarea.city_id', '=', 'city.city_id')
        ->where('city.name', $package->recipient_city)
        ->first();

        if($hubarea->hub_id != $hub->hub_id)
        {
            echo json_encode("NOT*Hub destination doesnt match with waybill destination");
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

    public function getQrdata(Request $request)
    {
        $data = DB::table('transfer')
        ->join('transferdetail', 'transfer.transfer_id', '=', 'transferdetail.transfer_id')
        ->join('package', 'transferdetail.package_id', '=', 'package.package_id')
        ->join('hub as from_hub', 'transfer.from_hub_id', '=', 'from_hub.hub_id')
        ->join('hub as to_hub', 'transfer.to_hub_id', '=', 'to_hub.hub_id')
        ->select('transfer.transfer_id', 'transfer.code', 'transfer.created_date as transfer_date', 'from_hub.name as hub_origin', 'to_hub.name as hub_dest')
        ->selectRaw('COUNT(package.package_id) as total_waybill')
        ->where('transfer.transfer_id', $request->id)
        ->groupBy('transfer.transfer_id')
        ->get()
        ->first();

        if(!$data)
        {
            echo json_encode("NOT*Data tidak ditemukan");
        }
        
        $qrcode = QrCode::size(150)->generate($data->code);

        echo json_encode("OK*".$data->code."*".$data->hub_origin."*".$data->hub_dest."*".$data->total_waybill."*".$data->transfer_date."*".$qrcode);
        return;
    }
}