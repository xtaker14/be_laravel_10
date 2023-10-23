<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use PDF;

use App\Helpers\Main;
use App\Helpers\ResponseFormatter;

class TestController extends Controller
{
    public function withoutToken(Request $request)
    {
        $res = new ResponseFormatter;
        return $res::success(__('messages.success'));
    }

    public function tokenAndJsonOnly(Request $request)
    {
        $res = new ResponseFormatter;
        return $res::success('middleware application/json success');
    }

    public function tokenAndRoleInRoute(Request $request)
    {  
        $res = new ResponseFormatter;
        return $res::success(__('messages.success'),[
            'id'=>1,
            'name'=>'test',
        ]); 
    }

    public function tokenAndRoleInController(Request $request)
    { 
        $user = Auth::user(); 
        $res = new ResponseFormatter;

        // $user->hasRole('admin') // cek role

        // $user->hasPermissionTo('view-test') // tidak ada argumen logic semisal handling method http req

        // $post = new \stdClass();
        // $post->id = 1;
        // $post->title = "test title";

        // $post = User::find(1);
        // $user->can('view-test', ['post' => $post]) // ada argumen tambahan untuk logic method http req

        if ($user->hasRole('super-admin') && $user->can('all')) {
            return $res::success(__('messages.success'),[
                'id'=>1,
                'name'=>'test',
            ]); 
        } else {
            return $res::error(403, __('messages.not_have_role_or_permissions'));
        }
    }

    public function aws3(Request $request)
    { 
        $validator = Main::validator($request, [
            'rules'=>[
                'file' => 'required|mimes:jpg,png,jpeg,gif,svg|max:2048',
            ],
        ]);
        
        if (!empty($validator)){
            return $validator;
        }

        $res = new ResponseFormatter;
        $fileName = time().'.'.$request->file->extension();  

        Storage::disk('s3')->put($fileName, file_get_contents($request->file));

        // return $res::success(__('messages.success'),['path' => Storage::disk('s3')->url($fileName)]); 

        $url = Storage::disk('s3')->url($fileName);

        return $res::success(__('messages.success'),['url' => $url]); 
    }

    public function excel(Request $request)
    {
        $validator = Main::validator($request, [
            'rules'=>[
                'file' => 'required|mimes:xlsx,xls,csv|max:2048',
            ],
        ]);
        
        if (!empty($validator)){
            return $validator;
        }

        $res = new ResponseFormatter;
        $array = Excel::toArray(new class implements ToCollection {
            public function collection(Collection $rows)
            {
                return $rows;
            }
        }, $request->file('file'))[0];

        // Menghilangkan header (baris pertama)
        array_shift($array);

        $res_data = [];
        foreach ($array as $row) {
            $res_data[] = [
                'name' => $row[0],  
                'email' => $row[1], 
            ];
        }

        return $res::success(__('messages.success'), $res_data); 
    }

    public function checkRelationTable(Request $request)
    {
        $validator = Main::validator($request, [
            'rules'=>[
                'table' => 'required|string', 
            ],
        ]);
        
        if (!empty($validator)){
            return $validator;
        } 

        $res = new ResponseFormatter;

        try {
            $query = DB::select("
                SELECT 
                    TABLE_NAME,
                    COLUMN_NAME,
                    CONSTRAINT_NAME,
                    REFERENCED_TABLE_NAME,
                    REFERENCED_COLUMN_NAME
                FROM
                    INFORMATION_SCHEMA.KEY_COLUMN_USAGE
                WHERE
                    TABLE_SCHEMA = '".DB::connection()->getDatabaseName()."' AND
                    REFERENCED_TABLE_NAME IS NOT NULL AND
                    TABLE_NAME = '{$request->table}';
            ");

            return $res::success(__('messages.connected'), [
                'db_status' => !empty($query) ? true : false,
                'query' => $query,
            ]);
        } catch (\Exception $e) { 
            return $res::error(500, __('messages.could_not_connect'), [
                'db_status' => false,
                'error' => $e->getMessage(),
            ]); 
        }
    }

    public function generatePdf(Request $request)
    {
        $validator_msg = [
            'string' => __('messages.validator_string'),
            'required' => __('messages.validator_required'),
            'in' => __('messages.validator_in'),
        ];

        $validator = Main::validator($request, [
            'rules' => [
                'platform' => 'required|string|in:desktop,mobile', 
            ],
            'messages' => $validator_msg,
        ]);

        if (!empty($validator)) {
            return $validator;
        } 
        $res = new ResponseFormatter;

        $data = [
            'courier_name' => 'Handani',
        ];

        // $pdf_view = ($request->header('User-Agent') && strpos($request->header('User-Agent'), 'Mobile') !== false) ? 'content._pdf.delivery_record_mobile' : 'content._pdf.delivery_record_desktop';

        $pdf_view = 'content._pdf.delivery_record_mobile';
        if($request->platform == 'desktop'){
            $pdf_view = 'content._pdf.delivery_record_desktop';
        }

        $pdf = PDF::loadView($pdf_view, $data);
        if($request->platform == 'desktop'){
            $pdf->setPaper('A4', 'portrait');
        }elseif($request->platform == 'desktop') {
            $pdf->setPaper('A5', 'portrait');
        }

        // return $res::success(__('messages.success'), $pdf); 
        // return $pdf->download('delivery-record.pdf');
        // return $pdf->stream('delivery-record.pdf');

        // Simpan PDF ke storage 
        $path = storage_path('app/public/pdf/');
        if (!File::exists($path)) {
            File::makeDirectory($path, $mode = 0755, true, true);
        }
        $filename = 'delivery-record-' . $request->platform . '.pdf';
        $pdf->save($path . $filename);

        // Menampilkan preview PDF
        return response()->stream(function () use ($pdf) {
            echo $pdf->stream();
        }, 200, [
            "Content-Type" => "application/pdf",
        ]);
    }

    public function linkPdf(Request $request)
    {
        $validator_msg = [
            'string' => __('messages.validator_string'),
            'required' => __('messages.validator_required'),
            'in' => __('messages.validator_in'),
        ];

        $validator = Main::validator($request, [
            'rules' => [
                'platform' => 'required|string|in:desktop,mobile',
            ],
            'messages' => $validator_msg,
        ]);

        if (!empty($validator)) {
            return $validator;
        }
        $res = new ResponseFormatter;

        $filename = 'delivery-record-' . $request->platform . '.pdf';
        $downloadLink = url('storage/pdf/' . $filename);

        return $res::success(__('messages.connected'), [
            'platform' => $filename,
            'link' => $downloadLink,
        ]);
    }
}