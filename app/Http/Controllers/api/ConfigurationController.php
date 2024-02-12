<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller as ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Helpers\Main;
use App\Helpers\ResponseFormatter;

use App\Services\ApiKeysService;

class ConfigurationController extends ApiController
{
    private ApiKeysService $apiKeysService;
    private $auth;

    public function __construct(ApiKeysService $apiKeysService)
    {
        $this->apiKeysService = $apiKeysService;
        $this->auth = auth('api');
    }

    public function generateApi(Request $request)
    {
        $res = new ResponseFormatter;

        $generateApi = $this->apiKeysService->generateApi();
        
        return $this->resService($res, $generateApi);
    }  
}
