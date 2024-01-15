<?php

namespace App\Models\Payments;

use App\Models\Administration\Provider;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseRequest extends Model
{
    protected $table = 'purchase_requests';
    protected $fillable = [
        'extraordinary',
        'station',
        'business',
        'paymentMethod',
        'status',
        'pettyCash',
        'provider_id',
        'petitioner_id'
    ];

    /**
     * Get the provider of the purchaseRequest.
     */
    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }

    /**
     * Get the user that owns the purchaseRequest.
     */
    public function petitioner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'petitioner_id');
    }

    /**
     * Get the details of the purchaseRequest.
     */
    public function details(): HasMany
    {
        return $this->hasMany(PurchaseRequestDetail::class, 'purchase_request_id', 'id');
    }

    /**
     * Get the observations of the purchaseRequest.
     */
    public function observations(): HasMany
    {
        return $this->hasMany(PurchaseRequestObservation::class);
    }
}
