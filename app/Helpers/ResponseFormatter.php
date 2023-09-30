<?php

namespace App\Helpers;

use Exception;
use Illuminate\Http\Client\RequestException; 
use Ramsey\Uuid\Uuid;
use stdClass;

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
        'errors' => [],
        'request_id' => '',
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
    public static function success($message = null, $params = [], $status_code = 200)
    {
        self::$response['status_code'] = $status_code;
        self::$response['status'] = static::statusCode($status_code);

        self::$response['message'] = $message;
        self::$response['data'] = $params;
        if(empty($params)){
            self::$response['data'] = new stdClass;
        }
        self::$response['errors'] = new stdClass;
        self::$response['request_id'] = Uuid::uuid4()->toString(); 

        if($status_code != 200){
            return response()->json(self::$response, 200);
        }
        return response()->json(self::$response, self::$response['status_code']);
    }

    /**
     * Give error response.
     */
    public static function error($status_code = 400, $message = null, $params = [])
    {
        self::$response['status_code'] = $status_code;
        self::$response['status'] = static::statusCode($status_code);
        self::$response['message'] = $message;
        self::$response['data'] = new stdClass;
        self::$response['errors'] = $params;
        if(empty($params)){
            self::$response['errors'] = new stdClass;
        }
        self::$response['request_id'] = Uuid::uuid4()->toString(); 

        return response()->json(self::$response, self::$response['status_code']);
    }

    public static function traceCode($code, $params=[])
    {
        $ar = [ 
            'AUTH001' => [
                'trace' => [
                    'INVALID_CREDENTIALS'
                ]
            ],
            'AUTH002' => [
                'trace' => [
                    'JWT_EXCEPTION'
                ]
            ],
            'AUTH003' => [
                'trace' => [
                    'UNAUTHENTICATED'
                ]
            ],
            // ----------
            'AUTH004' => [
                'trace' => [
                    'INVALID_TOKEN'
                ]
            ],
            'AUTH005' => [
                'trace' => [
                    'TOKEN_EXPIRED'
                ]
            ],
            'AUTH006' => [
                'trace' => [
                    'TOKEN_NOT_FOUND'
                ]
            ], 
            'AUTH007' => [
                'trace' => [
                    'INVALID_REFRESH_TOKEN'
                ]
            ],
            // ----------
            'AUTH008' => [
                'trace' => [
                    'PASSWORD_ALREADY_USED'
                ]
            ],
            // ----------
            'AUTH009' => [
                'trace' => [
                    'WAIT_REQUEST_NEW_OTP'
                ]
            ],
            'AUTH010' => [
                'trace' => [
                    'NO_OTP_FOUND'
                ]
            ],
            'AUTH011' => [
                'trace' => [
                    'EXCEEDED_OTP_ATT_LIMIT'
                ]
            ],
            'AUTH012' => [
                'trace' => [
                    'INVALID_OTP'
                ]
            ],
            // ----------
            'REQUEST001' => [
                'trace' => [
                    'INVALID_RULES_CONFIGURATION'
                ]
            ], 
            'REQUEST002' => [
                'trace' => [
                    'INVALID_RULES'
                ]
            ], 
            // ----------
            'ROLE001' => [
                'trace' => [
                    'ALREADY_HAS_ROLE'
                ]
            ], 
            'ROLE002' => [
                'trace' => [
                    'HAS_NO_ROLE'
                ]
            ], 
            // ----------
            'ALLOWED001' => [
                'trace' => [
                    'THE_SITE_ISN_ALLOWED'
                ]
            ], 
            // ----------
            'PERMISSION001' => [
                'trace' => [
                    'ALREADY_HAS_PERMISSION'
                ]
            ],
            'PERMISSION002' => [
                'trace' => [
                    'HAS_NO_PERMISSION'
                ]
            ], 
            // ----------
            'FEATURE001' => [
                'trace' => [
                    'ALREADY_HAS_FEATURE'
                ]
            ],
            'FEATURE002' => [
                'trace' => [
                    'HAS_NO_FEATURE'
                ]
            ], 
            // ----------
            'FORMAT001' => [
                'trace' => [
                    'JSON_ONLY'
                ]
            ], 
            // ----------
            'EXCEPTION001' => [
                'trace' => [
                    'UNAUTHENTICATED_EXCEPTION'
                ]
            ],
            'EXCEPTION002' => [
                'trace' => [
                    'FORBIDDEN_EXCEPTION'
                ]
            ],
            'EXCEPTION003' => [
                'trace' => [
                    'NOT_FOUND_EXCEPTION'
                ]
            ],
            'EXCEPTION004' => [
                'trace' => [
                    'METHOD_NOT_ALLOWED_EXCEPTION'
                ]
            ],
            'EXCEPTION005' => [
                'trace' => [
                    'UNPROCESSABLE_CONTENT_EXCEPTION'
                ]
            ],
            'EXCEPTION006' => [
                'trace' => [
                    'SEE_OTHER_EXCEPTION'
                ]
            ],
            'EXCEPTION007' => [
                'trace' => [
                    'REQUEST_TIMEOUT_EXCEPTION'
                ]
            ],
            'EXCEPTION008' => [
                'trace' => [
                    'CONTENT_TOO_LARGE_EXCEPTION'
                ]
            ],
            'EXCEPTION009' => [
                'trace' => [
                    'BAD_GATEWAY_EXCEPTION'
                ]
            ],
            'EXCEPTION010' => [
                'trace' => [
                    'SERVICE_UNAVAILABLE_EXCEPTION'
                ]
            ],
            'EXCEPTION011' => [
                'trace' => [
                    'LOOP_DETECTED_EXCEPTION'
                ]
            ],
            'EXCEPTION012' => [
                'trace' => [
                    'BAD_REQUEST_EXCEPTION'
                ]
            ],
            'EXCEPTION013' => [
                'trace' => [
                    'TOO_MANY_REQUESTS_EXCEPTION'
                ]
            ],
            'EXCEPTION014' => [
                'trace' => [
                    'SOMETHING_WENT_WRONG_EXCEPTION'
                ]
            ],
            'EXCEPTION015' => [
                'trace' => [
                    'NOT_FOUND'
                ]
            ],
        ];

        if(!empty($ar[$code])){
            $res['code'] = $code;
            foreach ($ar[$code] as $key => $val) {
                $res[$key] = $val;
            }
            if(!empty($params)){
                foreach ($params as $key => $val) {
                    $res['details'][$key] = $val;
                }
            }
            return $res;
        }

        return [];
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