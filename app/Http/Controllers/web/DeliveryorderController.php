<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DeliveryorderController extends Controller
{
    public function index()
    {
        return view('content.delivery-order.request-waybill');
    }

    public function template_reqwaybill()
    {
        
    }

    public function list()
    {
        return view('content.delivery-order.waybill-list');
    }

    public function adjustment()
    {
        return view('content.delivery-order.adjustment');
    }
}