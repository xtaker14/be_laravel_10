<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $inbound_type_id
 * @property string $name
 * @property string $created_date
 * @property string $modified_date
 * @property string $created_by
 * @property string $modified_by
 * @property Inbound[] $inbounds
 */
class InboundType extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'inboundtype';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'inbound_type_id';

    /**
     * Indicates if the model should be timestamped.
     * 
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['name', 'created_date', 'modified_date', 'created_by', 'modified_by'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function inbounds(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Inbound::class, 'inbound_type_id', 'inbound_type_id');
    }
}
