<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Helpers\ResponseFormatter;

class ConnectionController extends Controller
{
    public function checkConnection()
    {
        try {
            $databaseConnectionCheck = DB::select(DB::raw('SELECT 1'));

            return ResponseFormatter::success(__('messages.connected'), [
                'db_status' => !empty($databaseConnectionCheck) ? true : false
            ]);
        } catch (\Exception $e) { 
            return ResponseFormatter::error(500, __('messages.could_not_connect'), [
                'db_status' => false,
                'error' => $e->getMessage(),
            ]); 
        }
    }
}
