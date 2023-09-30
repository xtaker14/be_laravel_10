<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $spot_id
 * @property integer $hub_id
 * @property string $code
 * @property string $name
 * @property string $created_date
 * @property string $modified_date
 * @property string $created_by
 * @property string $modified_by
 * @property Routing[] $routings
 * @property Hub $hub
 * @property Spotarea[] $spotareas
 */
class Spot extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'spot';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'spot_id';

    /**
     * Indicates if the model should be timestamped.
     * 
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['hub_id', 'code', 'name', 'created_date', 'modified_date', 'created_by', 'modified_by'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function routings(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Routing::class, 'spot_id', 'spot_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function hub(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Hub::class, 'hub_id', 'hub_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function spotareas(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Spotarea::class, 'spot_id', 'spot_id');
    }
}
