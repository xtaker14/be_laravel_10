<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller as ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Helpers\Main;
use App\Helpers\ResponseFormatter;

use App\Services\TestService;

class TestController extends ApiController
{
    private TestService $testService;
    private $auth;

    public function __construct(TestService $testService)
    {
        $this->testService = $testService;
        $this->auth = auth('api');
    }

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
        $uploadAws3 = $this->testService->uploadAws3($request);

        return $this->resService($res, $uploadAws3);
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
        $uploadExcel = $this->testService->uploadExcel($request);

        return $this->resService($res, $uploadExcel);
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

        $checkRelationTable = $this->testService->checkRelationTable($request);

        return $this->resService($res, $checkRelationTable);
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

        $generatePdf = $this->testService->generatePdf($request);

        return response()->stream(function () use ($generatePdf) {
            echo $generatePdf->stream();
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

        $linkPdf = $this->testService->linkPdf($request);
        
        return $this->resService($res, $linkPdf);
    }
}