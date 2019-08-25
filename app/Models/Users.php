<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The database primary key value.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'active',
        'avatar',
        'permission_user',
        'role_user'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * Get the permissionUser for this model.
     */
    public function permissionUser()
    {
        return $this->hasMany('App\Models\PermissionUser', 'user_id', 'id');
    }

    /**
     * Get the roleUsers for this model.
     */
    public function roleUsers()
    {
        return $this->hasMany('App\Models\RoleUser', 'user_id', 'id');
    }
}
