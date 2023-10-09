<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// use App\Traits\JoinTableTrait; 

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
class RoutingDetail extends Model
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
    protected $fillable = ['routing_id', 'package_id', 'created_date', 'modified_date', 'created_by', 'modified_by'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function routing(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Routing::class, 'routing_id', 'routing_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function package(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Package::class, 'package_id', 'package_id');
    }

    // public function scopeJoinTable(\Illuminate\Database\Eloquent\Builder $query, $val, $relation_alias, $alias=null)
    // {
    //     $params_val = [];
    //     if(!isset($val['method'])){
    //         $params_val=[
    //             'method'=>'inner',
    //             'val'=>$val,
    //         ];
    //     }else{
    //         $params_val=[
    //             'method'=>$val['method'],
    //             'val'=>$val['val'],
    //         ];
    //     }

    //     $relation_name = '';
    //     $relation_key = '';

    //     if($val['val'] == 'package'){
    //         $relation_name = app(Package::class)->getTable();
    //         $relation_key = 'package_id';
    //         $this_key = 'package_id';
    //     }else{
    //         return $query;
    //     }

    //     if(!empty($alias)){
    //         $query->from($this->table . ' as ' . $alias);
    //     }

    //     $query->join(
    //         $relation_name . ' as ' . $relation_alias, 
    //         $alias . '.' . $this_key, 
    //         '=', 
    //         $relation_alias . '.' . $relation_key, 
    //         $params_val['method']
    //     );
    //     return $query;
    // }
}
