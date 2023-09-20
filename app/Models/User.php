<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
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
