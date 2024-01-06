<?php

namespace App\Models\Payments;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class PurchaseRequestObservation extends Model
{
    protected $table = 'purchase_request_observations';
    protected $fillable = [
        'message',
        'user_id',
        'purchase_request_id'
    ];

    /**
     * Get the user that owns the observation.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
