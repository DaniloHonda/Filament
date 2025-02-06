<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CountryResource\Pages;
use App\Models\Country;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CountryResource extends Resource
{
    protected static ?string $model = Country::class;

    protected static ?string $navigationGroup = 'World';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-globe-americas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(100),
                TextInput::make('iso2')
                    ->maxLength(2),
                TextInput::make('numeric_code')
                    ->maxLength(3),
                TextInput::make('phonecode')
                    ->tel()
                    ->maxLength(255),
                TextInput::make('capital')
                    ->maxLength(255),
                TextInput::make('currency')
                    ->maxLength(255),
                TextInput::make('currency_name')
                    ->maxLength(255),
                TextInput::make('currency_symbol')
                    ->maxLength(255),
                TextInput::make('tld')
                    ->maxLength(255),
                TextInput::make('native')
                    ->maxLength(255),
                TextInput::make('region')
                    ->maxLength(255),
                TextInput::make('subregion')
                    ->maxLength(255),
                Textarea::make('timezones')
                    ->columnSpanFull(),
                Textarea::make('translations')
                    ->columnSpanFull(),
                TextInput::make('latitude')
                    ->numeric(),
                TextInput::make('longitude')
                    ->numeric(),
                TextInput::make('emoji')
                    ->maxLength(191),
                TextInput::make('emojiU')
                    ->maxLength(191),
                Toggle::make('flag')
                    ->required(),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('iso2')
                    ->searchable(),
                TextColumn::make('numeric_code')
                    ->searchable(),
                TextColumn::make('phonecode')
                    ->searchable(),
                TextColumn::make('capital')
                    ->searchable(),
                TextColumn::make('currency')
                    ->searchable(),
                TextColumn::make('currency_name')
                    ->searchable(),
                TextColumn::make('currency_symbol')
                    ->searchable(),
                TextColumn::make('tld')
                    ->searchable(),
                TextColumn::make('native')
                    ->searchable(),
                TextColumn::make('region')
                    ->searchable(),
                TextColumn::make('subregion')
                    ->searchable(),
                TextColumn::make('latitude')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('longitude')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('flag')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([

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
            'index' => Pages\ListCountries::route('/'),
            'create' => Pages\CreateCountry::route('/create'),
            'edit' => Pages\EditCountry::route('/{record}/edit'),
        ];
    }
}
