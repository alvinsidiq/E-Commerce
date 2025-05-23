<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;

use Filament\Tables\Actions\DeleteAction;
use App\Models\Order;

use App\Filament\Resources\OrderResource;

class OrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'orders';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
               
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('id')
                ->label('Order ID')
                ->searchable(),
            TextColumn::make('grand_total')
                ->money('IDR'),
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
            TextColumn :: make('user.name')
            ->label('customer')
            ->searchable(),
        ])
        ->filters([])
        ->headerActions([])
        ->actions([
            Action::make('view_order')
                ->url(fn (Order $record): string => OrderResource::getUrl('view', ['record' => $record]))
                ->color('info')
                ->icon('heroicon-o-eye'),
            DeleteAction::make(),
        ])
        ->bulkActions([]);
    }
}
