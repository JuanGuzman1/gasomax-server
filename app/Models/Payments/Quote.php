<?php

namespace App\Models\Payments;

use App\Models\Administration\Provider;
use App\Models\Administration\ProviderAccount;
use App\Models\Users\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Quote extends Model
{
    protected $table = 'quotes';
    protected $fillable = [
        'title',
        'petitioner_id',
        'quote_concept_id',
        'description',
        'numProviders',
        'recommendedProviders',
        'line',
        'unit',
        'rejectQuotes',
        'approvedAmount',
        'provider_id',
        'provider_account_id',
        'paymentWithoutInvoice',
        'status'
    ];


    /**
     * Get the provider of the purchaseRequest.
     */
    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }

    /**
     * Get the provider of the purchaseRequest.
     */
    public function providerAccount(): BelongsTo
    {
        return $this->belongsTo(ProviderAccount::class, 'provider_account_id');
    }

    /**
     * Get the user that owns the purchaseRequest.
     */
    public function petitioner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'petitioner_id');
    }


    /**
     * Get the user that owns the purchaseRequest.
     */
    public function quoteConcept(): BelongsTo
    {
        return $this->belongsTo(QuoteConcept::class, 'quote_concept_id');
    }
}
