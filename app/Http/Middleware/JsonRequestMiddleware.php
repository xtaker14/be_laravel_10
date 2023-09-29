<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Helpers\ResponseFormatter;

class JsonRequestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $res = new ResponseFormatter;
        if (!$request->expectsJson()) {
            return $res::error(400, __('messages.json_only'), $res::traceCode('FORMAT001'));
        }

        return $next($request);
    }
}
