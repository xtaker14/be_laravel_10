<?php

namespace App\Imports;

use App\Models\Package;
use App\Models\PackageuploadHistory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Session;

class PackageImport implements ToCollection
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $collection)
    {
        $no = 0;
        foreach($collection as $key => $row)
        {
            if($key > 0)
            {
                $hub = DB::table('hub')->where('name', $row[13])->first();
                $serviceType = DB::table('servicetype')->where('name', $row[2])->first();

                $data['hub_id']                = $hub->hub_id;
                $data['client_id']             = 1;
                $data['service_type_id']       = $serviceType->service_type_id;
                $data['tracking_number']       = "DTX00".$serviceType->service_type_id.rand(100, 1000);
                $data['reference_number']      = $row[1];
                $data['request_pickup_date']   = date('Y-m-d H:i:s');
                $data['merchant_name']         = $row[12]; //check
                $data['pickup_name']           = $row[12];
                $data['pickup_phone']          = $row[16];
                $data['pickup_email']          = $row[18];
                $data['pickup_address']        = $row[14];
                $data['pickup_country']        = "INDONESIA";
                $data['pickup_province']       = $row[1]; //check
                $data['pickup_city']           = $row[1]; //check 
                $data['pickup_district']       = $row[1]; //check
                $data['pickup_subdistrict']    = $row[1]; //check
                $data['pickup_postal_code']    = $row[1]; //check
                $data['pickup_notes']          = $row[7]; 
                $data['pickup_coordinate']     = $row[1]; //check
                $data['recipient_name']        = $row[20];
                $data['recipient_phone']       = $row[23];
                $data['recipient_email']       = $row[25];
                $data['recipient_address']     = $row[21];
                $data['recipient_country']     = "INDONESIA";
                $data['recipient_province']    = $row[1]; //check
                $data['recipient_city']        = $row[1]; //check
                $data['recipient_district']    = $row[1]; //check
                $data['recipient_postal_code'] = $row[1]; //check
                $data['recipient_notes']       = $row[1]; //check
                $data['recipient_coordinate']  = $row[1]; //check
                $data['package_price']         = $row[10];
                $data['is_insurance']          = $row[8] == "YES" ? 1:0;
                $data['shipping_price']        = 10000; //check
                $data['cod_price']             = $row[8] == "YES" ? $row[29]:0;
                $data['total_weight']          = $row[5];
                $data['total_koli']            = $row[4];
                $data['volumetric']            = 1; //check
                $data['notes']                 = $row[7];
                $data['created_via']           = "UPLOAD";
                $data['created_date']          = date('Y-m-d H:i:s');
                $data['modified_date']         = date('Y-m-d H:i:s');
                $data['created_by']            = Session::get('userid');
                $data['modified_by']           = Session::get('userid');

                Package::create($data);
                
                $no++;
            }
        }

        $lastId = PackageuploadHistory::orderBy('upload_id', 'desc')->first();

        $last = $lastId['upload_id'] + 1;

        $upload['code']          = 'MW'.date('Ymd').$last.rand(100, 1000);
        $upload['total_waybill'] = $no;
        $upload['filename']      = "file";
        $upload['created_date']  = date('Y-m-d H:i:s');
        $upload['created_by']    = Session::get('userid');

        PackageuploadHistory::create($upload);
    }
}
