<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $routing_detail_id
 * @property integer $routing_id
 * @property integer $package_id
 * @property string $created_date
 * @property string $modified_date
 * @property string $created_by
 * @property string $modified_by
 * @property Routing $routing
 * @property Package $package
 */
class RoutigDetail extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'routingdetail';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'routing_detail_id';

    /**
     * Indicates if the model should be timestamped.
     * 
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['routing_id', 'package_id', 'created_date', 'modified_date', 'created_by', 'modified_by'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function routing(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\Routing', null, 'routing_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function package(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\Package', null, 'package_id');
    }
}
