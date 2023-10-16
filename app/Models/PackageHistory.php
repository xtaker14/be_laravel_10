<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $package_history_id
 * @property integer $package_id
 * @property integer $status_id
 * @property string $created_date
 * @property string $modified_date
 * @property string $created_by
 * @property string $modified_by
 * @property Package $package
 * @property Packagedelivery $packagedelivery
 * @property Status $status
 */
class PackageHistory extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'packagehistory';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'package_history_id';

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
    protected $fillable = ['package_id', 'status_id', 'created_date', 'modified_date', 'created_by', 'modified_by'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function package(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Package::class, 'package_id', 'package_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function packagedelivery(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(\App\Models\PackageDelivery::class, 'package_history_id', 'package_history_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Status::class, 'status_id', 'status_id')
            ->where([
                'is_active'=>1,
                'status_group'=>Status::STATUS_GROUP['package'],
            ]);
    }
}
