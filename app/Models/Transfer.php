<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $transfer_id
 * @property integer $from_hub_id
 * @property integer $to_hub_id
 * @property string $code
 * @property string $modified_date
 * @property string $created_by
 * @property string $modified_by
 * @property Inbound[] $inbounds
 * @property Outbound[] $outbounds
 * @property Hub $hub
 * @property Hub $hub
 * @property Transferdetail[] $transferdetails
 * @property Transferhistory[] $transferhistories
 */
class Transfer extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'transfer';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'transfer_id';

    /**
     * Indicates if the model should be timestamped.
     * 
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['from_hub_id', 'to_hub_id', 'code', 'modified_date', 'created_by', 'modified_by'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function inbounds(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\Inbound', null, 'transfer_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function outbounds(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\Outbound', null, 'transfer_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function hub(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\Hub', 'from_hub_id', 'hub_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function hub(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\Hub', 'to_hub_id', 'hub_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transferdetails(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\Transferdetail', null, 'transfer_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transferhistories(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\Transferhistory', null, 'transfer_id');
    }
}
