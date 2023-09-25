<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $subdistrict_id
 * @property integer $district_id
 * @property string $name
 * @property boolean $is_active
 * @property string $created_date
 * @property string $modified_date
 * @property string $created_by
 * @property string $modified_by
 * @property Hub[] $hubs
 * @property District $district
 */
class Subdistrict extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'subdistrict';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'subdistrict_id';

    /**
     * Indicates if the model should be timestamped.
     * 
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['district_id', 'name', 'is_active', 'created_date', 'modified_date', 'created_by', 'modified_by'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function hubs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Hub::class, 'subdistrict_id', 'subdistrict_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function district(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\District::class, 'district_id', 'district_id');
    }
}
