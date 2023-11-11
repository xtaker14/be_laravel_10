<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class InboundDetailExport implements FromQuery, WithHeadings
{
    use Exportable;

    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function query()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'HUB_ID',
            'HUB_NAME',
            'INBOUND_ID',
            'INBOUND_TYPE',
            'M-BAG_CODE',
            'DELIVERY_RECORD',
            'WAYBILL_NUMBER',
            'REFERENCE_NUMBER',
            'TOTAL_KOLI',
            'TOTAL_WEIGHT',
            'RECEIVED_DATE',
            'FINISH_DATE',
            'RECEIVED_BY'
        ];
    }
}
