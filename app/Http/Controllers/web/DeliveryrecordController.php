<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
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
        
        return view('content.delivery-record.create', ['hub' => $hub]);
    }

    public function update()
    {        
        return view('content.delivery-record.update');
    }
}