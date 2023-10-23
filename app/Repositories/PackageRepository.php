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
}