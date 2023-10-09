<?php

namespace App\Repositories;

use App\Interfaces\CourierRepositoryInterface;
use App\Models\Courier;
use Illuminate\Support\Facades\DB;

class CourierRepository implements CourierRepositoryInterface
{
    public function getAllCourier()
    {
        return Courier::all();
    }

    public function dataTableCourier()
    {
        return DB::table('courier')
        ->join('hub', 'courier.hub_id', '=', 'hub.hub_id')
        ->join('partner', 'courier.partner_id', '=', 'partner.partner_id')
        ->join(DB::raw("(SELECT courier_id, ROW_NUMBER() OVER (ORDER BY courier_id) AS row_index FROM courier) as sub"), 'courier.courier_id', '=', 'sub.courier_id')
        ->select('sub.row_index', 'courier.courier_id', 'courier.name', 'courier.code as code','courier.vehicle_type as vehicle_type','courier.vehicle_number as vehicle_number','courier.phone as phone', 'partner.name as vendor_name', 'hub.name as hub_name');
    }

    public function getCourierById($courierId)
    {
        return Courier::findOrFail($courierId);
    }

    public function deleteCourier($courierId)
    {
        Courier::destroy($courierId);
    }

    public function createCourier(array $courierDetails)
    {
        return Courier::create($courierDetails);
    }

    public function updateCourier($courierId, array $newDetails)
    {
        return Courier::whereId($courierId)->update($newDetails);
    }
    
}