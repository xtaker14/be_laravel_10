<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $partner_id
 * @property integer $organization_id
 * @property string $code
 * @property string $name
 * @property float $package_cost
 * @property string $created_date
 * @property string $modified_date
 * @property string $created_by
 * @property string $modified_by
 * @property Courier[] $couriers
 * @property Organization $organization
 * @property Userspartner[] $userspartners
 */
class Partner extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'partner';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'partner_id';

    /**
     * Indicates if the model should be timestamped.
     * 
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['organization_id', 'code', 'name', 'package_cost', 'created_date', 'modified_date', 'created_by', 'modified_by'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function couriers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\Courier', null, 'partner_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function organization(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\Organization', null, 'organization_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userspartners(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\Userspartner', null, 'partner_id');
    }
}
