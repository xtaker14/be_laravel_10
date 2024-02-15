<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model; 

/**
 * @property integer $privilege_id
 * @property integer $role_id
 * @property integer $feature_id
 * @property integer $permission_id
 * @property string $created_date
 * @property string $modified_date
 * @property string $created_by
 * @property string $modified_by
 * @property Role $role
 * @property Feature $feature
 * @property Permission $permission
 */
class Privilege extends Model
{
    protected $connection = 'mongodb';
    
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $collection = 'privilege';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'privilege_id';

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
    protected $fillable = ['role_id', 'feature_id', 'permission_id', 'created_date', 'modified_date', 'created_by', 'modified_by'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Role::class, 'role_id', 'role_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function feature(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Feature::class, 'feature_id', 'feature_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function permission(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Permission::class, 'permission_id', 'permission_id');
    }
}
