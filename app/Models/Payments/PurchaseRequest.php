<?php

namespace App\Models\Payments;


use App\Models\File;
use App\Models\Users\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class PurchaseRequest extends Model
{
    protected $table = 'purchase_requests';
    protected $fillable = [
        'status',
        'quote_id',
        'petitioner_id',
        'paymentDate',
        'title',
        'paymentAmount',
        'totalPaymentApproved',
        'totalPaymentModified'
    ];

    /**
     * Get the quote of the purchaseRequest.
     */
    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class);
    }

    /**
     * Get the user that owns the purchaseRequest.
     */
    public function petitioner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'petitioner_id');
    }


    /**
     * Get the observations of the purchaseRequest.
     */
    public function observations(): HasMany
    {
        return $this->hasMany(PurchaseRequestObservation::class);
    }

    /**
     * Get all of the provider's purchaseRequest.
     */
    public function files(): MorphMany
    {
        return $this->morphMany(File::class, 'fileable');
    }
}
