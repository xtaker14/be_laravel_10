<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Imports\PackageImport;
use App\Models\Package;
use App\Models\PackageuploadHistory;
use DB;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Session;
use Yajra\DataTables\Facades\DataTables;

class DeliveryorderController extends Controller
{
    public function index()
    {
        $hub = DB::table('hub')
        ->select('hub_id','name')
        ->where('organization_id', Session::get('orgid'))->get();
        
        return view('content.delivery-order.request-waybill', ['hub' => $hub]);
    }

    public function upload_reqwaybill(Request $request)
    {
        Excel::import(new PackageImport, $request->file('file'));

        $lastId = PackageuploadHistory::orderBy('upload_id', 'desc')->first();
        
        PackageuploadHistory::where('upload_id', $lastId['upload_id'])
        ->update(['filename' => $request->file('file')->getClientOriginalName()]);
        
        return redirect()->back();
    }

    public function list_upload(Request $request)
    {
        if($request->ajax())
        {
            $data = new PackageuploadHistory;
            $data = $data->where('created_by', Session::get('userid'));
            $data = $data->whereDate('created_date', date('Y-m-d'));
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
                    return $data->user->full_name;
                })
                ->addColumn('action', function($data){
                    return '<a class="btn btn-label-warning" href="'. route('login').'"><i class="tf-icons ti ti-book ti-xs me-1"></i>Print</a>';
                })
                ->make(true);
        }

        return view('request-waybill', compact('data', 'request'));
    }
    
    public function list()
    {
        return view('content.delivery-order.waybill-list');
    }

    public function list_package(Request $request)
    {
        if($request->ajax())
        {
            $data = new Package();
            $data = $data->where('created_by', Session::get('userid'));
            $data = $data->whereDate('created_date', date('Y-m-d'));
            $data = $data->latest();

            return datatables::of($data)
                ->addColumn('waybill', function($data){
                    return $data->tracking_number;
                })
                ->addColumn('location', function($data){
                    return 'location';
                })
                ->addColumn('brand', function($data){
                    return 'brand';
                })
                ->addColumn('origin_hub', function($data){
                    return 'origin hub';
                })
                ->addColumn('destination_hub', function($data){
                    return 'destination_hub';
                })
                ->addColumn('status', function($data){
                    return 'status';
                })
                ->addColumn('created_via', function($data){
                    return $data->created_via;
                })
                ->addColumn('action', function($data){
                    return 'action';
                })
                ->make(true);
        }

        return view('request-waybill', compact('data', 'request'));
    }

    public function adjustment()
    {
        return view('content.delivery-order.adjustment');
    }
}