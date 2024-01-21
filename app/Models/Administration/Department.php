<?php

namespace App\Models\Administration;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = "departments";
    protected $fillable = ['name'];
}
