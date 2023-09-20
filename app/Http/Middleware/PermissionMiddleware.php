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
        if (!Auth::check()) {
            return ResponseFormatter::error(401, __('messages.unauthenticated')); 
        }

        if (!$request->user()->can($permission)) {
            return ResponseFormatter::error(403, __('messages.not_have_permissions')); 
        }

        return $next($request);
    }
}
