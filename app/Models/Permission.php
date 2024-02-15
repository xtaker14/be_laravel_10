<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model; 
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
    protected $connection = 'mongodb';
    
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $collection = 'permission';

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

    protected $dates = ['created_date', 'modified_date'];

    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    const CREATED_AT = 'created_date';

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = 'modified_date'; 

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
