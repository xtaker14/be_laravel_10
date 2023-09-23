<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $routing_id
 * @property integer $spot_id
 * @property integer $courier_id
 * @property string $code
 * @property string $created_date
 * @property string $modified_date
 * @property string $created_by
 * @property string $modified_by
 * @property Reconcile $reconcile
 * @property Spot $spot
 * @property Courier $courier
 * @property Routingdetail[] $routingdetails
 * @property Routinghistory[] $routinghistories
 */
class Routing extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'routing';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'routing_id';

    /**
     * Indicates if the model should be timestamped.
     * 
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['spot_id', 'courier_id', 'code', 'created_date', 'modified_date', 'created_by', 'modified_by'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function reconcile(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne('App\Models\Reconcile', 'routing_id', 'routing_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function spot(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\Spot', null, 'spot_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function courier(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\Courier', null, 'courier_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function routingdetails(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\Routingdetail', null, 'routing_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function routinghistories(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\Routinghistory', null, 'routing_id');
    }
}
