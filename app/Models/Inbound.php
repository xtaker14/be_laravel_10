<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $inbound_id
 * @property integer $hub_id
 * @property integer $transfer_id
 * @property integer $inbound_type_id
 * @property string $code
 * @property string $courier_name
 * @property string $driver_name
 * @property string $driver_phone
 * @property string $vehicle_type
 * @property string $vehicle_number
 * @property string $notes
 * @property string $created_date
 * @property string $modified_date
 * @property string $created_by
 * @property string $modified_by
 * @property Hub $hub
 * @property Transfer $transfer
 * @property Inboundtype $inboundtype
 * @property Inbounddetail[] $inbounddetails
 */
class Inbound extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'inbound';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'inbound_id';

    /**
     * Indicates if the model should be timestamped.
     * 
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['hub_id', 'transfer_id', 'inbound_type_id', 'code', 'courier_name', 'driver_name', 'driver_phone', 'vehicle_type', 'vehicle_number', 'notes', 'created_date', 'modified_date', 'created_by', 'modified_by'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function hub(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Hub::class, 'hub_id', 'hub_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function transfer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Transfer::class, 'transfer_id', 'transfer_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function inboundtype(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\InboundType::class, 'inbound_type_id', 'inbound_type_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function inbounddetails(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\InboundDetail::class, 'inbound_id', 'inbound_id');
    }
}
