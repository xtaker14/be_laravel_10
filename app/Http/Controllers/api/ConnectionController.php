<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Helpers\ResponseFormatter;

class ConnectionController extends Controller
{
    public function checkConnection()
    {
        $res = new ResponseFormatter;

        try {
            $databaseConnectionCheck = DB::select('SELECT 1');

            return $res::success(__('messages.connected'), [
                'db_status' => !empty($databaseConnectionCheck) ? true : false
            ]);
        } catch (\Exception $e) { 
            return $res::error(500, __('messages.could_not_connect'), [
                'db_status' => false,
                'error' => $e->getMessage(),
            ]); 
        }
    }

    public function healthCheck()
    {
        $res = new ResponseFormatter;

        return $res::success(__('messages.server_is_running'));
    }
}
