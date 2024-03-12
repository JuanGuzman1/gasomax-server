<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    protected $table = 'configurations';
    protected $fillable = [
        'token_dropbox',
        'token_dropbox_refresh',
        'authorization_code_dropbox',
        'token_dropbox_expires_in'
    ];
}
