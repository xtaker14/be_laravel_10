<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $menu_id
 * @property integer $parent_id
 * @property integer $feature_id
 * @property integer $permission_id
 * @property string $name
 * @property string $description
 * @property integer $sequence
 * @property string $image_url
 * @property string $created_date
 * @property string $modified_date
 * @property string $created_by
 * @property string $modified_by
 * @property Menu $menu
 * @property Feature $feature
 * @property Permission $permission
 */
class Menu extends Model
{
    protected $connection = 'mysql';
    
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'menu';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'menu_id';

    /**
     * Indicates if the model should be timestamped.
     * 
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['parent_id', 'feature_id', 'permission_id', 'name', 'description', 'sequence', 'image_url', 'created_date', 'modified_date', 'created_by', 'modified_by'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function menu(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Menu::class, 'parent_id', 'menu_id');
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
