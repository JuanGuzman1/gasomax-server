<?php

namespace App\Models\Users;

use App\Models\Module;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserModule extends Model
{
    protected $table = 'user_modules';
    protected $fillable = ['user_id', 'module_id'];


    /**
     * Get the module that owns the user.
     */
    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }
}
