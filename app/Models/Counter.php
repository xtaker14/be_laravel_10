<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $counter_id
 * @property string $code
 * @property string $name
 * @property integer $counter
 * @property string $created_date
 * @property string $modified_date
 * @property string $created_by
 * @property string $modified_by
 */
class Counter extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'counter';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'counter_id';

    /**
     * Indicates if the model should be timestamped.
     * 
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['code', 'name', 'counter', 'created_date', 'modified_date', 'created_by', 'modified_by'];
}
