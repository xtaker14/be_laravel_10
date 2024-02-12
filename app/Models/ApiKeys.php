<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiKeys extends Model
{
    use HasFactory;

    protected $connection = 'mysql';

    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'api_keys';

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

    /**
     * @var array
     */
    protected $fillable = ['merchant_id', 'client_key', 'server_key', 'is_active', 'created_date', 'modified_date', 'created_by', 'modified_by'];
}
