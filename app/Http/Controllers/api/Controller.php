<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller as BaseController;

class Controller extends BaseController
{
    public function resService($res, $params)
    {
        if ($params['res'] == 'error') {
            $resTraceCode = $res::traceCode($params['trace_code']);
            if (!empty($params['data'])) {
                $resTraceCode = $res::traceCode($params['trace_code'], $params['data']);
            }

            return $res::error($params['status_code'], $params['msg'], $resTraceCode);
        } else {
            return $res::success($params['msg'], $params['data'], $params['status_code']);
        }
    }
}
