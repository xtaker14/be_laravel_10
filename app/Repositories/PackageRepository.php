<?php

namespace App\Repositories;

use App\Interfaces\PackageRepositoryInterface;
use App\Models\Package;
use App\Models\PackageHistory;
use App\Models\Status;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
            if (strtoupper($package->status->name) == 'DELIVERED') {
                $data['pod_signature'] = Storage::disk('s3')->temporaryUrl($package->packagedelivery->e_signature, Carbon::now()->addMinutes(15));
                $data['pod_photo'] = Storage::disk('s3')->temporaryUrl($package->packagedelivery->photo, Carbon::now()->addMinutes(15));
            } else {
                $data['pod_signature'] = "";
                $data['pod_photo'] = "";
            }

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

    public function reportWaybillTransaction(array $filter)
    {
        return DB::table('package')
        ->join('servicetype', 'package.service_type_id', '=', 'servicetype.service_type_id')
        ->join('hub', 'package.hub_id', '=', 'hub.hub_id')
        ->join('status', 'package.status_id', '=', 'status.status_id')
        ->leftJoin('packagedelivery', 'package.package_id', '=', 'packagedelivery.package_id')
        ->select(
            'package.package_id as master_waybill_id',
            'package.tracking_number as waybill_number', 
            'package.reference_number as reference_number', 
            'servicetype.name as service_type', 
            DB::raw('"-" as package_type'), 
            'package.total_koli as total_koli', 
            'package.total_weight as total_weight', 
            'package.notes as package_description', 
            DB::raw("'-' as package_instruction"), 
            'package.is_insurance as with_insurance', 
            DB::raw("'0' as insurance_amount"), 
            'package.package_price as package_value', 
            DB::raw("'0' as package_insurance"), 
            'package.pickup_name as sender_name', 
            'hub.name as hub_pickup', 
            'package.pickup_address as sender_address', 
            'package.pickup_postal_code as sender_postal_code', 
            'package.pickup_phone as sender_phone', 
            DB::raw("'-' as sender_fax"), 
            'package.pickup_email as sender_email', 
            DB::raw("'-' as sender_pic"), 
            'package.recipient_name as recepient_name', 
            'package.recipient_address as recepient_address', 
            'package.recipient_postal_code as recepient_postal_code', 
            'package.recipient_phone as recepient_phone', 
            DB::raw("'-' as recepient_fax"), 
            'package.recipient_email as recepient_email', 
            DB::raw("'-' as recepient_pic"), 
            DB::raw("(CASE WHEN package.cod_price > 0 THEN 'COD' ELSE 'NON COD' END) as payment_type"), 
            'package.cod_price as cod_amount', 
            'package.recipient_city as destination_city', 
            'package.recipient_district as destination_district', 
            DB::raw("'-' as destination_subdistrict"), 
            'status.name as last_status', 
            'package.created_by as created_by', 
            DB::raw("(CASE WHEN packagedelivery.photo <> '' THEN packagedelivery.photo ELSE '' END) as pod_photo"),
            DB::raw("(CASE WHEN packagedelivery.e_signature <> '' THEN packagedelivery.e_signature ELSE '' END) as pod_sign")
        )
        ->where(function($q) use($filter){
            if (isset($filter['date']) && $filter['date'] != "") {
                $q->whereDate('package.created_date',$filter['date']);
            }
            if (isset($filter['hub']) && $filter['hub'] != "") {
                $q->where('package.hub_id',$filter['hub']);
            }
            if (isset($filter['status']) && $filter['status'] != "") {
                $q->where('package.status_id',$filter['status']);
            }
            if (isset($filter['payment'])) {
                if ($filter['payment'] == 'cod') {
                    $q->where('package.cod_price','>',0);
                } else {
                    $q->where('package.cod_price','<=',0);
                }
            }
        })
        ->orderBy('package.package_id','asc');
    }

    public function reportWaybillHistory(array $filter)
    {
        $status = Status::pluck('status_id','code');

        return DB::table('package')
        ->join('status', 'package.status_id', '=', 'status.status_id')
        ->leftJoin('packagehistory as delivered', function($join) use($status) {
            $join->on('delivered.package_id', '=', 'package.package_id');
            $join->where('delivered.status_id', isset($status['DELIVERED']) ? $status['DELIVERED'] : 0);
        })
        ->leftJoin('packagehistory as rejected', function($join) use($status) {
            $join->on('rejected.package_id', '=', 'package.package_id');
            $join->where('rejected.status_id', isset($status['REJECTED']) ? $status['REJECTED'] : 0);
        })
        ->leftJoin('packagehistory as received', function($join) use($status) {
            $join->on('received.package_id', '=', 'package.package_id');
            $join->where('received.status_id', isset($status['RECEIVED']) ? $status['RECEIVED'] : 0);
        })
        ->leftJoin('packagehistory as transfer', function($join) use($status) {
            $join->on('transfer.package_id', '=', 'package.package_id');
            $join->where('transfer.status_id', isset($status['TRANSFER']) ? $status['TRANSFER'] : 0);
        })
        ->leftJoin('packagehistory as intransit', function($join) use($status) {
            $join->on('intransit.package_id', '=', 'package.package_id');
            $join->where('intransit.status_id', isset($status['INTRANSIT']) ? $status['INTRANSIT'] : 0);
        })
        ->leftJoin('packagehistory as routing', function($join) use($status) {
            $join->on('routing.package_id', '=', 'package.package_id');
            $join->where('routing.status_id', isset($status['ROUTING']) ? $status['ROUTING'] : 0);
        })
        ->leftJoin('packagehistory as ondelivery', function($join) use($status) {
            $join->on('ondelivery.package_id', '=', 'package.package_id');
            $join->where('ondelivery.status_id', isset($status['ONDELIVERY']) ? $status['ONDELIVERY'] : 0);
        })
        ->leftJoin('packagehistory as undelivered', function($join) use($status) {
            $join->on('undelivered.package_id', '=', 'package.package_id');
            $join->where('undelivered.status_id', isset($status['UNDELIVERED']) ? $status['UNDELIVERED'] : 0);
        })
        ->leftJoin('packagehistory as return', function($join) use($status) {
            $join->on('return.package_id', '=', 'package.package_id');
            $join->where('return.status_id', isset($status['RETURN']) ? $status['RETURN'] : 0);
        })
        ->select(
            'package.tracking_number as waybill_number',
            'package.reference_number as reference_number',
            'status.name as last_status',
            'package.created_date as created_date',
            'package.created_by as created_by',
            'package.created_by as created_by',
            'package.created_by as created_by',
            'rejected.created_date as rejected_date',
            'rejected.modified_date as rejected_by',
            'received.created_date as received_date',
            'received.modified_date as received_by',
            'transfer.created_date as transfer_date',
            'transfer.modified_date as transfer_by',
            'intransit.created_date as intransit_date',
            'intransit.modified_date as intransit_by',
            'routing.created_date as routing_date',
            'routing.modified_date as routing_by',
            'ondelivery.created_date as ondelivery_date',
            'ondelivery.modified_date as ondelivery_by',
            'delivered.created_date as delivered_date',
            'delivered.modified_date as delivered_by',
            'undelivered.created_date as undelivered_date',
            'undelivered.modified_date as undelivered_by',
            'return.created_date as return_date',
            'return.modified_date as return_by'
        )
        ->where(function($q) use($filter){
            if (isset($filter['date']) && $filter['date'] != "") {
                $q->whereDate('package.created_date',$filter['date']);
            }
            if (isset($filter['hub']) && $filter['hub'] != "") {
                $q->where('package.hub_id',$filter['hub']);
            }
            if (isset($filter['status']) && $filter['status'] != "") {
                $q->where('package.status_id',$filter['status']);
            }
            if (isset($filter['payment'])) {
                if ($filter['payment'] == 'cod') {
                    $q->where('package.cod_price','>',0);
                } else {
                    $q->where('package.cod_price','<=',0);
                }
            }
        })
        ->orderBy('package.package_id', 'asc')
        ->groupBy('package.package_id');
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
        $in_transit_status = Status::where('code', 'intransit')->first();
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