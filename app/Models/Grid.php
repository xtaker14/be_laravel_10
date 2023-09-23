<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $grid_id
 * @property integer $hub_id
 * @property string $name
 * @property string $created_date
 * @property string $modified_date
 * @property string $created_by
 * @property string $modified_by
 * @property Hub $hub
 * @property Moving[] $movings
 */
class Grid extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'grid';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'grid_id';

    /**
     * Indicates if the model should be timestamped.
     * 
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['hub_id', 'name', 'created_date', 'modified_date', 'created_by', 'modified_by'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function hub(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\Hub', null, 'hub_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function movings(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\Moving', null, 'grid_id');
    }
}
