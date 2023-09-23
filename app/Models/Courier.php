<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $courier_id
 * @property integer $partner_id
 * @property string $code
 * @property string $phone
 * @property string $vehicle_type
 * @property string $vehicle_number
 * @property string $created_date
 * @property string $modified_date
 * @property string $created_by
 * @property string $modified_by
 * @property Partner $partner
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
     * @var array
     */
    protected $fillable = ['partner_id', 'code', 'phone', 'vehicle_type', 'vehicle_number', 'created_date', 'modified_date', 'created_by', 'modified_by'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function partner(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\Partner', null, 'partner_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function routings(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\Routing', null, 'courier_id');
    }
}
