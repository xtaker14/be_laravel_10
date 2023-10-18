<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// use App\Traits\JoinTableTrait; 

/**
 * @property integer $routing_delivery_id
 * @property integer $routing_id
 * @property integer $delivery
 * @property integer $delivered
 * @property integer $undelivered
 * @property integer $total_delivery
 * @property float $total_cod_price
 * @property float $total_shipping_price
 * @property float $total_package_price
 * @property string $created_date
 * @property string $modified_date
 * @property string $created_by
 * @property string $modified_by
 * @property Routing $routing
 */
class RoutingDelivery extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'routingdelivery';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'routing_delivery_id';

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
    protected $fillable = ['routing_delivery_id', 'routing_id', 'delivery', 'delivered','undelivered','total_delivery', 'total_cod_price', 'total_shipping_price', 'total_package_price', 'created_date', 'modified_date', 'created_by', 'modified_by'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function routing(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Routing::class, 'routing_id', 'routing_id');
    } 
}
