<?php

namespace App\Filament\Resources\SubscriptionPlans\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class SubscriptionPlanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                TextInput::make('billing_cycle')
                    ->required()
                    ->default('monthly'),
                TextInput::make('product_limit')
                    ->required()
                    ->numeric()
                    ->default(10),
                Toggle::make('featured_products')
                    ->required(),
                Toggle::make('priority_support')
                    ->required(),
                Toggle::make('analytics_access')
                    ->required(),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
