<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $country_id
 * @property string $code
 * @property string $name
 * @property boolean $is_active
 * @property string $created_date
 * @property string $modified_date
 * @property string $created_by
 * @property string $modified_by
 * @property Province[] $provinces
 */
class Country extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'country';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'country_id';

    /**
     * Indicates if the model should be timestamped.
     * 
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['code', 'name', 'is_active', 'created_date', 'modified_date', 'created_by', 'modified_by'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function provinces(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\Province', null, 'country_id');
    }
}
