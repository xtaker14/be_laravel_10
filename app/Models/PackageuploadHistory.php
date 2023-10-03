<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageuploadHistory extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'package_upload_history';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'upload_id';

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
     * @var array
     */
    protected $fillable = ['code', 'total_waybill', 'filename', 'created_date', 'created_by'];
}
