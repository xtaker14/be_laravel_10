<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Traits\UserPermissionsTrait;

/**
 * @property integer $users_id
 * @property integer $role_id
 * @property string $gender
 * @property string $username
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
 */
class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, UserPermissionsTrait;

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

    const ACTIVE = 1;
    const NOT_ACTIVE = 0;

    /**
     * @var array
     */
    protected $fillable = [
        'role_id', 
        'gender', 
        'username', 
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

    // ---- permission

    public function hasRole($str): bool
    {
        return $this->role && $this->role->name === $str;
    }

    public function hasPermission($str): bool
    {
        if (!$this->role) return false;

        foreach ($this->role->privileges as $privilege) {
            if ($privilege->permission && $privilege->permission->name === $str) {
                return true;
            }
        }

        return false;
    }

    public function hasFeature($str): bool
    {
        if (!$this->role) return false;

        foreach ($this->role->privileges as $privilege) {
            if ($privilege->feature && $privilege->feature->name === $str) {
                return true;
            }
        }

        return false;
    }

    public function getPrivileges()
    {
        if (!$this->role) return null;

        if ($this->role->privileges->isEmpty()) return null;

        return $this->role->privileges->toBase();
    }

    public function getPermissions()
    {
        $privileges = $this->getPrivileges();

        if (!$privileges) return null;

        $permissions = $privileges->filter(function ($privilege) {
            return !empty($privilege->permission);
        });

        return $permissions;
    }

    public function getFeatures()
    {
        $privileges = $this->getPrivileges();

        if (!$privileges) return null;

        $features = $privileges->filter(function ($privilege) {
            return !empty($privilege->feature);
        });

        return $features;
    }

    public function getMenus()
    {
        $privileges = $this->getPrivileges();

        if (!$privileges) return null;

        $menus = collect();

        foreach ($privileges as $privilege) {
            if (!empty($privilege->feature)) {
                foreach ($privilege->feature->menus as $menu) {
                    $menus->push($menu);
                }
            }

            if (!empty($privilege->permission)) {
                foreach ($privilege->permission->menus as $menu) {
                    $menus->push($menu);
                }
            }
        }

        return $menus;
    }

    public function isAdmin() {
        return in_array($this->role->name,['DEVELOPMENT', 'CONTROL_TOWER']);
    }
}
