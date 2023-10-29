<?php

namespace App\Imports;

use App\Models\District;
use App\Models\Hub;
use App\Models\Package;
use App\Models\PackageuploadHistory;
use App\Models\ServiceType;
use App\Models\Status;
use App\Models\UserClient;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\ValidationException;
use Session;

class PackageImport implements ToModel, WithStartRow, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use Importable, SkipsFailures;
    
    private $result = [];
    private $rows = 0;
   
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public function rules(): array
    {
        return [
            'reference_number'      => 'required',
            'service_type'          => 'required',
            'package_type'          => 'required',
            'total_koli'            => 'required',
            'total_weight'          => 'required',
            'with_insurance'        => 'required',
            'sender_name'           => 'required',
            'hub_pickup'            => 'required',
            'recipient_name'        => 'required',
            'recipient_address'     => 'required',
            'recipient_postal_code' => 'required',
            'recipient_phone'       => 'required',
            'payment_type'          => 'required',
            'destination_city'      => 'required',
            'destination_district'  => 'required'
        ];
    }

    public function startRow(): int
    {
        return 2;
    }
    
    public function model(array $row)
    {
        $result[] = $row;
        
        $result[0]['waybill'] = "";
        
        $serviceType = ServiceType::where('name', $row['service_type'])->first();
        if(!$serviceType)
        {
            $result[0]['result'] = "Service Type Not Found";
        }

        $userClient = UserClient::where('users_id', Session::get('userid'))->first();
        if(!$userClient)
        {
            $result[0]['result'] = "User Not Found";
        }
        
        $hub = Hub::where('name', $row['hub_pickup'])->first();
        if(!$hub)
        {
            $result[0]['result'] = "Hub Pickup Not Found";
        }

        $recipient = District::where('name', $row['destination_district'])->first();
        if(!$recipient)
        {
            $result[0]['result'] = "Destination Not Found";
        }

        if($row['payment_type'] == "cod" && $row['cod_amount'] < 1)
        {
            $result[0]['result'] = "COD amount must be more than 0";
        }

        $last = 1;
        $lastId = Package::orderBy('package_id', 'desc')->first();
        if($lastId)
        {
            $last = $lastId['package_id'] + 1;
        }
 
        if(!isset($result[0]['result']))
        {
            $package = [
                'hub_id'                => $hub->hub_id,
                'status_id'             => Status::where('code', 'ENTRY')->first()->status_id,
                'client_id'             => $userClient->client_id,
                'service_type_id'       => $serviceType->service_type_id,
                'tracking_number'       => "DTX00".$serviceType->service_type_id.$last.rand(100, 1000),
                'reference_number'      => $row['reference_number'],
                'request_pickup_date'   => Carbon::now(),
                'merchant_name'         => $row['sender_name'], //check
                'pickup_name'           => $row['sender_name'],
                'pickup_phone'          => $row['sender_phone'],
                'pickup_email'          => $row['sender_email'],
                'pickup_address'        => $row['sender_address'],
                'pickup_country'        => $hub->subdistrict->district->city->province->country->name,
                'pickup_province'       => $hub->subdistrict->district->city->province->name,
                'pickup_city'           => $hub->subdistrict->district->city->name, 
                'pickup_district'       => $hub->subdistrict->district->name,
                'pickup_subdistrict'    => $hub->subdistrict->name,
                'pickup_postal_code'    => $hub->postcode,
                'pickup_notes'          => "", 
                'pickup_coordinate'     => $hub->coordinate,
                'recipient_name'        => $row['recipient_name'],
                'recipient_phone'       => $row['recipient_phone'],
                'recipient_email'       => $row['recipient_email'],
                'recipient_address'     => $row['recipient_address'],
                'recipient_country'     => $recipient->city->province->country->name,
                'recipient_province'    => $recipient->city->province->name,
                'recipient_city'        => $recipient->city->name,
                'recipient_district'    => $recipient->name,
                'recipient_postal_code' => $row['recipient_postal_code'],
                'recipient_notes'       => "",
                'recipient_coordinate'  => "",
                'package_price'         => $row['package_value'],
                'is_insurance'          => $row['with_insurance'] == "YES" ? 1:0,
                'shipping_price'        => 1,
                'cod_price'             => $row['cod_amount'],
                'total_weight'          => $row['total_weight'],
                'total_koli'            => $row['total_koli'],
                'volumetric'            => $row['total_volume'] != "" ? $row['total_volume']:1,
                'notes'                 => $row['package_instruction'],
                'created_via'           => "IMPORT",
                'created_date'          => Carbon::now(),
                'modified_date'         => Carbon::now(),
                'created_by'            => Session::get('username'),
                'modified_by'           => Session::get('username')
            ];
            $create = Package::create($package);

            $result[0]['waybill'] = $package['tracking_number'];
            $result[0]['result'] = "SUCCESS";
        }
        ++$this->rows;

        $this->result[] = $result;
        return;
    }

    public function getRowCount(): int
    {
        return $this->rows;
    }

    public function result(): array
    {
        return $this->result;
    }
}
