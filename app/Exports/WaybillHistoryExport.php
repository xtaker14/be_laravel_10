<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class WaybillHistoryExport implements FromQuery, WithHeadings
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
            'WAYBILL_NUMBER',
            'REFERENCE_NUMBER',
            'LAST_STATUS',
            'CREATED_DATE',
            'CREATED_BY',
            'REJECTED_DATE',
            'REJECTED_BY',
            'RECEIVED_DATE',
            'RECEIVED_BY',
            'TRANSFER_DATE',
            'TRANSFER_BY',
            'IN_TRANSIT_DATE',
            'IN_TRANSIT_BY',
            'ROUTING_DATE',
            'ROUTING_BY',
            'ON_DELIVERY_DATE',
            'ON_DELIVERY_BY',
            'DELIVERED_DATE',
            'DELIVERED_BY',
            'UNDELIVERED_DATE',
            'UNDELIVERED_BY',
            'RETURN_DATE',
            'RETURN_BY'
        ];
    }
}
