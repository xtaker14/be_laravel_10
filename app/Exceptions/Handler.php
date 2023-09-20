<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Support\Facades\Log;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use InvalidArgumentException;
use BadMethodCallException;
use Error;
use ErrorException;
use RuntimeException;
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
        if ($request->expectsJson()) {
            return ResponseFormatter::error(401, __('messages.unauthenticated')); 
        }

        return redirect('api/error/unauthenticated');
    }

    public function render($request, Throwable $exception)
    {
        $is_error = false;
        $msg = '';
        $status_code = 500;

        if ($exception instanceof NotFoundHttpException) {
            $is_error = true;
            $msg = !empty($exception->getMessage()) ? $exception->getMessage().' in the NotFoundHttpException' : __('messages.NotFoundHttpException');
            $status_code = 404;
        }

        if ($exception instanceof BindingResolutionException) {
            $is_error = true;
            $msg = !empty($exception->getMessage()) ? $exception->getMessage().' in the BindingResolutionException' : __('messages.BindingResolutionException'); 
            $status_code = 500;
        }

        if ($exception instanceof BadMethodCallException) {
            $is_error = true;
            $msg = !empty($exception->getMessage()) ? $exception->getMessage().' in the BadMethodCallException' : __('messages.BadMethodCallException'); 
            $status_code = 500;
        }

        if ($exception instanceof MassAssignmentException) {
            $is_error = true;
            $msg = !empty($exception->getMessage()) ? $exception->getMessage().' in the MassAssignmentException' : __('messages.MassAssignmentException'); 
            $status_code = 500;
        }

        if ($exception instanceof ThrottleRequestsException) {
            $is_error = true;
            $msg = !empty($exception->getMessage()) ? $exception->getMessage().' in the ThrottleRequestsException' : __('messages.ThrottleRequestsException'); 
            $status_code = 429;
        }

        if ($exception instanceof RuntimeException) {
            $is_error = true;
            $msg = !empty($exception->getMessage()) ? $exception->getMessage().' in the RuntimeException' : __('messages.RuntimeException'); 
            $status_code = 404;
        }

        if ($exception instanceof InvalidArgumentException) {
            $is_error = true;
            $msg = !empty($exception->getMessage()) ? $exception->getMessage().' in the InvalidArgumentException' : __('messages.InvalidArgumentException'); 
            $status_code = 400;
        }

        if ($exception instanceof Error) { 
            $is_error = true;
            $msg = !empty($exception->getMessage()) ? $exception->getMessage().' in the Error' : __('messages.Error'); 
            $status_code = 500;
        }
        
        if ($exception instanceof QueryException) { 
            $rootException = Main::getRootException($exception);

            $is_error = true;
            $msg = !empty($exception->getMessage()) && env('APP_DEBUG') ? $exception->getMessage().' in the QueryException' : __('messages.QueryException') . " in file {$rootException['file']} (line: {$rootException['line']}".(!empty($rootException['line']) ? ", function: {$rootException['function']}" : "").")"; 
            $status_code = 500;
        } 

        if ($exception instanceof ErrorException) {
            $is_error = true;
            $msg = !empty($exception->getMessage()) ? $exception->getMessage().' in the ErrorException' : __('messages.ErrorException'); 
            $status_code = 500;
        }

        if($is_error){
            $rootException = Main::getRootException($exception);

            // file log in storage/logs/laravel.log
            // Log::error('Error in handler.php :', [
            //     'message' => $msg,
            //     'file' => $rootException['file'],
            //     'line' => $rootException['line'],
            //     'function' => $rootException['function'],
            // ]);
            return ResponseFormatter::error($status_code, $msg); 
        }

        return parent::render($request, $exception);
    }
}
