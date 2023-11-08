<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $status_id
 * @property string $code
 * @property integer $status_order
 * @property string $status_group
 * @property string $name
 * @property string $color
 * @property boolean $is_active
 * @property string $created_date
 * @property string $modified_date
 * @property string $created_by
 * @property string $modified_by
 * @property array STATUS_GROUP
 * @property array STATUS
 * @property Packagehistory[] $packagehistories
 * @property Routinghistory[] $routinghistories
 * @property Transferhistory[] $transferhistories
 */
class Status extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'status';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'status_id';

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

    // return status group
    CONST STATUS_GROUP = [
        'routing' => 'routing',
        'package' => 'package',
    ];

    // return status code
    CONST STATUS = [
        self::STATUS_GROUP['routing'] => [ 
            'assigned' => 'ASSIGNED',
            'inprogress' => 'INPROGRESS',
            'collected' => 'COLLECTED',
        ],

        self::STATUS_GROUP['package'] => [
            'entry' => 'ENTRY',
            'routing' => 'ROUTING',
            'ondelivery' => 'ONDELIVERY',
            'delivered' => 'DELIVERED',
            'undelivered' => 'UNDELIVERED',
            'return' => 'RETURN',
        ],
    ];

    CONST ACTIVE = 1;
    const NOT_ACTIVE = 0;

    /**
     * @var array
     */
    protected $fillable = ['code', 'name', 'color', 'is_active', 'status_order', 'label', 'status_group', 'created_date', 'modified_date', 'created_by', 'modified_by'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function packagehistories(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\PackageHistory::class, 'status_id', 'status_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function routinghistories(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\RoutingHistory::class, 'status_id', 'status_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transferhistories(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\TransferHistory::class, 'status_id', 'status_id');
    }
}
