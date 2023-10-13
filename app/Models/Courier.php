<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $courier_id
 * @property integer $partner_id
 * @property integer $hub_id
 * @property integer $users_id
 * @property string $code
 * @property string $phone
 * @property string $vehicle_type
 * @property string $vehicle_number
 * @property string $created_date
 * @property string $modified_date
 * @property string $created_by
 * @property string $modified_by
 * @property Partner $partner
 * @property Hub $hub
 * @property User $user
 * @property Routing[] $routings
 */
class Courier extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'courier';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'courier_id';

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
    protected $fillable = ['partner_id', 'hub_id', 'users_id', 'code', 'name', 'phone', 'vehicle_type', 'vehicle_number', 'created_date', 'modified_date', 'created_by', 'modified_by'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function partner(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Partner::class, 'partner_id', 'partner_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function hub(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Hub::class, 'hub_id', 'hub_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'users_id', 'users_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function routings(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Routing::class, 'courier_id', 'courier_id');
    }
}
