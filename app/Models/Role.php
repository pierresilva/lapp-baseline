<?php

namespace App\Models;

use App\Traits\SchemaTrait;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    //
    use SchemaTrait;

    protected $table = 'roles';
}
