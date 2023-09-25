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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Status::class, 'status_id', 'status_id');
    }
}
