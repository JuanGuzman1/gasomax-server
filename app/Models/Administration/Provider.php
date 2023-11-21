<?php

namespace App\Models\Administration;

use App\Models\File;
use Illuminate\Database\Eloquent\Relations\MorphMany;
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
    /**
     * Get all of the provider's files.
     */
    public function files(): MorphMany
    {
        return $this->morphMany(File::class, 'fileable');
    }
}
