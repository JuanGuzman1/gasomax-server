<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = "departments";
    protected $fillable = ['name'];
}
