<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;

class AddressRelationManager extends RelationManager
{
    protected static string $relationship = 'address';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
               TextInput :: make ('first_name')
               ->required()
               ->maxLength(255),
               TextInput :: make ('last_name')
               ->required()
               ->maxLength(255),
               TextInput :: make ('phone')
               ->tel()
               ->required()
               ->maxLength(20),
               TextInput :: make ('city')
               ->required()
               ->maxLength(255),
               TextInput :: make ('state')
               ->required ()
               ->maxLength(255),
               TextInput :: make ('zip_code')
               ->numeric()
               ->required()
               ->maxLength(10),
               Textarea :: make ('street_address')
               ->required()
               ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn :: make ('full_name')
                ->label('Full Name'),
                TextColumn :: make ('phone'),
                TextColumn :: make ('city'),
                TextColumn :: make ('state'),
                TextColumn :: make ('zip_code'),
                TextColumn :: make ('street_address'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
