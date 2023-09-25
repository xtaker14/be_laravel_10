<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $service_type_id
 * @property integer $organization_id
 * @property string $code
 * @property string $name
 * @property integer $minimum_weight
 * @property integer $maximum_weight
 * @property string $description
 * @property string $created_date
 * @property string $modified_date
 * @property string $created_by
 * @property string $modified_by
 * @property Package[] $packages
 * @property Rates[] $rates
 * @property Organization $organization
 */
class ServiceType extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'servicetype';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'service_type_id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['organization_id', 'code', 'name', 'minimum_weight', 'maximum_weight', 'description', 'created_date', 'modified_date', 'created_by', 'modified_by'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function packages(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Package::class, 'service_type_id', 'service_type_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rates(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Rates::class, 'service_type_id', 'service_type_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function organization(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Organization::class, 'organization_id', 'organization_id');
    }
}
