<?php

namespace App\Models;

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
class LogLogin extends Model
{
    protected $connection = 'mongodb';
    
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $collection = 'loglogin';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'log_login_id';

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
    protected $fillable = ['ip', 'browser', 'location', 'access_token', 'created_date', 'modified_date', 'created_by', 'modified_by'];
}
