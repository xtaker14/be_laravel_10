<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $upload_id
 * @property string $code
 * @property string $total_waybill
 * @property string $filename
 * @property string $created_date
 * @property string $created_by
 * @property User $user
 */
class MasterWaybill extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'master_waybill';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'master_waybill_id';

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
    protected $fillable = ['master_waybill_id', 'code', 'total_waybill', 'filename', 'created_date', 'created_by'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function package(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Package::class, 'master_waybill_id', 'master_waybill_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by', 'users_id');
    }
}
