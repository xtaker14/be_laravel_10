<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $hub_area_id
 * @property integer $hub_id
 * @property integer $city_id
 * @property string $created_date
 * @property string $modified_date
 * @property string $created_by
 * @property string $modified_by
 * @property Hub $hub
 * @property City $city
 */
class HubArea extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'hubarea';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'hub_area_id';

    /**
     * Indicates if the model should be timestamped.
     * 
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['hub_id', 'city_id', 'created_date', 'modified_date', 'created_by', 'modified_by'];

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
    public function city(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\City', null, 'city_id');
    }
}
