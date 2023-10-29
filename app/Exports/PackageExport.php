<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PackageExport implements FromArray, WithHeadings
{
    use Exportable;

    protected $data;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function headings(): array
    {
        return [
            'REFERENCE_NUMBER',
            'SERVICE_TYPE',
            'PACKAGE_TYPE',
            'TOTAL_KOLI',
            'TOTAL_WEIGHT',
            'TOTAL_VOLUME',
            'PACKAGE_DESCRIPTION',
            'PACKAGE_INSTRUCTION',
            'WITH_INSURANCE',
            'INSURANCE_AMOUNT',
            'PACKAGE_VALUE',
            'PACKAGE_INSURANCE',
            'SENDER_NAME',
            'HUB_PICKUP',
            'SENDER_ADDRESS',
            'SENDER_POSTAL_CODE',
            'SENDER_PHONE',
            'SENDER_FAX',
            'SENDER_EMAIL',
            'SENDER_PIC',
            'RECIPIENT_NAME',
            'RECIPIENT_ADDRESS',
            'RECIPIENT_POSTAL_CODE',
            'RECIPIENT_PHONE',
            'RECIPIENT_FAX',
            'RECIPIENT_EMAIL',
            'RECIPIENT_PIC',
            'PAYMENT_TYPE',
            'COD_AMOUNT',
            'DESTINATION_CITY',
            'DESTINATION_DISTRICT',
            'DESTINATION_SUBDISTRICT',
            'WAYBILL_NUMBER',
            'RESULT'
        ];
    }

    public function array(): array
    {
        return $this->data;
    }
}
