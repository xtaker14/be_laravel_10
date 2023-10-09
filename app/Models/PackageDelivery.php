<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $package_delivery_id
 * @property integer $package_id
 * @property integer $package_history_id
 * @property string $information
 * @property string $notes
 * @property string $accept_cod
 * @property string $e_signature
 * @property string $photo
 * @property string $created_date
 * @property string $modified_date
 * @property string $created_by
 * @property string $modified_by
 * @property Package $package
 * @property PackageHistory $packagehistory
 */
class PackageDelivery extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'packagedelivery';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'package_delivery_id';

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
    protected $fillable = ['package_id', 'package_history_id', 'information', 'notes', 'accept_cod', 'e_signature', 'photo', 'created_date', 'modified_date', 'created_by', 'modified_by'];

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
    public function packagehistory(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\PackageHistory::class, 'package_history_id', 'package_history_id');
    } 
}
