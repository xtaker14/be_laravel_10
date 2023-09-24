<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Deliveryorder extends Controller
{
    public function index()
    {
        return view('content.delivery-order.request-waybill');
    }

    public function list()
    {
        return view('content.delivery-order.list-waybill');
    }

    public function adjustment()
    {
        return view('content.delivery-order.adjustment');
    }
}