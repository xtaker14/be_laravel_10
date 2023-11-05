<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $package_api_id
 * @property integer $package_id
 * @property string $action
 * @property integer $status
 * @property string $message
 * @property string $created_date
 * @property string $modified_date
 * @property string $created_by
 * @property string $modified_by
 * @property Package $package
 */
class PackageApi extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'packageapi';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'package_api_id';

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

    const ACTION_WMS = [
        'post_tracking' => 'POST_TRACKING_NUMBER',
    ];

    const PROCESSED = 0;
    const COMPLETED = 1;
    const FAILED = 2;

    /**
     * @var array
     */
    protected $fillable = ['package_id', 'action', 'status', 'message', 'created_date', 'modified_date', 'created_by', 'modified_by'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function package(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Package::class, 'package_id', 'package_id');
    } 
}
