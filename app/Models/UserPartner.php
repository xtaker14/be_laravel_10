<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $users_partner_id
 * @property integer $users_id
 * @property integer $partner_id
 * @property string $created_date
 * @property string $modified_date
 * @property string $created_by
 * @property string $modified_by
 * @property User $user
 * @property Partner $partner
 */
class UserPartner extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'userspartner';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'users_partner_id';

    /**
     * Indicates if the model should be timestamped.
     * 
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['users_id', 'partner_id', 'created_date', 'modified_date', 'created_by', 'modified_by'];

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
    public function partner(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\Partner', null, 'partner_id');
    }
}
