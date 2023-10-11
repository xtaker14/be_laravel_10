<?php

namespace App\Repositories;

use App\Interfaces\VendorRepositoryInterface;
use App\Models\Partner;
use Illuminate\Support\Facades\DB;

class VendorRepository implements VendorRepositoryInterface
{
    public function getAllVendor()
    {
        return Partner::all();
    }

    public function dataTableVendor()
    {
        return DB::table('partner')
        ->join('organization', 'organization.organization_id', '=', 'partner.organization_id')
        ->leftJoin('courier', 'partner.partner_id', '=', 'courier.partner_id')
        ->join(DB::raw("(SELECT partner_id, ROW_NUMBER() OVER (ORDER BY partner_id) AS row_index FROM partner) as sub"), 'partner.partner_id', '=', 'sub.partner_id')
        ->select('sub.row_index', 'partner.partner_id', 'partner.name', 'partner.code', 'organization.name as organization_name', 'partner.email', 'partner.phone_number', 'partner.is_active', DB::raw('COUNT(courier.courier_id) as total_courier'))
        ->groupBy('partner.partner_id');
    }

    public function getVendorById($vendorId)
    {
        return Partner::findOrFail($vendorId);
    }

    public function deleteVendor($vendorId)
    {
        Partner::destroy($vendorId);
    }

    public function createVendor(array $vendorDetails)
    {
        return Partner::create($vendorDetails);
    }

    public function updateVendor($vendorId, array $newDetails)
    {
        return Partner::whereId($vendorId)->update($newDetails);
    }
    
}