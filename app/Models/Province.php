<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $province_id
 * @property integer $country_id
 * @property string $name
 * @property boolean $is_active
 * @property string $created_date
 * @property string $modified_date
 * @property string $created_by
 * @property string $modified_by
 * @property City[] $cities
 * @property Country $country
 */
class Province extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'province';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'province_id';

    /**
     * Indicates if the model should be timestamped.
     * 
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['country_id', 'name', 'is_active', 'created_date', 'modified_date', 'created_by', 'modified_by'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cities(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\City::class, 'province_id', 'province_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Country::class, 'country_id', 'country_id');
    }
}
