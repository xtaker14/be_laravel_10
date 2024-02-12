<?php

namespace App\Http\Middleware;

use Closure; 
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

use App\Helpers\ResponseFormatter;

use App\Services\UserService;

class JwtMiddleware extends BaseMiddleware
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    
    public function handle($request, Closure $next)
    {  
        $checkToken = $this->userService->checkToken();

        $res = new ResponseFormatter;
        if ($checkToken['res'] == 'success') {
            return $next($request);
        } else {
            return $res::error($checkToken['status_code'], $checkToken['msg'], $res::traceCode($checkToken['trace_code']));
        } 

    }
}
