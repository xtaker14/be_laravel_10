<?php

namespace App\Repositories;

use App\Interfaces\PackageRepositoryInterface;
use App\Models\Package;
use App\Models\PackageHistory;
use App\Models\Status;
use Illuminate\Support\Facades\DB;
use Auth;
use Carbon\Carbon;

class PackageRepository implements PackageRepositoryInterface
{
    public function getAllPackage()
    {
        return Package::all();
    }

    public function dataTablePackage()
    {
        return DB::table('package');
    }

    public function getPackageById($packageId)
    {
        return Package::findOrFail($packageId);
    }

    public function getPackageInformation($trackingNumber)
    {
        $data = [];

        $package = Package::where('tracking_number',$trackingNumber)->first();
        if ($package) {
            $data['waybill'] = $package->tracking_number;
            $data['order_code'] = $package->reference_number;
            $data['order_date'] = Carbon::parse($package->created_date)->format('d/m/Y H:i');
            $data['channel'] = $package->merchant_name;
            $data['brand'] = $package->merchant_name;
            if (isset($package->routingdetails()->first()->routing->code)) {
                $data['delivery_record'] = $package->routingdetails()->first()->routing->code;
                $data['courier'] = $package->routingdetails()->first()->routing->courier->userpartner->user->full_name;
                $data['destination_hub'] = $package->routingdetails()->first()->routing->spot->hub->name;
            } else {
                $data['delivery_record'] = '-';
                $data['courier'] = '-';
                $data['destination_hub'] = '-';
            }
            $data['cod'] = number_format($package->cod_price);
            $data['status_name'] = $package->status->name;
            $data['status_label'] = $package->status->label;
            $data['origin_hub'] = $package->hub->name;

            $delivery_history = [];
            $histories = $package->packagehistories()->orderBy('package_history_id','asc')->get();
            foreach ($histories as $key => $history) {
                $delivery_history[$key]['status'] = $history->status->name;
                $delivery_history[$key]['timestamp'] = Carbon::parse($history->created_date)->format('d/m/Y H:i');
                $delivery_history[$key]['modified_by'] = $history->created_by;
            }
            $data['delivery_history'] = $delivery_history;
        }

        return $data;
    }

    public function getHistoryPackage($packageId)
    {
        return PackageHistory::where('tracking_number',$packageId)->first();
    }

    public function deletePackage($packageId)
    {
        Package::destroy($packageId);
    }

    public function createPackage(array $packageDetails)
    {
        return Package::create($packageDetails);
    }

    public function updatePackage($packageId, array $newDetails)
    {
        return Package::whereId($packageId)->update($newDetails);
    }
    
    public function updateStatusPackage($packageId, $statusCode)
    {
        DB::beginTransaction();

        try {
            $statusId = Status::where('code', $statusCode)->first()->status_id;

            $update = Package::find($packageId);
            if ($update) {
                $update->status_id = $statusId;
                $update->modified_date = Carbon::now();
                $update->modified_by = Auth::user()->full_name;
                if($update->save()){
                    $history = new PackageHistory;
                    $history->package_id = $packageId;
                    $history->status_id = $statusId;
                    $history->created_date = Carbon::now();
                    $history->modified_date = Carbon::now();
                    $history->created_by = Auth::user()->full_name;
                    $history->modified_by = Auth::user()->full_name;
                    if ($history->save()) {
                        DB::commit();

                        return true;
                    } else {
                        DB::rollBack();

                        return false;
                    }
                } else {
                    DB::rollBack();

                    return false;
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
            
            return false;
        }
    }

    public function summaryStatus($origin, array $created)
    {
        $entry_status = Status::where('code', 'entry')->first();
        $rejected_status = Status::where('code', 'rejected')->first();
        $received_status = Status::where('code', 'received')->first();
        $transfer_status = Status::where('code', 'transfer')->first();
        $in_transit_status = Status::where('code', 'in_transit')->first();
        $routing_status = Status::where('code', 'routing')->first();
        $on_delivery_status = Status::where('code', 'ondelivery')->first();
        $undelivered_status = Status::where('code', 'undelivered')->first();
        $delivered_status = Status::where('code', 'delivered')->first();
        $return_status = Status::where('code', 'return')->first();

        $waybill = Package::where(function($q) use($origin, $created){
            if ($origin != "") {
                $q->where('hub_id', $origin);
            }
            if ($created['created_start'] != "All") {
                $q->whereBetween('created_date',[$created['created_start'], $created['created_end']]);
            }
        })
        ->count();

        if ($entry_status) {
            $entry = Package::where('status_id',$entry_status->status_id)
            ->where(function($q) use($origin, $created){
                if ($origin != "") {
                    $q->where('hub_id', $origin);
                }
                if ($created['created_start'] != "All") {
                    $q->whereBetween('created_date',[$created['created_start'], $created['created_end']]);
                }
            })
            ->count();
        } else {
            $entry = 0;
        }

        if ($rejected_status) {
            $rejected = Package::where('status_id',$rejected_status->status_id)
            ->where(function($q) use($origin, $created){
                if ($origin != "") {
                    $q->where('hub_id', $origin);
                }
                if ($created['created_start'] != "All") {
                    $q->whereBetween('created_date',[$created['created_start'], $created['created_end']]);
                }
            })
            ->count();
        } else {
            $rejected = 0;
        }
        
        if ($received_status) {
            $received = Package::where('status_id',$received_status->status_id)
            ->where(function($q) use($origin, $created){
                if ($origin != "") {
                    $q->where('hub_id', $origin);
                }
                if ($created['created_start'] != "All") {
                    $q->whereBetween('created_date',[$created['created_start'], $created['created_end']]);
                }
            })
            ->count();
        } else {
            $received = 0;
        }

        if ($transfer_status) {
            $transfer = Package::where('status_id',$transfer_status->status_id)
            ->where(function($q) use($origin, $created){
                if ($origin != "") {
                    $q->where('hub_id', $origin);
                }
                if ($created['created_start'] != "All") {
                    $q->whereBetween('created_date',[$created['created_start'], $created['created_end']]);
                }
            })
            ->count();
        } else {
            $transfer = 0;
        }

        if ($in_transit_status) {
            $in_transit = Package::where('status_id',$in_transit_status->status_id)
            ->where(function($q) use($origin, $created){
                if ($origin != "") {
                    $q->where('hub_id', $origin);
                }
                if ($created['created_start'] != "All") {
                    $q->whereBetween('created_date',[$created['created_start'], $created['created_end']]);
                }
            })
            ->count();
        } else {
            $in_transit = 0;
        }

        if ($routing_status) {
            $routing = Package::where('status_id',$routing_status->status_id)
            ->where(function($q) use($origin, $created){
                if ($origin != "") {
                    $q->where('hub_id', $origin);
                }
                if ($created['created_start'] != "All") {
                    $q->whereBetween('created_date',[$created['created_start'], $created['created_end']]);
                }
            })
            ->count();
        } else {
            $routing = 0;
        }

        if ($on_delivery_status) {
            $on_delivery = Package::where('status_id',$on_delivery_status->status_id)
            ->where(function($q) use($origin, $created){
                if ($origin != "") {
                    $q->where('hub_id', $origin);
                }
                if ($created['created_start'] != "All") {
                    $q->whereBetween('created_date',[$created['created_start'], $created['created_end']]);
                }
            })
            ->count();
        } else {
            $on_delivery = 0;
        }

        if ($undelivered_status) {
            $undelivered = Package::where('status_id',$undelivered_status->status_id)
            ->where(function($q) use($origin, $created){
                if ($origin != "") {
                    $q->where('hub_id', $origin);
                }
                if ($created['created_start'] != "All") {
                    $q->whereBetween('created_date',[$created['created_start'], $created['created_end']]);
                }
            })
            ->count();
        } else {
            $undelivered = 0;
        }

        if ($delivered_status) {
            $delivered = Package::where('status_id',$delivered_status->status_id)
            ->where(function($q) use($origin, $created){
                if ($origin != "") {
                    $q->where('hub_id', $origin);
                }
                if ($created['created_start'] != "All") {
                    $q->whereBetween('created_date',[$created['created_start'], $created['created_end']]);
                }
            })
            ->count();
        } else {
            $delivered = 0;
        }

        if ($return_status) {
            $return = Package::where('status_id',$return_status->status_id)
            ->where(function($q) use($origin, $created){
                if ($origin != "") {
                    $q->where('hub_id', $origin);
                }
                if ($created['created_start'] != "All") {
                    $q->whereBetween('created_date',[$created['created_start'], $created['created_end']]);
                }
            })
            ->count();
        } else {
            $return = 0;
        }

        $data = [
            'waybill' => $waybill,
            'entry' => $entry,
            'rejected' => $rejected,
            'received' => $received,
            'transfer' => $transfer,
            'in_transit' => $in_transit,
            'routing' => $routing,
            'on_delivery' => $on_delivery,
            'undelivered' => $undelivered,
            'delivered' => $delivered,
            'return' => $return
        ];

        return $data;
    }
}