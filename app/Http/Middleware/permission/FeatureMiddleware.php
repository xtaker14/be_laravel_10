<?php

namespace App\Http\Middleware\permission;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Helpers\ResponseFormatter;

class FeatureMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $feature, $guard = 'api', $act = 'before'): Response
    {
        if($act == 'before'){
            $res = new ResponseFormatter;  
        
            if (!auth($guard)->check()) {
                return $res::error(401, __('messages.unauthenticated'), $res::traceCode('AUTH003')); 
            }

            if (!auth($guard)->user()->hasFeature($feature)) {
                return $res::error(403, __('messages.not_have_feature'), $res::traceCode('FEATURE002')); 
            }
        }
        
        $response = $next($request);

        if($act == 'after'){
            if (auth($guard)->check() && !auth($guard)->user()->hasFeature($feature)) {
                $res = new ResponseFormatter; 
                
                $AuthService = new \App\Services\AuthService('api');
                $logout_service = $AuthService->logout($request);
                
                return $res::error(403, __('messages.not_have_feature'), $res::traceCode('FEATURE002'));
            }
        }

        return $response; 
    }
}
