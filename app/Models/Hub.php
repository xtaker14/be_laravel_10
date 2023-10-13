<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property integer $hub_id
 * @property integer $organization_id
 * @property integer $hub_type_id
 * @property integer $subdistrict_id
 * @property string $code
 * @property string $name
 * @property string $street_name
 * @property integer $street_number
 * @property string $neighbourhood
 * @property integer $postcode
 * @property string $maps_url
 * @property string $coordinate
 * @property boolean $is_active
 * @property string $created_date
 * @property string $modified_date
 * @property string $created_by
 * @property string $modified_by
 * @property Grid[] $grids
 * @property Hubtype $hubtype
 * @property Subdistrict $subdistrict
 * @property Organization $organization
 * @property Hubarea[] $hubareas
 * @property Inbound[] $inbounds
 * @property Outbound[] $outbounds
 * @property Spot[] $spots
 * @property Transfer[] $transfers
 * @property Transfer[] $transfers
 * @property Package[] $packages
 * @property Courier[] $couriers
 * @property Usershub[] $usershubs
 */
class Hub extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'hub';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'hub_id';

    /**
     * Indicates if the model should be timestamped.
     * 
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['organization_id', 'hub_type_id', 'subdistrict_id', 'code', 'name', 'street_name', 'street_number', 'neighbourhood', 'postcode', 'maps_url', 'coordinate', 'is_active', 'created_date', 'modified_date', 'created_by', 'modified_by'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function grids(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Grid::class, 'hub_id', 'hub_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function hubtype(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Hubtype::class, 'hub_type_id', 'hub_type_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subdistrict(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Subdistrict::class, 'subdistrict_id', 'subdistrict_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function organization(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Organization::class, 'organization_id', 'organization_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function hubareas(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Hubarea::class, 'hub_id', 'hub_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function inbounds(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Inbound::class, 'hub_id', 'hub_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function outbounds(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Outbound::class, 'hub_id', 'hub_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function spots(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Spot::class, 'hub_id', 'hub_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transfersFrom(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Transfer::class, 'from_hub_id', 'hub_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transfersTo(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Transfer::class, 'to_hub_id', 'hub_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function packages(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Package::class, 'hub_id', 'hub_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function couriers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Courier::class, 'hub_id', 'hub_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function usershubs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Userhub::class, 'hub_id', 'hub_id');
    }
}
