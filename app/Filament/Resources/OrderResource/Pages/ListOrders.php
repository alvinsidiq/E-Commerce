<?php

namespace App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\Widgets\OrderStats;
use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            // Actions\CreateAction::make(),
            OrderStats::class,
        ];
    }
    public function getTabs(): array
    {
        return [
            'all' => Tab::make(),
            'new' => Tab::make()->query(fn (Builder $query) => $query->where('status', 'new')),
            'processing' => Tab::make()->query(fn (Builder $query) => $query->where('status', 'processing')),
            'shipped' => Tab::make()->query(fn (Builder $query) => $query->where('status', 'shipped')),
            'delivered' => Tab::make()->query(fn (Builder $query) => $query->where('status', 'delivered')),
            'cancelled' => Tab::make()->query(fn (Builder $query) => $query->where('status', 'cancelled')),
        ];
    }
}
