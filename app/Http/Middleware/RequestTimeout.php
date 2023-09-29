<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;

use App\Helpers\ResponseFormatter;

class RequestTimeout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Waktu maksimum yang diijinkan (dalam detik)
        $timeoutSeconds = env('REQUEST_TIMEOUT', 60);

        $startDatetime = Carbon::now();

        // Mulai timer
        $start = microtime(true);
        
        // Terapkan respons pada variabel
        $response = $next($request);

        $endDatetime = Carbon::now();

        // Hitung durasi eksekusi
        $duration = microtime(true) - $start;
        
        // Jika durasi melebihi batas waktu, kirim respons timeout
        if ($duration > $timeoutSeconds) {
            return response()->json([
                'error' => 'Request Timeout',
                'start_datetime' => $startDatetime->toDateTimeString(),
                'end_datetime' => $endDatetime->toDateTimeString(),
            ], 408); // 408 adalah kode status untuk Request Timeout
        }
        
        return $response;
    }
}
