<?php

namespace App\Filament\Widgets;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Filament\Resources\OrderResource;
use App\Models\Order;
class LatestOrders extends BaseWidget
{
   protected string|array|int $columnSpan ='full';
   protected static ?int $sort = 2;
    public function table(Table $table): Table
    {
        
        return $table
           ->query(OrderResource::getEloquentQuery())
           ->defaultPaginationPageOption(5)
           ->defaultSort('created_at', 'desc')
           ->columns([ 
            TextColumn::make('id')
                ->label('Order ID')
                ->searchable(),
            TextColumn::make('grand_total')
                ->money('INR'),
            TextColumn::make('status')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'new' => 'info',
                    'processing' => 'warning',
                    'shipped' => 'success',
                    'delivered' => 'success',
                    'cancelled' => 'danger',
                })
                ->icon(fn (string $state): string => match ($state) {
                    'new' => 'heroicon-m-sparkles',
                    'processing' => 'heroicon-m-arrow-path',
                    'shipped' => 'heroicon-m-truck',
                    'delivered' => 'heroicon-m-check-badge',
                    'cancelled' => 'heroicon-m-x-circle',
                })
                ->sortable(),
            TextColumn::make('payment_method')
                ->sortable()
                ->searchable(),
            TextColumn::make('payment_status')
                ->badge()
                ->sortable()
                ->searchable(),
            TextColumn::make('created_at')
                ->label('Order Date')
                ->dateTime(),
            TextColumn::make('user.name')
                ->label('Customer')
                ->searchable(),
           ])
           ->actions([
            Action:: make('View_Order')
            ->url(fn (Order $record): string => OrderResource::getUrl('view', ['record' => $record]))
            ->icon('heroicon-o-eye'),
           ]);
    }
}
