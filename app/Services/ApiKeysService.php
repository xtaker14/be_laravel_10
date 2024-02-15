<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use MongoDB\Exception\BadMethodCallException;
use MongoDB\Exception\InvalidArgumentException;
use Carbon\Carbon;
use DataTables;

use App\Helpers\Main;

use App\Repositories\ApiKeysRepository;
use App\Repositories\CountersRepository;

class ApiKeysService
{
    private ApiKeysRepository $apiKeysRepository;
    private CountersRepository $countersRepository;

    public function __construct(ApiKeysRepository $apiKeysRepository, CountersRepository $countersRepository)
    {
        $this->apiKeysRepository = $apiKeysRepository;
        $this->countersRepository = $countersRepository;
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

            $api_keys_id = $this->countersRepository->incrementCounter('api_keys', 'api_keys_id');

            $params = [
                'api_keys_id' => (int)$api_keys_id,
                'merchant_id' => $keys['merchant_id'],
                'client_key' => $keys['client_key'],
                'server_key' => $keys['server_key'],
                'is_active' => 'yes',
            ];
            Main::setCreatedModifiedVal(false, $params, 'all', true);

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
        } catch (BadMethodCallException $e) {
            DB::rollBack();
            
            return [
                'res' => 'error',
                'status_code' => 500,
                'msg' => __('messages.something_went_wrong'),
                'trace_code' => 'EXCEPTION014',
                'data' => [
                    'trace' => $e->getTraceAsString(),
                    'error' => $e->getMessage(),
                ],
            ];
        }
    }
}
