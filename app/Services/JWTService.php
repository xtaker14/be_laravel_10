<?php

namespace App\Services;

use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Illuminate\Support\Facades\Route;

class JWTService
{
    public function checkToken()
    { 
        try {
            JWTAuth::parseToken()->authenticate();
        } catch (TokenInvalidException $e) {
            if ($e) {
                return 'invalid_token'; 
            } else if ($e) {
                return 'token_expired'; 
            } else {
                return 'token_not_found';
            }
        }

        return 'valid_token'; 
    }
}
