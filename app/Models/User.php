<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * @property integer $users_id
 * @property integer $role_id
 * @property string $type
 * @property string $full_name
 * @property string $email
 * @property string $password
 * @property boolean $is_active
 * @property string $picture
 * @property string $created_date
 * @property string $modified_date
 * @property string $created_by
 * @property string $modified_by
 * @property Role $role
 * @property Usersclient[] $usersclients
 * @property Usershub[] $usershubs
 * @property Userspartner[] $userspartners
 */
class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'users_id';

    /**
     * Indicates if the model should be timestamped.
     * 
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'role_id', 
        'type', 
        'full_name', 
        'email', 
        'password', 
        'is_active', 
        'picture', 
        'created_date', 
        'modified_date', 
        'created_by', 
        'modified_by'
    ];
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Role::class, 'role_id', 'role_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function usersclients(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Userclient::class, 'users_id', 'users_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function usershubs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Userhub::class, 'users_id', 'users_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userspartners(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Userpartner::class, 'users_id', 'users_id');
    }
    
    // public function organization()
    // {
    //     $res = [];
    //     if(!empty($this->usersclients)){
    //         foreach ($this->usersclients as $key => $val) {
    //             if(!empty($val->client)){
    //                 foreach ($val->client as $key2 => $val2) {

    //                 }
    //             }
    //         }
    //     }
    //     return $res;
    // } 

    public function getOrganizationIdsAttribute()
    {
        $client_table = (new Client)->getTable();

        if($this->role->name !== 'driver') {
            return $this->usersclients->pluck('client.organization_id')->unique()->all() ?? null;
        } else {
            return $this->usersclients->pluck('client.organization_id')->first() ?? null;
        }
    }

    public function getClientIdsAttribute()
    {
        $client_table = (new Client)->getTable();

        if($this->role->name !== 'driver') {
            return $this->usersclients->pluck('client_id')->unique()->all() ?? null;
        } else {
            return $this->usersclients->pluck('client_id')->first() ?? null;
        }
    }

    public function getHubIdsAttribute()
    {
        $hub_table = (new Hub)->getTable();

        if($this->role->name !== 'driver') {
            return $this->usershubs->pluck('hub_id')->unique()->all() ?? null;
        } else {
            // return $this->usershubs->where("$hub_table.is_active", 1)->pluck('hub_id')->first() ?? null;
            return $this->usershubs->pluck('hub_id')->first() ?? null;
        }
    }

    public function getCouriers()
    {
        if ($this->role->name == 'driver') {
            $userPartner = $this->userspartners->first(); 

            if ($userPartner) {
                $partner = $userPartner->partner; 
                return $partner->couriers->first(); 
            }

            return null; 
        } else {
            $couriers = collect();

            foreach ($this->userspartners as $userpartner) {
                foreach ($userpartner->partner->couriers as $courier) {
                    $couriers->push($courier);
                }
            }

            return $couriers;
        }
    }
}
