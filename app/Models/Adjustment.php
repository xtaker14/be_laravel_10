<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $city_id
 * @property integer $province_id
 * @property string $code
 * @property string $name
 * @property boolean $is_active
 * @property string $created_date
 * @property string $modified_date
 * @property string $created_by
 * @property string $modified_by
 * @property Province $province
 * @property District[] $districts
 * @property Hubarea[] $hubareas
 * @property Rates[] $rates
 */
class Adjustment extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'adjustment';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'adjustment_id';

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
    protected $fillable = ['adjustment_id', 'code', 'type', 'status_from', 'status_to', 'reason', 'remark', 'created_date', 'modified_date', 'created_by', 'modified_by'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function statusFrom(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Status::class, 'status_from', 'status_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function statusTo(): \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(\App\Models\Status::class, 'status_to', 'status_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function package(): \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(\App\Models\Package::class, 'code', 'tracking_number')->withDefault();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function masterWaybill(): \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(\App\Models\MasterWaybill::class, 'code', 'code')->withDefault();
    }
}
