<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $reconcile_id
 * @property integer $routing_id
 * @property string $code
 * @property string $unique_number
 * @property float $total_deposit
 * @property float $actual_deposit
 * @property float $remaining_deposit
 * @property string $created_date
 * @property string $modified_date
 * @property string $created_by
 * @property string $modified_by
 * @property Routing $routing
 */
class Reconcile extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'reconcile';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'reconcile_id';

    /**
     * Indicates if the model should be timestamped.
     * 
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['routing_id', 'code', 'unique_number', 'total_deposit', 'actual_deposit', 'remaining_deposit', 'created_date', 'modified_date', 'created_by', 'modified_by'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function routing(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\Routing', null, 'routing_id');
    }
}
