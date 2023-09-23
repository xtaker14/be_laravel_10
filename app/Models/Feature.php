<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $feature_id
 * @property string $name
 * @property string $created_date
 * @property string $modified_date
 * @property string $created_by
 * @property string $modified_by
 * @property Menu[] $menus
 * @property Privilege[] $privileges
 */
class Feature extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'feature';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'feature_id';

    /**
     * Indicates if the model should be timestamped.
     * 
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['name', 'created_date', 'modified_date', 'created_by', 'modified_by'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function menus(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\Menu', null, 'feature_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function privileges(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\Privilege', null, 'feature_id');
    }
}
