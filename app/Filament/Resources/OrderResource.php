<?php

namespace App\Filament\Resources;
use App\Filament\Resources\OrderResource\RelationManagers\AddressRelationManager;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Actions\CreateAction; 
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Product;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction; 

class OrderResource extends Resource
{
    public static ?int $navigationSort = 5;
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                ->columnSpanFull()
                ->schema([
                    Section :: make ('Order Information')
                    ->columns(2)
                    ->schema([
                        Select:: make ('user_id')
                        ->label('Customer')
                        ->relationship('user','name')
                        ->searchable()
                        ->preload()
                        ->required(),
                        Select:: make ('payment_method')
                        ->options([
                            'stripe' => 'Stripe',
                            'cod'=> 'Cash on Delivery',
                        ])
                        ->required(),
                        Select :: make ('payment_status')
                        ->options([
                            'pending'=>'Pending',
                            'paid'=> 'Paid',
                            'failed'=> 'Failed',
                        ])
                        ->default('pending')
                        ->required(),
                        ToggleButtons :: make ('status')
                        ->inline()
                        ->default('new')
                        ->required()
                        ->options([
                            'new' => 'New',
                            'processing'=> 'Processing',
                            'shipped'=> 'Shipped',
                            'delivered'=> 'Delivered',
                            'cancelled'=> 'Cancelled',
                        ])
                        ->colors([
                            'new' => 'info',
                            'processing'=> 'warning',
                            'shipped'=> 'success',
                            'cancelled'=> 'danger',
                        ])
                        ->icons([
                            'new' => 'heroicon-m-sparkles',
                            'processing' => 'heroicon-m-arrow-path',
                            'shipped' => 'heroicon-m-truck',
                            'delivered' => 'heroicon-m-check-badge',
                            'cancelled' => 'heroicon-m-x-circle', 
                        ]),
                        Select :: make ('currency')
                        ->options([
                            'IDR' => 'IDR',
                            'USD' => 'USD',
                            'EUR' => 'EUR',
                            'GBP' => 'GBP',
                        ])
                           ->default('IDR')
                           ->required(), 
                        Select :: make ('shipping_method')
                        ->options ([
                            'jne' => 'JNE',
                            'tiki' => 'TIKI',
                            'pos' => 'POS',
                            'jnt' => 'JNT',
                        ]),
                        Textarea :: make ('notes')
                        ->columnSpanFull(),

                    ]),
                    Section :: make ('Order Items')
                    ->schema ([
                        Repeater::make ('items')
                        ->relationship('items')
                        ->schema([
                            Select :: make ('product_id')
                            ->relationship('product','name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->distinct()
                            ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                            ->reactive()
                            ->afterStateUpdated(function ($state, Set $set) {
                                $product = Product::find($state);
                                $set('unit_amount', $product?->price ?? 0);
                                $set('total_amount', $product?->price ?? 0);
                            })
                            ->columnSpan(4),
                            TextInput :: make ('quantity')
                            ->numeric()
                            ->required()
                            ->default(1)
                            ->reactive()
                            ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                $set('total_amount', $state * $get('unit_amount'));
                            })
                            ->columnSpan(2),
                            TextInput :: make ('unit_amount')
                            ->numeric()
                            ->required()
                            ->disabled()
                            ->dehydrated()
                            ->columnSpan(3),
                            TextInput :: make ('total_amount')
                            ->numeric()
                            ->required()
                            ->disabled()
                            ->dehydrated()
                            ->columnSpan(3),
                        ])
                        ->columns(12),
                        Placeholder :: make ('grand_total')
                        ->label ('Grand Total')
                        ->content ( function (Get $get , Set $set): string {
                            $total =0 ;
                            if (!$repeaters = $get ('items')) {
                                return number_format($total,2).'IDR';
                            }
                            foreach($repeaters as $key => $repeater){
                                $total += $get ("items.{$key}.total_amount");
                            }
                            $set('grand_total', $total);
                            return number_format($total,2).'IDR';

                        }),
                        Hidden :: make ('grand_total')
                        ->default(0),
                    ]),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn :: make ('user.name')
                ->label('Customer')
                ->searchable()
                ->sortable(),
                TextColumn :: make ('grand_total')
                ->numeric()
                ->money('IDR')
                ->sortable(),
                TextColumn :: make ('payment_method')
                ->searchable()
                ->sortable(),
                TextColumn:: make ('payment_status')
                ->searchable()
                ->sortable(),
                SelectColumn :: make ('status')
                ->options([
                    'new' => 'New',
                    'processing'=> 'Processing',
                    'shipped'=> 'Shipped',
                    'delivered'=> 'Delivered',
                    'cancelled'=> 'Cancelled',
                ])
                ->searchable()
                ->sortable(),
                TextColumn :: make ('currency')
                ->searchable()
                ->sortable(),
                TextColumn :: make('shipping_method')
                ->searchable()
                ->sortable(),
                TextColumn :: make ('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make('updated_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                ActionGroup :: make([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
                ]),
            ]);
           
            
    }

    public static function getRelations(): array
    {
        return [
            AddressRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
{
    return static::getModel()::count();
}

public static function getNavigationBadgeColor(): ?string
{
    $count = static::getModel()::count();
    return $count > 10 ? 'danger' : 'success';
}

}
