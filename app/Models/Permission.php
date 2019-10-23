<?php

namespace App\Models;

use App\Traits\SchemaTrait;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use SchemaTrait;
    //
    protected $table = 'permissions';
}
