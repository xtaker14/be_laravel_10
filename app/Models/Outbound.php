<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $outbound_id
 * @property integer $hub_id
 * @property integer $transfer_id
 * @property integer $package_id
 * @property string $created_date
 * @property string $modified_date
 * @property string $created_by
 * @property string $modified_by
 * @property Hub $hub
 * @property Transfer $transfer
 * @property Package $package
 */
class Outbound extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'outbound';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'outbound_id';

    /**
     * Indicates if the model should be timestamped.
     * 
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['hub_id', 'transfer_id', 'package_id', 'created_date', 'modified_date', 'created_by', 'modified_by'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function hub(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\Hub', null, 'hub_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function transfer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\Transfer', null, 'transfer_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function package(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\Package', null, 'package_id');
    }
}
