<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TransferExport implements FromQuery, WithHeadings
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
            'MBAG_ID',
            'MBAG_STATUS',
            'WAYBILL_NUMBER',
            'REFERENCE_NUMBER',
            'DESTINATION_CITY',
            'DESTINATION_DISTRICT',
            'DESTINATION_SUBDISTRICT',
            'TOTAL_KOLI',
            'TOTAL_WEIGHT',
            'TRANSFER_FROM',
            'TRANSFER_TO',
            'TRANSFER_DATE',
            'TRANSFER_BY',
            'IN_TRANSIT_DATE',
            'IN_TRANSIT_BY'
        ];
    }
}
