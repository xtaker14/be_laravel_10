<?php

namespace App\Http\Controllers\web;

use App\Exports\PackageExport;
use App\Http\Controllers\Controller;
use App\Imports\PackageImport;
use App\Models\Hub;
use App\Models\Package;
use App\Models\PackageuploadHistory;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Session;
use Yajra\DataTables\Facades\DataTables;

class DeliveryorderController extends Controller
{
    public function index(Request $request)
    {
        $hub = DB::table('usershub')
        ->select('hub.hub_id','hub.name')
        ->join('hub', 'usershub.hub_id', '=', 'hub.hub_id')
        ->where('usershub.users_id', Session::get('userid'))->get();
        
        $date = "";
        if(isset($request->date))
        {
            $date = $request->date;
        }

        if($request->ajax())
        {
            $data = new PackageuploadHistory;
            $data = $data->where('created_by', Session::get('username'));
            $data = $data->whereDate('created_date', $date == "" ? date('Y-m-d'):$date);
            $data = $data->latest();

            return datatables::of($data)
                ->addColumn('master_waybill', function($data){
                    return $data->code;
                })
                ->addColumn('filename', function($data){
                    return $data->filename;
                })
                ->addColumn('total_waybill', function($data){
                    return $data->total_waybill;
                })
                ->addColumn('upload_time', function($data){
                    return $data->created_date;
                })
                ->addColumn('upload_by', function($data){
                    return $data->created_by;
                })
                ->addColumn('action', function($data){
                    return '<a class="btn btn-label-warning" href="'. route('login').'"><i class="tf-icons ti ti-book ti-xs me-1"></i>Print</a>';
                })
                ->make(true);
        }

        return view('content.delivery-order.request-waybill', ['hub' => $hub, 'date' => $date]);
    }

    public function upload_reqwaybill(Request $request)
    {
        $request->session()->forget('order_result');

        $extensions = array("xls","xlsx","csv");

        $extens = array($request->file('file')->getClientOriginalExtension());
        if(!in_array($extens[0], $extensions)){
            return "NOT*Unsupported file format";
        }

        $import = new PackageImport;
        Excel::import($import, $request->file('file'));
        $import_result = array();
        foreach($import->result() as $results) {
            $import_result[] = $results[0];
        }
        
        foreach ($import->failures() as $failure) {
            $failed = $failure->values();
            $failed['waybill'] = "";
            $failed['result'] = $failure->errors()[0];

            $import_result[] = $failed;
        }
        
        //if import doesnt have success data
        $have_success = 0;
        foreach($import_result as $imp_res)
        {
            if($imp_res['result'] == "SUCCESS")
            {
                $have_success = 1;
                break;
            }
        }

        $last = 1;
        
        $lastId = PackageuploadHistory::orderBy('upload_id', 'desc')->first();
        if($lastId)
        {
            $last = $lastId['upload_id'] + 1;
        }

        $upload['code']          = 'MW FAILED ALL';
        $upload['total_waybill'] = count($import->result());
        $upload['filename']      = $request->file('file')->getClientOriginalName();
        $upload['created_date']  = Carbon::now();
        $upload['created_by']    = Session::get('username');
        
        if($have_success == 1)
        {
            $upload['code']      = 'MW'.date('Ymd').$last.rand(100, 1000);
            $history = PackageuploadHistory::create($upload);
        }

        $result = [
            "filename" => "Result - ".$upload['code'].".xlsx",
            "result" => base64_encode(json_encode($import_result))
        ];

        $request->session()->put('order_result', json_encode($result));

        return "OK*Success";

        // $export = new PackageExport($import_result);
        // return Excel::download($export, $result);
    }

    public function upload_result()
    {
        $result = json_decode(Session::get('order_result'));
        $filename = $result->filename;
        $file = json_decode(base64_decode($result->result));

        $export = new PackageExport($file);
        return Excel::download($export, $filename);
    }
    
    public function waybill_list(Request $request)
    {
        $hub = DB::table('usershub')
        ->select('hub.hub_id','hub.name')
        ->join('hub', 'usershub.hub_id', '=', 'hub.hub_id')
        ->where('usershub.users_id', Session::get('userid'))->get();
        
        $status = DB::table('status')
        ->select('status_id','name')
        ->where('status_group', 'package')->get();

        $date = "";
        if(isset($request->date))
        {
            $date = $request->date;
        }

        if($request->ajax())
        {
            $data = DB::table('package')
            ->select('package.*', 'origin.name as hub_origin', 'dest.name as hub_dest', 'status.label as status_label', 'status.name as status_name')
            ->join('city', 'package.recipient_city', '=', 'city.name')
            ->join('hubarea', 'city.city_id', '=', 'hubarea.city_id')
            ->join('hub as dest', 'hubarea.hub_id', '=', 'dest.hub_id')
            ->join('hub as origin', 'package.hub_id', '=', 'origin.hub_id')
            ->join('status', 'package.status_id', '=', 'status.status_id')
            ->where('package.created_by', Session::get('username'))
            ->whereDate('package.created_date', $date == "" ? date('Y-m-d'):$date)
            ->get();

            return datatables::of($data)
                ->addColumn('waybill', function($data){
                    return $data->tracking_number;
                })
                ->addColumn('location', function($data){
                    return '';
                })
                ->addColumn('origin_hub', function($data){
                    return $data->hub_origin;
                })
                ->addColumn('destination_hub', function($data){
                    return $data->hub_dest;
                })
                ->addColumn('status', function($data){
                    return '<span class="badge bg-label-'.$data->status_label.'">'.ucwords($data->status_name).'</span>';
                })
                ->addColumn('created_via', function($data){
                    return $data->created_via;
                })
                ->addColumn('action', function($data){
                    return '<a class="btn btn-label-warning" href=""><i class="tf-icons ti ti-eye ti-xs me-1"></i>View</a>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('content.delivery-order.waybill-list', ['hub' => $hub, 'status' => $status, 'date' => $date]);
    }

    public function adjustment()
    {
        return view('content.delivery-order.adjustment');
    }
}