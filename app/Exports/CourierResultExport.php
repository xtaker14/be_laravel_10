<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CourierResultExport implements FromArray, WithHeadings
{
    protected $result;

    public function __construct(array $result)
    {
        $this->result = $result;
    }

    public function array(): array
    {
        return $this->result;
    }

    public function headings(): array
    {
        return [
            'CODE',
            'NAME',
            'HUB ID',
            'VENDOR ID',
            'PHONE',
            'VEHICLE TYPE',
            'VEHICLE NUMBER',
            'USERNAME',
            'GENDER',
            'EMAIL',
            'RESULT'
        ];
    }
}
