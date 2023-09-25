<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $spot_area_id
 * @property integer $spot_id
 * @property integer $district_id
 * @property string $created_date
 * @property string $modified_date
 * @property string $created_by
 * @property string $modified_by
 * @property Spot $spot
 * @property District $district
 */
class SpotArea extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'spotarea';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'spot_area_id';

    /**
     * Indicates if the model should be timestamped.
     * 
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['spot_id', 'district_id', 'created_date', 'modified_date', 'created_by', 'modified_by'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function spot(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Spot::class, 'spot_id', 'spot_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function district(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\District::class, 'district_id', 'district_id');
    }
}
