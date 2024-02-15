<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;
// use MongoDB\Laravel\Eloquent\SoftDeletes;

class Counters extends Model
{
    use HasFactory;
    // use SoftDeletes;

    protected $connection = 'mongodb';

    /**
     * The table associated with the model.
     * 
     * @var string
     */ 
    protected $collection = 'counters';

    /**
     * Indicates if the model should be timestamped.
     * 
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['collection_name', 'column_name', 'sequence_value', 'code'];
}
