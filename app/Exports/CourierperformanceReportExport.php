<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CourierperformanceReportExport implements FromQuery, WithHeadings
{
    use Exportable;

    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function query()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'HUB_ID',
            'HUB_NAME',
            'COURIER_NAME',
            'COURIER_ID',
            'DELIVERY_RECORD',
            'STATUS',
            'TOTAL_WAYBILL',
            'TOTAL_KOLI',
            'TOTAL_WEIGHT',
            'PICKUP_DATE',
            'COLLECTED_DATE'
        ];
    }
}
