<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Helpers\ResponseFormatter;

class CorsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(empty(env('ALLOWED_DOMAINS'))){
            return $next($request);
        }

        $allowedDomains = explode(',', env('ALLOWED_DOMAINS'));

        $referer = $request->headers->get('referer');
        $origin = $request->headers->get('origin');

        if (!in_array($referer, $allowedDomains) && !in_array($origin, $allowedDomains)) {
            return ResponseFormatter::error(403, __('messages.the_site_isn_allowed'));
        }

        return $next($request);
    }
}
