<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function beforeCreate(): void
    {
        // Check for active subscription
        $subscription = auth()->user()->activeSubscription;
        
        if (! $subscription) {
            // Throws a 403 Forbidden error
            abort(403, 'Active subscription required.');
        }
        
        $currentProducts = auth()->user()->products()->count();

        $limit = $subscription->plan->product_limit;

        if ($currentProducts >= $limit) {
            session()->flash(
                'error',
                'Product limit reached.'
            );

            return;
        }
    }
}
