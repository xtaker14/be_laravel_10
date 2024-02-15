<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model; 

/**
 * @property integer $log_login_id 
 * @property string $ip
 * @property string $browser
 * @property string $location
 * @property string $created_date
 * @property string $modified_date
 * @property string $created_by
 * @property string $modified_by
 */
class OTP extends Model
{
    // use HasFactory;

    protected $connection = 'mongodb';

    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $collection = 'otp';

    /**
     * Indicates if the model should be timestamped.
     * 
     * @var bool
     */
    public $timestamps = false;

    protected $dates = ['created_date', 'modified_date'];

    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    const CREATED_AT = 'created_date';

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = 'modified_date'; 

    /**
     * @var array
     */
    protected $fillable = ['phone_number', 'otp', 'attempts', 'type', 'otp_attempts_reset_at', 'otp_expires_at', 'verified_at', 'created_date', 'modified_date', 'created_by', 'modified_by'];

    public static $exp_time = 10;

    public static function generateCode()
    {
        return rand(100000, 999999);
    }
}
