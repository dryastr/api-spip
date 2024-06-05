<?php

namespace App\Models\User;

use App\Traits\ColumnFilterer;
use App\Traits\ColumnSorter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;
    use ColumnSorter;
    use ColumnFilterer;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nip',
        'email',
        'fullname',
        'mobile',
        'username',
        'password',
        'email_verify_key',
        'email_verified_at',
        'avatar',
        'status',
        'remember_token',
        'created_by',
        'created_by_name',
        'updated_by',
        'updated_by_name',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes encrypted.
     *
     * @var array
     */
    protected $encrypted = [
        'username',
    ];

    /**
     * Set the username attribute.
     *
     * @param  string  $value
     *
     * @return void
     */
    public function setUsernameAttribute($value)
    {
        if (is_null($value) === false) {
            if (security()->decrypt($value) === false) {
                $this->attributes['username'] = security()->encrypt($value);
            } else {
                $this->attributes['username'] = $value;
            }
        }
    }

    /**
     * Get the username attribute.
     *
     * @param  string  $value
     *
     * @return string
     */
    public function getUsernameAttribute($value)
    {
        if ($decrypt = security()->decrypt($value)) {
            return $decrypt;
        }
        return $value;
    }

    /**
     * Get the permissions for the menu.
     */
    public function roles()
    {
        return $this->hasMany(UserHasRole::class);
    }

    /**
     * Get the registration.
     */
    public function registration()
    {
        return $this->hasOne(Registration::class, 'user_id', 'id');
    }

    /**
     * Permission.
     */
    public function permissions()
    {
        return $this->hasManyThrough(Permission::class, RoleHasPermission::class, 'user_id', 'role_id', 'id', 'role_id');
    }

    public function userHasRoles()
    {
        return $this->hasMany(UserHasRole::class, 'user_id', 'id');
    }
}
