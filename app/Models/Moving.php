<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $moving_id
 * @property integer $package_id
 * @property integer $grid_id
 * @property string $created_date
 * @property string $modified_date
 * @property string $created_by
 * @property string $modified_by
 * @property Package $package
 * @property Grid $grid
 */
class Moving extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'moving';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'moving_id';

    /**
     * Indicates if the model should be timestamped.
     * 
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['package_id', 'grid_id', 'created_date', 'modified_date', 'created_by', 'modified_by'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function package(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Package::class, 'package_id', 'package_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function grid(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Grid::class, 'grid_id', 'grid_id');
    }
}
