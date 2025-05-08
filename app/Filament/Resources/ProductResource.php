<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Illuminate\Support\Str;
use Filament\Forms\Set;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Filters\SelectFilter;
class ProductResource extends Resource
{
    public static ?int $navigationSort = 4;
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(3)
            ->schema([
                Group :: make()
                ->columnSpan(2)
                ->schema([
                    Section :: make ('Product Details')
                    ->columns(2)
                    ->schema([
                        TextInput :: make ('name')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (string $operation, $state, Set $set) {
                            if ($operation !== 'create') {
                                return;
                            }
                            $set('slug', Str::slug($state));
                        }),
                    TextInput :: make ('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(\App\Models\Product::class, 'slug', ignoreRecord: true)
                    -> disabled()
                    ->dehydrated(),
                    MarkdownEditor :: make ('description')
                    ->fileAttachmentsDisk('public')
                    ->fileAttachmentsDirectory('products')
                    ->columnSpanFull(),
                    ]),
                Section :: make ('images')
                ->schema([
                    FileUpload :: make ('images')
                    ->multiple()
                    ->directory('products')
                    ->maxFiles(5)
                    ->dehydrated()
                    ->reorderable()
                    ->statePath('images')
                    ->required()
                    ->visibility('public'),
                ]),

            ]),
            Group :: make()
            ->columnSpan(1)
            ->schema([
                Section :: make('Price')
                ->schema([
                    TextInput :: make ('price')
                    ->numeric()
                    ->required()
                    ->prefix('IDR'),
                ]),
            Section :: make('Associations')
            ->schema([
                Select :: make ('category_id')
                ->relationship(name: 'category', titleAttribute:'name')
                ->required()
                ->searchable()
                ->preload(),
                Select :: make ('brand_id')
                ->relationship(name: 'brand', titleAttribute: 'name')
                ->required()
                ->searchable()
                ->preload(),
            ]),
            Section :: make ('status')
            ->schema([
                Toggle::make ('in_stock')
                ->required()
                ->default(true),
                Toggle::make ('is_active')
                ->required()
                ->default(true),
                Toggle::make ('is_featured')
                ->required(),
                Toggle::make ('on_sale')
                ->required(),
            ]),
        ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
               TextColumn::make('name')
               ->searchable(),
               TextColumn::make('category.name')
               ->sortable(),
               TextColumn::make('brand.name')
               ->sortable(),
               TextColumn::make('price')
               ->money('IDR'),
               IconColumn::make('is_featured')
               ->boolean(),
               IconColumn::make('on_sale')
               ->boolean(),
               IconColumn::make('in_stock')
               ->boolean(),
               IconColumn::make('is_active')
               ->boolean(),
               TextColumn::make('created_at')
               ->dateTime()
               ->sortable()
               ->toggleable(isToggledHiddenByDefault: true),
               TextColumn::make('updated_at')
               ->dateTime()
               ->sortable()
               ->toggleable(isToggledHiddenByDefault: true),

            ])
            ->filters([
                SelectFilter::make('category')
                    ->relationship('category', 'name'),
                SelectFilter::make('brand')
                    ->relationship('brand', 'name'),
            ])
           
            ->actions([
               ActionGroup :: make([
                   ViewAction :: make(),
                   EditAction :: make(),
                   DeleteAction :: make(),
               ]),
            ]);
           
         
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
