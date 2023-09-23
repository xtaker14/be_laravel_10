<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ResponseFormatter;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $permission): Response
    {
        $res = new ResponseFormatter;  

        if (!Auth::check()) {
            return $res::error(401, __('messages.unauthenticated'), $res::traceCode('AUTH003')); 
        }

        if (!$request->user()->can($permission)) {
            return $res::error(403, __('messages.not_have_permissions')); 
        }

        return $next($request);
    }
}
