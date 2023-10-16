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
        return DB::table('users')
        ->join('role', 'users.role_id', '=', 'role.role_id')
        ->join('courier', 'users.users_id', '=', 'courier.users_id')
        ->join('hub', 'courier.hub_id', '=', 'hub.hub_id')
        ->join('partner', 'courier.partner_id', '=', 'partner.partner_id')
        ->join(DB::raw("(SELECT users_id, ROW_NUMBER() OVER (ORDER BY users_id) AS row_index FROM users) as sub"), 'users.users_id', '=', 'sub.users_id')
        ->join(DB::raw("(SELECT users_id, CASE WHEN is_active = 1 THEN 'active' ELSE 'inactive' END AS status FROM users) as sub2"), 'users.users_id', '=', 'sub2.users_id')
        ->select('sub.row_index', 'courier.courier_id', 'users.full_name', 'users.is_active', 'courier.code as code','courier.vehicle_type as vehicle_type','courier.vehicle_number as vehicle_number','courier.phone as phone', 'partner.name as vendor_name', 'hub.name as hub_name', 'sub2.status')
        ->where('role.name','COURIER');
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