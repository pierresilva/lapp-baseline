<?php

namespace App\Models;

use App\Traits\SchemaTrait;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{

    use SchemaTrait;
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
    public function permissions()
    {
        return $this->hasMany('App\Models\Permission', 'user_id', 'id');
    }

    /**
     * Get the roleUsers for this model.
     */
    public function roles()
    {
        return $this->hasMany('App\Models\Role', 'user_id', 'id');
    }
}
