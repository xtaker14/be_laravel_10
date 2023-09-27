<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $users_hub_id
 * @property integer $users_id
 * @property integer $hub_id
 * @property string $created_date
 * @property string $modified_date
 * @property string $created_by
 * @property string $modified_by
 * @property User $user
 * @property Hub $hub
 */
class UserHub extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'usershub';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'users_hub_id';

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
    protected $fillable = ['users_id', 'hub_id', 'created_date', 'modified_date', 'created_by', 'modified_by'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'users_id', 'users_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function hub(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Hub::class, 'hub_id', 'hub_id');
    }
}
