<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $log_login_id
 * @property string $ip
 * @property string $browser
 * @property string $created_date
 * @property string $modified_date
 * @property string $created_by
 * @property string $modified_by
 */
class LogLogin extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'loglogin';

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

    /**
     * @var array
     */
    protected $fillable = ['ip', 'browser', 'created_date', 'modified_date', 'created_by', 'modified_by'];
}
