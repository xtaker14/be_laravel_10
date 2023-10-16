<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $district_id
 * @property integer $city_id
 * @property string $code
 * @property string $name
 * @property boolean $is_active
 * @property string $created_date
 * @property string $modified_date
 * @property string $created_by
 * @property string $modified_by
 * @property City $city
 * @property Spotarea[] $spotareas
 * @property Subdistrict[] $subdistricts
 */
class District extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'district';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'district_id';

    /**
     * Indicates if the model should be timestamped.
     * 
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['city_id', 'code', 'name', 'is_active', 'created_date', 'modified_date', 'created_by', 'modified_by'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function city(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\City::class, 'city_id', 'city_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function spotareas(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Spotarea::class, 'district_id', 'district_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subdistricts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Subdistrict::class, 'district_id', 'district_id');
    }
}
