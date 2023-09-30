<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $inbound_detail_id
 * @property integer $inbound_id
 * @property integer $package_id
 * @property string $created_date
 * @property string $modified_date
 * @property string $created_by
 * @property string $modified_by
 * @property Inbound $inbound
 * @property Package $package
 */
class InboundDetail extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'inbounddetail';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'inbound_detail_id';

    /**
     * Indicates if the model should be timestamped.
     * 
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['inbound_id', 'package_id', 'created_date', 'modified_date', 'created_by', 'modified_by'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function inbound(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Inbound::class, 'inbound_id', 'inbound_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function package(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Package::class, 'package_id', 'package_id');
    }
}
