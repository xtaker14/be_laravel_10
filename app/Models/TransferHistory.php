<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $transfer_history_id
 * @property integer $transfer_id
 * @property integer $status_id
 * @property string $created_date
 * @property string $modified_date
 * @property string $created_by
 * @property string $modified_by
 * @property Transfer $transfer
 * @property Status $status
 */
class TransferHistory extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'transferhistory';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'transfer_history_id';

    /**
     * Indicates if the model should be timestamped.
     * 
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['transfer_id', 'status_id', 'created_date', 'modified_date', 'created_by', 'modified_by'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function transfer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Transfer::class, 'transfer_id', 'transfer_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Status::class, 'status_id', 'status_id');
    }
}
