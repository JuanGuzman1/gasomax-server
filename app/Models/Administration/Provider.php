<?php

namespace App\Models\Administration;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    protected $table = "providers";
    protected $fillable = [
        "name",
        "type",
        "contact",
        "rfc",
        "address",
        "phone",
        "email",
        "accountingAccount"
    ];
}
