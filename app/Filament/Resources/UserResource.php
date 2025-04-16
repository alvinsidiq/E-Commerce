<?php

namespace App\Filament\Resources;
use App\Filament\Resources\UserResource\RelationManagers\OrdersRelationManager;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;

use Illuminate\Contracts\Support\Htmlable; 
use Illuminate\Database\Eloquent\Model;

class UserResource extends Resource
{
    public static ?int $navigationSort = 1;
    protected static ?string $model = User::class;
    public static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput :: make('name')->required(),
                TextInput :: make('email')
                    ->label('Email Address')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                DateTimePicker :: make ('email_verified_at')
                    ->label('Email Verified At')
                    ->default(now()),
                TextInput :: make('password')
                    ->password()
                    ->dehydrated(fn($state)=> filled($state))
                    ->required(fn(string $context ): bool =>$context==='create'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn :: make('name')->searchable(),
                TextColumn :: make('email')->searchable(),
                TextColumn :: make('email_verified_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn :: make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                ActionGroup :: make([
                    ViewAction :: make(),
                    EditAction :: make(),
                    DeleteAction :: make(),

                ]),
            ]);
            

            


            // ->filters([
            //     //
            // ])
            // ->actions([
            //     Tables\Actions\EditAction::make(),
            // ])
            // ->bulkActions([
            //     Tables\Actions\BulkActionGroup::make([
            //         Tables\Actions\DeleteBulkAction::make(),
            //     ]),
            // ]);
    }

    public static function getRelations(): array
    {
        return [
            OrdersRelationManager::class,
        ];
    }
    

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getGlobalSearchResultTitle(Model $record): Htmlable|string
{
    return $record->name;
}

public static function getGloballySearchableAttributes(): array
{
    return ['name', 'email'];
}

}
