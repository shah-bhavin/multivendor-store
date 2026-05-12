<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('customButton')
                ->label('Export Orders')
                ->icon('heroicon-m-sparkles')
                ->color('success')
                ->url(route('orders.export')),

            CreateAction::make(),
        ];
    }
}
