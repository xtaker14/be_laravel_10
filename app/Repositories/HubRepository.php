<?php

namespace App\Repositories;

use App\Interfaces\HubRepositoryInterface;
use App\Models\Hub;
use App\Models\UserHub;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Session;

class HubRepository implements HubRepositoryInterface
{
    public function getAllHub()
    {
        return Hub::all();
    }

    public function getAllHubByRole()
    {
        if (Auth::user()->role->name == 'DEVELOPMENT') {
            return Hub::pluck('name','hub_id');
        } else {
            $user_hub = UserHub::where('users_id', Auth::user()->users_id)->pluck('hub_id','hub_id');
            return Hub::whereIn('hub_id',$user_hub)->pluck('name','hub_id');
        }
    }

    public function dataTableHub()
    {
        return DB::table('hub')
        ->leftJoin('hubarea', 'hub.hub_id', '=', 'hubarea.hub_id')
        ->join('subdistrict', 'hub.subdistrict_id', '=', 'subdistrict.subdistrict_id')
        ->join('district', 'subdistrict.district_id', '=', 'district.district_id')
        ->join('city', 'district.city_id', '=', 'city.city_id')
        ->join('province', 'city.province_id', '=', 'province.province_id')
        ->join(DB::raw("(SELECT hub_id, ROW_NUMBER() OVER (ORDER BY hub_id) AS row_index FROM hub) as sub"), 'hub.hub_id', '=', 'sub.hub_id')
        ->join(DB::raw("(SELECT hub_id, CASE WHEN is_active = 1 THEN 'active' ELSE 'inactive' END AS status FROM hub) as sub2"), 'hub.hub_id', '=', 'sub2.hub_id')
        ->select('sub.row_index', 'hub.hub_id', 'hub.code', 'hub.name', 'hub.street_name', 'city.name as city', 'province.name as province','district.name as district','subdistrict.name as subdistrict', 'hub.is_active', 'hub.total_district', 'sub2.status')
        ->groupBy('hub.hub_id');

    }

    public function getHubById($hubId)
    {
        return Hub::findOrFail($hubId);
    }
    public function deleteHub($hubId)
    {
        Hub::destroy($hubId);
    }
    public function createHub(array $hubDetails)
    {
        return Hub::create($hubDetails);
    }
    public function updateHub($hubId, array $newDetails)
    {
        return Hub::whereId($hubId)->update($newDetails);
    }
    
    public function getHubRegion($hubName)
    {
        return DB::table('hub as a')
        ->select('a.hub_id', 'a.postcode', 'a.coordinate', 'b.name as subdistrict', 'c.name as district', 'd.name as city', 'e.name as province', 'f.name as country')
        ->join('subdistrict as b', 'a.subdistrict_id','=','b.subdistrict_id')
        ->join('district as c', 'b.district_id','=','c.district_id')
        ->join('city as d', 'c.city_id','=','d.city_id')
        ->join('province as e', 'd.province_id','=','e.province_id')
        ->join('country as f', 'e.country_id','=','f.country_id')
        ->where('a.name', $hubName)->first();
    }

    public function getUsersHub()
    {
        return DB::table('usershub')
        ->select('hub.*')
        ->join('hub', 'usershub.hub_id', '=', 'hub.hub_id')
        ->where('usershub.users_id', Session::get('userid'))
        ->get();
    }
}