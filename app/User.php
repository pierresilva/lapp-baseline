<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use pierresilva\Sentinel\Traits\SentinelTrait;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens, SentinelTrait, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $appends = [
        'avatar_url',
        'role',
        'ability'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'active',
        'activation_token',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'activation_token',
        'roles',
        'permissions',
    ];

    /**
     * Return user avatar URL
     *
     * @return string
     */
    public function getAvatarUrlAttribute()
    {
        return 'uploads/avatars/' . $this->id . '/' . $this->avatar;
    }

    public function getRoleAttribute()
    {
        return $this->getRoles();
    }

    public function getAbilityAttribute()
    {
        return $this->getPermissions();
    }
}
