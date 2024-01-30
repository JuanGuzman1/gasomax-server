<?php

namespace App\Models\Users;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPermission extends Model
{
    protected $table = "user_permissions";
    protected $fillable = ['user_id', 'permission_id'];

    /**
     * Get the permission that owns the user.
     */
    public function permission(): BelongsTo
    {
        return $this->belongsTo(Permission::class)->with('module');
    }
}
