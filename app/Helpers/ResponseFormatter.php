<?php

namespace App\Helpers;

use Exception;
use Illuminate\Http\Client\RequestException; 

/**
 * Format response.
 */
class ResponseFormatter
{
    /**
     * API Response
     *
     * @var array
     */
    protected static $response = [
        'status_code' => 200,
        'status' => 'OK',
        'message' => 'success',
        'data' => [],
    ];

    /**
     * Give status code response.
     */
    public static function statusCode($code)
    {
        $status_code = [
            '100' => 'Continue',
            '101' => 'Switching Protocols',
            '102' => 'Processing',
            '103' => 'Early Hints',
            '200' => 'OK',
            '201' => 'Created',
            '202' => 'Accepted',
            '203' => 'Non-Authoritative Information',
            '204' => 'No Content',
            '205' => 'Reset Content',
            '206' => 'Partial Content',
            '207' => 'Multi-Status',
            '208' => 'Already Reported',
            '226' => 'IM Used',
            '300' => 'Multiple Choices',
            '301' => 'Moved Permanently',
            '302' => 'Found',
            '303' => 'See Other',
            '304' => 'Not Modified',
            '307' => 'Temporary Redirect',
            '308' => 'Permanent Redirect',
            '400' => 'Bad Request',
            '401' => 'Unauthorized',
            '402' => 'Payment Required',
            '403' => 'Forbidden',
            '404' => 'Not Found',
            '405' => 'Method Not Allowed',
            '406' => 'Not Acceptable',
            '407' => 'Proxy Authentication Required',
            '408' => 'Request Timeout',
            '409' => 'Conflict',
            '410' => 'Gone',
            '411' => 'Length Required',
            '412' => 'Precondition Failed',
            '413' => 'Content Too Large',
            '414' => 'URI Too Long',
            '415' => 'Unsupported Media Type',
            '416' => 'Range Not Satisfiable',
            '417' => 'Expectation Failed',
            '418' => 'I\'m a teapot',
            '421' => 'Misdirected Request',
            '422' => 'Unprocessable Content',
            '423' => 'Locked',
            '424' => 'Failed Dependency',
            '425' => 'Too Early',
            '426' => 'Upgrade Required',
            '428' => 'Precondition Required',
            '429' => 'Too Many Requests',
            '431' => 'Request Header Fields Too Large',
            '451' => 'Unavailable For Legal Reasons',
            '500' => 'Internal Server Error',
            '501' => 'Not Implemented',
            '502' => 'Bad Gateway',
            '503' => 'Service Unavailable',
            '504' => 'Gateway Timeout',
            '505' => 'HTTP Version Not Supported',
            '506' => 'Variant Also Negotiates',
            '507' => 'Insufficient Storage',
            '508' => 'Loop Detected',
            '510' => 'Not Extended',
            '511' => 'Network Authentication Required',
        ];

        if(empty($status_code[$code])){
            return $status_code[500];
        }

        return $status_code[$code];
    }

    /**
     * Give success response.
     */
    public static function success($message = null, $data = [])
    {
        self::$response['message'] = $message;
        self::$response['data'] = $data;

        return response()->json(self::$response, self::$response['status_code']);
    }

    /**
     * Give error response.
     */
    public static function error($status_code = 400, $message = null, $data = [])
    {
        self::$response['status_code'] = $status_code;
        self::$response['status'] = static::statusCode($status_code);
        self::$response['message'] = $message;
        self::$response['data'] = $data;

        return response()->json(self::$response, self::$response['status_code']);
    }

    public static function catchError(Exception $error, $msg=false, $with_trace=false)
    { 
        $status_code = $error->getCode();

        if(empty($status_code)){
            $status_code = 500;
        }
        if(!is_int($status_code)){
            $status_code = 500;
        } 

        $params_trace = [];
        if($with_trace){
            $params_trace = [
                'error_trace' => $error->getTraceAsString(), 
                'error_line' => $error->getLine(),
                'error_file' => $error->getFile(),
            ];
        }

        if(empty($msg)){
            $msg = $error->getMessage();
        }

        return self::error($status_code, $msg, $params_trace); 
    }

    public static function catchRequestError(RequestException $error, $msg=false, $with_trace=false)
    { 
        $status_code = $error->getCode();

        if(empty($status_code)){
            $status_code = 500;
        }
        if(!is_int($status_code)){
            $status_code = 500;
        } 

        $params_trace = [];
        if($with_trace){
            $params_trace = [
                'error_trace' => $error->getTraceAsString(), 
                'error_line' => $error->getLine(),
                'error_file' => $error->getFile(),
            ];
        }

        if(empty($msg)){
            $msg = $error->getMessage();
        }

        return self::error($status_code, $msg, $params_trace);
    }
}