<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $response = Http::get('https://emsifa.github.io/api-wilayah-indonesia/api/provinces.json');
        if ($response->successful()) {
            $provinces = $response->json();
            DB::table('province')->where('name','!=','DKI JAKARTA')->delete();
            DB::table('city')->where('province_id','!=',1)->delete();
            DB::table('district')->whereNotIn('city_id',[1,2])->delete();
            DB::table('subdistrict')->whereNotIn('district_id',[1,2])->delete();
            foreach ($provinces as $key => $province) {
                $name_province = $province['name'];

                if (!in_array($name_province,['DKI JAKARTA'])) {
                    DB::table('province')->insert([
                        'province_id' => $province['id'],
                        'country_id' => 1,
                        'name' => $name_province,
                        'is_active' => 1,
                        'created_date' => now(),
                        'modified_date' => now(),
                        'created_by' => 'system',
                        'modified_by' => 'system'
                    ]);
                }

                if (in_array($name_province,['JAWA BARAT','JAWA TENGAH','JAWA TIMUR'])) {
                    $response2 = Http::get('https://emsifa.github.io/api-wilayah-indonesia/api/regencies/'.$province['id'].'.json');
                    if ($response2->successful()) {
                        $regencies = $response2->json();
                        foreach ($regencies as $key2 => $regencie) {
                            DB::table('city')->insert([
                                'city_id' => $regencie['id'],
                                'province_id' => $province['id'],
                                'name' => $regencie['name'],
                                'is_active' => 1,
                                'created_date' => now(),
                                'modified_date' => now(),
                                'created_by' => 'system',
                                'modified_by' => 'system'
                            ]);


                            $response3 = Http::get('https://emsifa.github.io/api-wilayah-indonesia/api/districts/'.$regencie['id'].'.json');
                            if ($response3->successful()) {
                                $districts = $response3->json();

                                foreach ($districts as $key3 => $district) {
                                    DB::table('district')->insert([
                                        'district_id' => $district['id'],
                                        'city_id' => $regencie['id'],
                                        'name' => $district['name'],
                                        'is_active' => 1,
                                        'created_date' => now(),
                                        'modified_date' => now(),
                                        'created_by' => 'system',
                                        'modified_by' => 'system'
                                    ]);

                                    $response4 = Http::get('https://emsifa.github.io/api-wilayah-indonesia/api/villages/'.$district['id'].'.json');
                                    if ($response4->successful()) {
                                        $villages = $response4->json();

                                        foreach ($villages as $key4 => $village) {
                                            DB::table('subdistrict')->insert([
                                                'subdistrict_id' => $village['id'],
                                                'district_id' => $district['id'],
                                                'name' => $village['name'],
                                                'is_active' => 1,
                                                'created_date' => now(),
                                                'modified_date' => now(),
                                                'created_by' => 'system',
                                                'modified_by' => 'system'
                                            ]);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        
    }
}
