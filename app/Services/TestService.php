<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use PDF;
use DataTables;

use App\Helpers\Main;

use App\Repositories\TestRepository;

class TestService
{
    private TestRepository $testRepository;

    public function __construct(TestRepository $testRepository)
    {
        $this->testRepository = $testRepository;
    }

    public function uploadAws3(Request $request)
    {
        $fileName = time() . '.' . $request->file->extension();

        Storage::disk('s3')->put($fileName, file_get_contents($request->file)); 

        $url = Storage::disk('s3')->url($fileName);

        return [
            'res' => 'success',
            'status_code' => 200,
            'msg' => __('messages.success'),
            'trace_code' => null,
            'data' => [
                'url' => $url,
            ],
        ];
    }

    public function uploadExcel(Request $request)
    {
        $array = Excel::toArray(new class implements ToCollection {
            public function collection(Collection $rows)
            {
                return $rows;
            }
        }, $request->file('file'))[0];
        
        array_shift($array);

        $res_data = [];
        foreach ($array as $row) {
            $res_data[] = [
                'name' => $row[0],  
                'email' => $row[1], 
            ];
        }

        return [
            'res' => 'success',
            'status_code' => 200,
            'msg' => __('messages.success'),
            'trace_code' => null,
            'data' => $res_data,
        ];
    }

    public function checkRelationTable(Request $request)
    {
        try {
            $query = $this->testRepository->checkRelationTable($request->table);

            return [
                'res' => 'success',
                'status_code' => 200,
                'msg' => __('messages.connected'),
                'trace_code' => null,
                'data' => [
                    'db_status' => !empty($query) ? true : false,
                    'query' => $query,
                ],
            ];
        } catch (\Exception $e) {
            return [
                'res' => 'error',
                'status_code' => 500,
                'msg' => __('messages.could_not_connect'),
                'trace_code' => 'EXCEPTION014',
                'data' => [
                    'db_status' => false,
                    'error' => $e->getMessage(),
                ],
            ];
        }
    }

    public function generatePdf(Request $request)
    {
        $data = [
            'courier_name' => 'Handani',
        ];

        $pdf_view = 'content._pdf.delivery_record_mobile';
        if ($request->platform == 'desktop') {
            $pdf_view = 'content._pdf.delivery_record_desktop';
        }

        $pdf = PDF::loadView($pdf_view, $data);
        if ($request->platform == 'desktop') {
            $pdf->setPaper('A4', 'portrait');
        } elseif ($request->platform == 'desktop') {
            $pdf->setPaper('A5', 'portrait');
        }

        $path = storage_path('app/public/pdf/');
        if (!File::exists($path)) {
            File::makeDirectory($path, $mode = 0755, true, true);
        }
        $filename = 'delivery-record-' . $request->platform . '.pdf';
        $pdf->save($path . $filename);

        return $pdf->stream();
    }

    public function linkPdf(Request $request)
    {
        $filename = 'delivery-record-' . $request->platform . '.pdf';
        $downloadLink = url('storage/pdf/' . $filename);
        
        return [
            'res' => 'success',
            'status_code' => 200,
            'msg' => __('messages.success'),
            'trace_code' => null,
            'data' => [
                'platform' => $filename,
                'link' => $downloadLink,
            ],
        ];
    }
}
