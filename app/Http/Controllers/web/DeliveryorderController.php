<?php

namespace App\Http\Controllers\web;

use App\Exports\PackageExport;
use App\Http\Controllers\Controller;
use App\Imports\PackageImport;
use App\Models\Hub;
use App\Models\Package;
use App\Models\PackageuploadHistory;
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
        $request->validate([
            'file' => 'required|max:1000|mimes:xlsx,xls,csv'
        ]);

        $import = new PackageImport;
        Excel::import($import, $request->file('file'));
        
        foreach ($import->failures() as $failure) {
            $failure->row(); // row that went wrong
            $failure->attribute(); // either heading key (if using heading row concern) or column index
            dd($failure->errors()); // Actual error messages from Laravel validator
            $failure->values(); // The values of the row that has failed.
        }

        $last = 1;
        
        $lastId = PackageuploadHistory::orderBy('upload_id', 'desc')->first();
        if($lastId)
        {
            $last = $lastId['upload_id'] + 1;
        }

        $upload['code']          = 'MW'.date('Ymd').$last.rand(100, 1000);
        $upload['total_waybill'] = $import->getRowCount();
        $upload['filename']      = $request->file('file')->getClientOriginalName();
        $upload['created_date']  = date('Y-m-d H:i:s');
        $upload['created_by']    = Session::get('username');

        $history = PackageuploadHistory::create($upload);
        
        $result = "Result - ".$upload['code'].".xlsx";
        $this->upload_result($import->result(), $result);

        return redirect()->back();
    }

    public function upload_result1($data, $filename)
    {
        $export = new PackageExport($data);

        return Excel::download($export, $filename);
    }

    public function upload_result()
    {
        $export = new PackageExport([[10,1], [1,10]]);

        return Excel::download($export, "ABCN.xlsx");
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
            $data = new Package();
            $data = $data->where('created_by', Session::get('username'));
            $data = $data->whereDate('created_date', $date == "" ? date('Y-m-d'):$date);
            $data = $data->latest();

            return datatables::of($data)
                ->addColumn('waybill', function($data){
                    return $data->tracking_number;
                })
                ->addColumn('location', function($data){
                    return $data->hub->subdistrict->name;
                })
                ->addColumn('origin_hub', function($data){
                    return $data->hub->name;
                })
                ->addColumn('status', function($data){
                    return '<span class="badge bg-label-'.$data->status->label.'">'.ucwords($data->status->name).'</span>';
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