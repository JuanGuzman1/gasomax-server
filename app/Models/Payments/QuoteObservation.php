<?php

namespace App\Models\Payments;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuoteObservation extends Model
{
    protected $table = 'quote_observations';
    protected $fillable = [
        'message',
        'user_id',
        'quote_id'
    ];

    /**
     * Get the user that owns the observation.
     */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
