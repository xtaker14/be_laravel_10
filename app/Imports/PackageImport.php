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
            // dd($collection);
            if($key > 0)
            {
                $serviceType = DB::table('servicetype')->where('name', $row[2])->first();
                $userClient = DB::table('usersclient')->where('users_id', Session::get('userid'))->first();
                
                $hub_dist = DB::table('hub as a')
                ->select('a.hub_id', 'a.postcode', 'a.coordinate', 'b.name as subdistrict', 'c.name as district', 'd.name as city', 'e.name as province', 'f.name as country')
                ->join('subdistrict as b', 'a.subdistrict_id','=','b.subdistrict_id')
                ->join('district as c', 'b.district_id','=','c.district_id')
                ->join('city as d', 'c.city_id','=','d.city_id')
                ->join('province as e', 'd.province_id','=','e.province_id')
                ->join('country as f', 'e.country_id','=','f.country_id')
                ->where('a.name', $row[14])->first();

                $recipient_dist = DB::table('district as a')
                ->select('a.name as district', 'b.name as city', 'c.name as province', 'd.name as country')
                ->join('city as b', 'a.city_id','=','b.city_id')
                ->join('province as c', 'b.province_id','=','c.province_id')
                ->join('country as d', 'c.country_id','=','d.country_id')
                ->where('a.name', $row[31])->first();

                $lastId = Package::orderBy('package_id', 'desc')->first();

                $last = $lastId['package_id'] + 1;

                $data['hub_id']                = $hub_dist->hub_id;
                $data['client_id']             = $userClient->client_id;
                $data['service_type_id']       = $serviceType->service_type_id;
                $data['tracking_number']       = "DTX00".$serviceType->service_type_id.$last.rand(100, 1000);
                $data['reference_number']      = $row[1];
                $data['request_pickup_date']   = date('Y-m-d H:i:s');
                $data['merchant_name']         = $row[13]; //check
                $data['pickup_name']           = $row[13];
                $data['pickup_phone']          = $row[17];
                $data['pickup_email']          = $row[19];
                $data['pickup_address']        = $row[15];
                $data['pickup_country']        = $hub_dist->country;
                $data['pickup_province']       = $hub_dist->province;
                $data['pickup_city']           = $hub_dist->city; 
                $data['pickup_district']       = $hub_dist->district;
                $data['pickup_subdistrict']    = $hub_dist->subdistrict;
                $data['pickup_postal_code']    = $hub_dist->postcode;
                $data['pickup_notes']          = ""; 
                $data['pickup_coordinate']     = $hub_dist->coordinate;
                $data['recipient_name']        = $row[21];
                $data['recipient_phone']       = $row[24];
                $data['recipient_email']       = $row[26];
                $data['recipient_address']     = $row[22];
                $data['recipient_country']     = $recipient_dist->country;
                $data['recipient_province']    = $recipient_dist->province;
                $data['recipient_city']        = $recipient_dist->city;
                $data['recipient_district']    = $recipient_dist->district;
                $data['recipient_postal_code'] = $row[23];
                $data['recipient_notes']       = "";
                $data['recipient_coordinate']  = "";
                $data['package_price']         = $row[11];
                $data['is_insurance']          = $row[9] == "YES" ? 1:0;
                $data['shipping_price']        = 1;
                $data['cod_price']             = $row[9] == "YES" ? $row[29]:0;
                $data['total_weight']          = $row[5];
                $data['total_koli']            = $row[4];
                $data['volumetric']            = $row[6];
                $data['notes']                 = $row[8];
                $data['created_via']           = "IMPORT";
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
