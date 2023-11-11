<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\DB;

class WaybillTransactionExport implements FromQuery, WithHeadings, WithMapping
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

    public function map($waybill): array
    {
        $podPhoto = $this->generateLinkS3($waybill->pod_photo);
        $podSign = $this->generateLinkS3($waybill->pod_sign);

        return [
            $waybill->master_waybill_id,
            $waybill->waybill_number,
            $waybill->reference_number,
            $waybill->service_type,
            $waybill->package_type,
            $waybill->total_koli,
            $waybill->total_weight,
            $waybill->package_description,
            $waybill->package_instruction,
            $waybill->with_insurance,
            $waybill->sender_postal_code,
            $waybill->sender_phone,
            $waybill->sender_fax,
            $waybill->sender_email,
            $waybill->sender_pic,
            $waybill->recepient_name,
            $waybill->recepient_address,
            $waybill->recepient_postal_code,
            $waybill->recepient_phone,
            $waybill->recepient_fax,
            $waybill->recepient_email,
            $waybill->recepient_pic,
            $waybill->payment_type,
            $waybill->cod_amount,
            $waybill->destination_city,
            $waybill->destination_district,
            $waybill->destination_subdistrict,
            $waybill->last_status,
            $waybill->created_by,
            $podPhoto,
            $podSign
        ];
    }

    public function headings(): array
    {
        return [
            'MASTER_WAYBILL_ID',
            'WAYBILL_NUMBER',
            'REFERENCE_NUMBER',
            'SERVICE_TYPE',
            'PACKAGE_TYPE',
            'TOTAL_KOLI',
            'TOTAL_WEIGHT',
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
            'RECEPIENT_NAME',
            'RECEPIENT_ADDRESS',
            'RECEPIENT_POSTAL_CODE',
            'RECEPIENT_PHONE',
            'RECEPIENT_FAX',
            'RECEPIENT_EMAIL',
            'RECEPIENT_PIC',
            'PAYMENT_TYPE',
            'COD_AMOUNT',
            'DESTINATION_CITY',
            'DESTINATION_DISTRICT',
            'DESTINATION_SUBDISTRICT',
            'LAST_STATUS',
            'CREATED_BY',
            'POD_PHOTO',
            'POD_SIGN',
        ];
    }

    public function generateLinkS3($value)
    {
        if ($value != "") {
            return route('image-s3', ['path' => $value]);
        } else {
            return '-';
        }
    }
}
