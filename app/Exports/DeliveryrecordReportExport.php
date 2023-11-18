<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DeliveryrecordReportExport implements FromQuery, WithHeadings
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
            'DELIVERY_RECORD',
            'STATUS_DR',
            'WAYBILL_NUMBER',
            'REFERENCE_NUMBER',
            'TOTAL_KOLI',
            'TOTAL_WEIGHT',
            'STATUS_WAYBILL',
            'CRATED_DATE',
            'CREATED_BY',
            'ASSIGNED_TO'
        ];
    }
}
