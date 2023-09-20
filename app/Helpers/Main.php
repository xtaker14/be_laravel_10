<?php

namespace App\Helpers; 

use Exception;
use Illuminate\Support\Facades\Http;

class Main
{ 
    private static function determineStatusCode($errors)
    {
        $response = new \Illuminate\Http\Response;
        if ($errors->has('field_name')) {
            return $response::HTTP_UNPROCESSABLE_ENTITY;
        }
        
        return $response::HTTP_BAD_REQUEST;
    }

    public static function validator($request, $params, $msg=false)
    {
        $facadesValidator = new \Illuminate\Support\Facades\Validator;

        if(empty($params['rules']) || !is_array(($params['rules']))){
            return ResponseFormatter::error(500, __('messages.invalid_rules_conf'));
        }

        if(!empty($params['messages']) && is_array(($params['messages']))){
            $validator = $facadesValidator::make($request->all(), $params['rules'], $params['messages']);
        }else{
            $validator = $facadesValidator::make($request->all(), $params['rules']);
        }

        if ($validator->fails()) {
            $errors = $validator->errors();
            $status_code = self::determineStatusCode($errors);
            if(empty($status_code)){
                $status_code = 500;
            }
            if(!is_int($status_code)){
                $status_code = 500;
            } 

            if(empty($msg)){
                $msg = __('messages.something_went_wrong');
            }
            return ResponseFormatter::error($status_code, $msg, $validator->errors());
        }

        return [];
    }

    public static function getRootException(\Throwable $exception)
    {
        // while ($exception->getPrevious() !== null) {
        //     $exception = $exception->getPrevious();
        // }
        // return $exception;

        // dump($exception->getTrace()); exit;

        $res_error = [];
        foreach ($exception->getTrace() as $key => $val) {  
            if (strpos($val['file'], 'app\Http\Controllers\\') !== false) {
                $val['file'] = str_replace('\\','/',$val['file']);
                $pos_app = strpos($val['file'], "/app/");
                $file_name = substr($val['file'], $pos_app);
                $val['file'] = str_replace('/app/Http/Controllers/','',$file_name);
                $res_error = $val;
                break;
            }
            else if (strpos($val['file'], 'app\Models\\') !== false) {
                $val['file'] = str_replace('\\','/',$val['file']);
                $pos_app = strpos($val['file'], "/app/");
                $file_name = substr($val['file'], $pos_app);
                $val['file'] = str_replace('/app/Models/','',$file_name);
                $res_error = $val;
                break;
            }  
        }

        // dump($res_error); 
        // exit;

        return $res_error;
    }
    
    public static function sendOtp($otp, $phone_number)
    {
        $account_sid = env('TWILIO_ACCOUNT_SID');
        $auth_token = env('TWILIO_AUTH_TOKEN');
        $twilio_number = env('TWILIO_NUMBER');

        $client = new \Twilio\Rest\Client($account_sid, $auth_token);
        
        $client->messages->create(
            $phone_number,
            [
                'from' => $twilio_number,
                'body' => 'Your OTP code is: ' . $otp,
            ]
        );
    }
}