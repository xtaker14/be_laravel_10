<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OTP extends Model
{
    use HasFactory;

    protected $table = 'otp';

    public static $exp_time = 10;

    public static function generateCode(){
        return rand(100000, 999999);
    }
}
