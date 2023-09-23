<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
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
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

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
        return $this->belongsTo('App\Models\Role', null, 'role_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function usersclients(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\Usersclient', 'users_id', 'users_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function usershubs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\Usershub', 'users_id', 'users_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userspartners(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\Userspartner', 'users_id', 'users_id');
    } 

    public function getAllRolesName() {
        
        // $user->getRoleNames();
        
        $roles=[];
        foreach ($this->roles as $key => $val) {
            $roles[] = [
                'role' => $val->name,
                'permission' => $val->permissions->pluck('name'),
            ];
        }

        return $roles;
    } 

    public function getAllPermissionsName() {
        
        // $user->getPermissionNames();

        // Direct permissions
        // $user->getDirectPermissions()->pluck('name');

        // Permissions inherited from the user's roles
        // $user->getPermissionsViaRoles()->pluck('name');

        // All permissions which apply on the user (inherited and direct)
        // $user->getAllPermissions()->pluck('name');

        $permissions = $this->getDirectPermissions()->pluck('name');
        return $permissions;
    } 
}
