<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
// use Illuminate\Contracts\Container\BindingResolutionException;
// use Illuminate\Database\Eloquent\MassAssignmentException;
// use Illuminate\Database\QueryException;
// use Illuminate\Http\Exceptions\ThrottleRequestsException;
// use Illuminate\Support\Facades\Log;

// use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

// use InvalidArgumentException;
// use BadMethodCallException;
// use Error;
// use ErrorException;
// use RuntimeException;
// use TypeError;
use Throwable;

use App\Helpers\Main;
use App\Helpers\ResponseFormatter;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        $res = new ResponseFormatter;
        if ($request->expectsJson()) {
            return $res::error(401, __('messages.unauthenticated'), $res::traceCode('AUTH003')); 
        }

        return redirect('api/error/unauthenticated');
    }

    public function render($request, Throwable $exception)
    { 
        $res = new ResponseFormatter;
        
        // add Headers Accept: application/json in request
        if ($request->ajax() || $request->wantsJson()) { 
            
            if (!($exception instanceof \Exception)) {
                return $this->customApiResponse($res, $exception);
            }

            $custom_exception = $this->handleApiException($res, $request, $exception);

            if(empty($custom_exception) || !$custom_exception){
                $parent_render = parent::render($request, $exception);

                if (property_exists($parent_render, 'original')) {
                    return $this->customApiResponse($res, $parent_render);
                } 

                return $parent_render;
            } 

            return $custom_exception;
        } 

        return parent::render($request, $exception);
        
    }

    private function getTrace($exception)
    {
        $status_code = 500;
        $msg = '';
        $trace_code = '';
        $detail_trace = [];

        if (method_exists($exception, 'getStatusCode')) {
            $status_code = $exception->getStatusCode();
        } 

        switch ($status_code) { 
            // case 401:
            //     $msg = __('messages.unauthenticated');
            //     $trace_code = 'EXCEPTION001';
            //     break;
            case 403:
                $msg = __('messages.forbidden');
                $trace_code = 'EXCEPTION002';
                break;
            case 404:
                $msg = __('messages.not_found');
                $trace_code = 'EXCEPTION003';
                break;
            case 405:
                $msg = __('messages.method_not_allowed');
                $trace_code = 'EXCEPTION004';
                break;
            case 422:
                // Unprocessable Content
                $msg = __('messages.unprocessable_content').': '.$exception->original['message'];
                $detail_trace['errors'] = $exception->original['errors'];
                $trace_code = 'EXCEPTION005';
                break;
            case 303:
                $msg = __('messages.see_other');
                $trace_code = 'EXCEPTION006';
                break;
            case 408:
                $msg = __('messages.request_timeout');
                $trace_code = 'EXCEPTION007';
                break;
            case 413:
                $msg = __('messages.content_too_large');
                $trace_code = 'EXCEPTION008';
                break;
            case 502:
                $msg = __('messages.bad_gateway');
                $trace_code = 'EXCEPTION009';
                break;
            case 503:
                $msg = __('messages.service_unavailable');
                $trace_code = 'EXCEPTION010';
                break;
            case 508:
                $msg = __('messages.loop_detected');
                $trace_code = 'EXCEPTION011';
                break;
            case 400:
                $msg = __('messages.bad_request');
                $trace_code = 'EXCEPTION012';
                break;
            case 429:
                $msg = __('messages.too_many_requests');
                $trace_code = 'EXCEPTION013';
                break;

            default:
                $msg = '';
                if($status_code == 500){
                    $msg = __('messages.something_went_wrong');
                }else{
                    if(method_exists($exception, 'getMessage') && !empty($exception->getMessage())){
                        $msg = $exception->getMessage();
                    }
                }
                $trace_code = 'EXCEPTION014';
                break;
        }

        $res = [
            'status_code' => $status_code,
            'msg' => $msg,
            'trace_code' => $trace_code,
            'detail_trace' => $detail_trace,
        ];

        return $res;
    }
    
    private function traceCode($res, $exception, $trace_code, $detail_trace)
    { 
        $res_trace_code = $res::traceCode($trace_code);
        if (config('app.debug')) {
            if(method_exists($exception, 'getCode') && !empty($exception->getCode())){
                $detail_trace['code'] = $exception->getCode();
            }
            if(method_exists($exception, 'getMessage') && !empty($exception->getMessage())){
                $detail_trace['message'] = $exception->getMessage();
            }
            
            $root_exception = [];
            if ($exception instanceof Throwable) {
                $root_exception = Main::getRootException($exception);
            }
            if(!empty($root_exception)){
                $detail_trace['trace'] = $root_exception;
            }else{
                if(method_exists($exception, 'getTrace') && !empty($exception->getTrace())){
                    $detail_trace['trace'] = $exception->getTrace();
                }
            }

            $res_trace_code = $res::traceCode($trace_code, $detail_trace);
        } 
        return $res_trace_code;
    }

    private function handleApiException($res, $request, $exception)
    { 
        $exception = $this->prepareException($exception);

        if ($exception instanceof \Illuminate\Http\Exceptions\HttpResponseException) {
            $exception = $exception->getResponse();
        } 

        if ($exception instanceof AuthenticationException) {
            return $this->unauthenticated($request, $exception);
        }

        if ($exception instanceof \Illuminate\Validation\ValidationException) {
            $exception = $this->convertValidationExceptionToResponse($exception, $request);
        }

        return $this->customApiResponse($res, $exception);
    }

    private function customApiResponse($res, $exception)
    { 
        $trace = $this->getTrace($exception);
        $trace_code = $trace['trace_code']; 
        $status_code = $trace['status_code']; 
        $msg = $trace['msg']; 
        $detail_trace = $trace['detail_trace']; 
        $res_trace_code = $this->traceCode($res, $exception, $trace_code, $detail_trace);

        return $res::error($status_code, $msg, $res_trace_code);
    }
}
