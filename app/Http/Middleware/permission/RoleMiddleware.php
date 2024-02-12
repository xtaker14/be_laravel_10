<?php

namespace App\Http\Middleware\permission;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

use App\Helpers\ResponseFormatter;

use App\Services\UserService;

class RoleMiddleware
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role, $guard = 'api', $act = 'before'): Response
    { 
        if($act == 'before'){
            $res = new ResponseFormatter; 
            
            if (!auth($guard)->check()) {
                return $res::error(401, __('messages.unauthenticated'), $res::traceCode('AUTH003')); 
            }

            if (!auth($guard)->user()->hasRole($role)) {
                return $res::error(403, __('messages.not_have_role'), $res::traceCode('ROLE002')); 
            }
        }
        
        $response = $next($request);

        if($act == 'after'){
            if (auth($guard)->check() && !auth($guard)->user()->hasRole($role)) {
                $res = new ResponseFormatter;
 
                $logout_service = $this->userService->logout($request, auth($guard));
                
                return $res::error(403, __('messages.not_have_role'), $res::traceCode('ROLE002'));
            }
        }

        return $response;
    } 
}
