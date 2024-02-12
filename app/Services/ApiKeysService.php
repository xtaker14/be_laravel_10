<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use DataTables;

use App\Helpers\Main;

use App\Repositories\ApiKeysRepository;

class ApiKeysService
{
    private ApiKeysRepository $apiKeysRepository;

    public function __construct(ApiKeysRepository $apiKeysRepository)
    {
        $this->apiKeysRepository = $apiKeysRepository;
    }

    public function generateApi()
    {
        DB::beginTransaction();
        try {
            $req_unique_keys = [
                ['column' => 'merchant_id', 'length' => 8],
                ['column' => 'client_key', 'prefix' => 'Client-', 'length' => 12],
                ['column' => 'server_key', 'prefix' => 'Server-', 'length' => 12],
            ];

            $keys = $this->apiKeysRepository->uniqueKeys($req_unique_keys);

            $params = [
                'merchant_id' => $keys['merchant_id'],
                'client_key' => $keys['client_key'],
                'server_key' => $keys['server_key'],
                'is_active' => 1,
            ];
            Main::setCreatedModifiedVal(false, $params);

            $api_key = $this->apiKeysRepository->create($params);

            DB::commit();
            
            return [
                'res' => 'success',
                'status_code' => 200,
                'msg' => __('messages.success'),
                'trace_code' => null,
                'data' => [
                    'api_key' => $api_key,
                ],
            ];
        } catch (\Exception $error) {
            DB::rollBack();
            
            return [
                'res' => 'error',
                'status_code' => 500,
                'msg' => __('messages.something_went_wrong'),
                'trace_code' => 'EXCEPTION014',
            ];
        }
    }
}
