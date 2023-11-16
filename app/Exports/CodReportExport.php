<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CodReportExport implements FromQuery, WithHeadings
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
            'COLLECTION_ID',
            'COURIER_NAME',
            'COURIER_ID',
            'DELIVERY_RECORD',
            'COLLECTION_STATUS',
            'TOTAL_WAYBILL',
            'TOTAL_WAYBILL_COD',
            'TOTAL_WAYBILL_NON_COD',
            'TOTAL_COD_DELIVERED',
            'TOTAL_COD_UNDELIVERED',
            'TOTAL_COD_AMOUNT',
            'TOTAL_COD_COLLECTED',
            'COLLECTED_DATE',
            'COLLECTED_BY'
        ];
    }
}
