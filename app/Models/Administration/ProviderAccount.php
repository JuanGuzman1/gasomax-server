<?php

namespace App\Models\Administration;

use App\Models\Administration\Bank;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProviderAccount extends Model
{
    protected $table = 'provider_accounts';
    protected $fillable = [
        'bankAccount',
        'clabe',
        'primary'
    ];

    /**
     * Get the bank that owns the providerAccount.
     */
    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class);
    }
}
