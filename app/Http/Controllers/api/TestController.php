<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\ToCollection;

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

        $test = [];
        foreach ($array as $row) {
            $test[] = [
                'name' => $row[0],  
                'email' => $row[1], 
            ];
        }

        return $res::success(__('messages.success'), $test); 
    }
}