<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;
// use MongoDB\Laravel\Eloquent\SoftDeletes;

class ApiKeys extends Model
{
    // use HasFactory;
    // use SoftDeletes;

    protected $connection = 'mongodb';

    /**
     * The table associated with the model.
     * 
     * @var string
     */ 
    protected $collection = 'api_keys';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'api_keys_id';

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
    protected $fillable = ['api_keys_id', 'merchant_id', 'client_key', 'server_key', 'is_active', 'created_date', 'modified_date', 'created_by', 'modified_by'];
}
