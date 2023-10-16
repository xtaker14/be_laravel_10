<?php

namespace App\Imports;

use App\Models\City;
use App\Models\District;
use App\Models\Package;
use App\Models\PackageuploadHistory;
use App\Models\Province;
use App\Models\Subdistrict;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Session;

class RegionImport implements ToCollection
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
                $province = DB::table('province')->where('code', $row[3])->first();
                if(!$province)
                {                    
                    $prov['country_id']    = 1;
                    $prov['code']          = $row[3];
                    $prov['name']          = $row[4];
                    $prov['created_date']  = date('Y-m-d H:i:s');
                    $prov['modified_date'] = date('Y-m-d H:i:s');
                    $prov['created_by']    = "system";
                    $prov['modified_by']   = "system";
    
                    $province = Province::create($prov);
                    $province_id = $province->province_id;
                }
                else
                {
                    $province_id = $province->province_id;
                }

                $city = DB::table('city')->where('code', $row[5])->first();
                if(!$city)
                {
                    $ct['province_id']   = $province_id;
                    $ct['code']          = $row[5];
                    $ct['name']          = $row[6];
                    $ct['created_date']  = date('Y-m-d H:i:s');
                    $ct['modified_date'] = date('Y-m-d H:i:s');
                    $ct['created_by']    = "system";
                    $ct['modified_by']   = "system";

                    $city = City::create($ct);
                    $city_id = $city->city_id;
                }
                else
                {
                    $city_id = $city->city_id;
                }

                $district = DB::table('district')->where('code', $row[7])->first();
                if(!$district)
                {
                    $dc['city_id']       = $city_id;
                    $dc['code']          = $row[7];
                    $dc['name']          = $row[8];
                    $dc['created_date']  = date('Y-m-d H:i:s');
                    $dc['modified_date'] = date('Y-m-d H:i:s');
                    $dc['created_by']    = "system";
                    $dc['modified_by']   = "system";

                    $district = District::create($dc);
                    $district_id = $district->district_id;
                }
                else
                {
                    $district_id = $district->district_id;
                }

                $subdisc = DB::table('subdistrict')->where('code', $row[9])->first();
                if(!$subdisc)
                {
                    $dc['district_id']   = $district_id;
                    $dc['code']          = $row[9];
                    $dc['name']          = $row[10];
                    $dc['created_date']  = date('Y-m-d H:i:s');
                    $dc['modified_date'] = date('Y-m-d H:i:s');
                    $dc['created_by']    = "system";
                    $dc['modified_by']   = "system";

                    $subdisc = Subdistrict::create($dc);
                }
            }
        }
    }
}
