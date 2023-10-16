<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $rates_id
 * @property integer $service_type_id
 * @property integer $origin_city_id
 * @property integer $destination_city_id
 * @property boolean $is_cod
 * @property float $publish_price
 * @property integer $maximum_delivered
 * @property string $created_date
 * @property string $modified_date
 * @property string $created_by
 * @property string $modified_by
 * @property ClientRates[] $clientrates
 * @property Servicetype $servicetype
 */
class Rates extends Model
{
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'rates_id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['service_type_id', 'origin_city_id', 'destination_city_id', 'is_cod', 'publish_price', 'maximum_delivered', 'created_date', 'modified_date', 'created_by', 'modified_by'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function clientrates(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\ClientRates::class, 'rates_id', 'rates_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function servicetype(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\ServiceType::class, 'service_type_id', 'service_type_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function city(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\City::class, 'origin_city_id', 'city_id');
    }

}
