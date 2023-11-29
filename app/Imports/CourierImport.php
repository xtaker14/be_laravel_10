<?php

namespace App\Imports;

use App\Models\Courier;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use App;

class CourierImport implements ToModel, WithHeadingRow, SkipsEmptyRows, WithValidation, SkipsOnFailure
{
    use Importable, RemembersRowNumber, SkipsFailures;

    private $result = [];

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        unset($row['result']);
        $courierRepository = App::make('App\Interfaces\CourierRepositoryInterface');
        $insert = $courierRepository->createCourierImport($row);

        $row['result'] = $insert;

        $currentRowNumber = $this->getRowNumber();
        $this->result[$currentRowNumber] = $row;
    }

    public function rules(): array
    {
        return [
            'code' => 'required|unique:courier|min:5|max:100',
            'name' => 'required|unique:users,full_name|min:5|max:100',
            'hub_id' => function($attribute, $value, $onFailure) {
                $hubRepository = App::make('App\Interfaces\HubRepositoryInterface');
                $check = $hubRepository->getHubByCode($value);

                if (!$check) {
                     $onFailure('HUB ID NOT FOUND');
                }
            },
            'vendor_id' => function($attribute, $value, $onFailure) {
                $vendorRepository = App::make('App\Interfaces\VendorRepositoryInterface');
                $check = $vendorRepository->getVendorByCode($value);

                if (!$check) {
                     $onFailure('VENDOR ID NOT FOUND');
                }
            },
            'phone' => 'required|min_digits:5',
            'vehicle_type' => 'required',
            'vehicle_number' => 'required',
            'username' => 'required|unique:users',
            'gender' => 'required',
            'email' => 'required|unique:users',
        ];
    }

    public function headingRow(): int
    {
        return 1;
    }

    public function result(): array
    {
        return $this->result;
    }
}
