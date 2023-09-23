<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $users_client_id
 * @property integer $users_id
 * @property integer $client_id
 * @property string $created_date
 * @property string $modified_date
 * @property string $created_by
 * @property string $modified_by
 * @property User $user
 * @property Client $client
 */
class UserClient extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'usersclient';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'users_client_id';

    /**
     * Indicates if the model should be timestamped.
     * 
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['users_id', 'client_id', 'created_date', 'modified_date', 'created_by', 'modified_by'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\User', 'users_id', 'users_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\Client', null, 'client_id');
    }
}
