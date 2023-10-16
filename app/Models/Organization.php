<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $organization_id
 * @property string $code
 * @property string $name
 * @property string $created_date
 * @property string $modified_date
 * @property string $created_by
 * @property string $modified_by
 * @property Client[] $clients
 * @property Hub[] $hubs
 * @property Partner[] $partners
 * @property Servicetype[] $servicetypes
 */
class Organization extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'organization';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'organization_id';

    /**
     * Indicates if the model should be timestamped.
     * 
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['code', 'name', 'created_date', 'modified_date', 'created_by', 'modified_by'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function clients(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Client::class, 'organization_id', 'organization_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function hubs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Hub::class, 'organization_id', 'organization_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function partners(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Partner::class, 'organization_id', 'organization_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function servicetypes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\ServiceType::class, 'organization_id', 'organization_id');
    }
}
