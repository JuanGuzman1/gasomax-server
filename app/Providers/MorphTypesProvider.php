<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class MorphTypesProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Relation::enforceMorphMap([
            'provider' => 'App\Models\Administration\Provider',
            'purchaseRequest' => 'App\Models\Payments\PurchaseRequest',
            'quote' => 'App\Models\Payments\Quote',
            'user' => 'App\Models\Users\User',
        ]);
    }
}
