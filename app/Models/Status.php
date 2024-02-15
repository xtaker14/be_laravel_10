<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model; 

/**
 * @property integer $status_id
 * @property string $code
 * @property integer $status_order
 * @property string $status_group
 * @property string $name
 * @property string $color
 * @property string $label
 * @property boolean $is_active
 * @property string $created_date
 * @property string $modified_date
 * @property string $created_by
 * @property string $modified_by
 * @property array STATUS_GROUP
 * @property array STATUS
 */
class Status extends Model
{
    protected $connection = 'mongodb';
    
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $collection = 'status';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'status_id';

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

    // return status code
    CONST STATUS = [
        'test_1' => [
            'test_2' => 'TEST 2',
        ],
    ];

    CONST ACTIVE = 1;
    const NOT_ACTIVE = 0;

    /**
     * @var array
     */
    protected $fillable = ['code', 'name', 'color', 'is_active', 'status_order', 'label', 'status_group', 'created_date', 'modified_date', 'created_by', 'modified_by'];
}
