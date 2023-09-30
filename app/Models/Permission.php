<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $permission_id
 * @property string $name
 * @property string $created_date
 * @property string $modified_date
 * @property string $created_by
 * @property string $modified_by
 * @property Menu[] $menus
 * @property Privilege[] $privileges
 */
class Permission extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'permission';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'permission_id';

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
        return $this->hasMany(\App\Models\Menu::class, 'permission_id', 'permission_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function privileges(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Privilege::class, 'permission_id', 'permission_id');
    }
}
