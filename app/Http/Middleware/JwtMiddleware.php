<?php

namespace App\Http\Middleware;

use Closure; 
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

use App\Helpers\ResponseFormatter;

class JwtMiddleware extends BaseMiddleware
{
    public function handle($request, Closure $next)
    {  
        $JWTService = app(\App\Services\JWTService::class);
        $checkToken = $JWTService->checkToken(); 

        $res = new ResponseFormatter;  
        switch ($checkToken) {
            case 'token_invalid':
                return $res::error(401, __('messages.token_invalid'), $res::traceCode('AUTH004')); 
                break;
            case 'token_expired':
                return $res::error(401, __('messages.token_expired'), $res::traceCode('AUTH005')); 
                break;
            case 'token_not_found':
                return $res::error(404, __('messages.token_not_found'), $res::traceCode('AUTH006'));
                break;
            case 'token_valid':
                return $next($request);
                break;
            
            default:
                return $res::error(401, __('messages.token_invalid'), $res::traceCode('AUTH004')); 
                break;
        } 

    }
}
