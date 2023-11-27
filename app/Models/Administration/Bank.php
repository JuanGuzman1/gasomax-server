<?php

namespace App\Models\Administration;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $table = 'banks';
    protected $fillable = ['name'];
}
