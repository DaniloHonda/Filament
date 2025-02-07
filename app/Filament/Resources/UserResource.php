<?php

namespace App\Filament\Resources;

use Altwaireb\World\Models\City;
use Altwaireb\World\Models\State;
use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationLabel = 'Employees';

    protected static ?string $navigationGroup = 'Creation';

    protected static ?int $navigationSort = 9;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Personal Info')
                    ->schema([
                        TextInput::make('name')
                            ->helperText(str('Your **full name** here')->markdown()->toHtmlString())
                            ->required()
                            ->maxLength(50),
                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(table: 'users', ignorable: fn ($record) => $record)
                            ->maxLength(50),
                        TextInput::make('password')
                            ->password()
                            ->required()
                            ->hiddenOn('edit')
                            ->maxLength(50),
                    ])->columns(3),

                Section::make('Address Info')
                    ->schema([
                        Select::make('country_id')
                            ->relationship(name: 'country', titleAttribute: 'name')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->required()
                            ->afterStateUpdated(function (Set $set) {
                                $set('state_id', null);
                                $set('city_id', null);
                            }),

                        Select::make('state_id')
                            ->label('State')
                            ->searchable()
                            ->preload()
                            ->afterStateUpdated(fn (Set $set) => $set('city_id', null))
                            ->live()
                            ->required(fn (Get $get): bool => State::where('country_id', $get('country_id'))->exists())
                            ->disabled(fn (Get $get): bool => State::where('country_id', $get('country_id'))->doesntExist())// Desativa se não houver estados
                            ->options(fn (Get $get): Collection => State::query()
                                ->where('country_id', $get('country_id'))
                                ->pluck('name', 'id')),
                        Select::make('city_id')
                            ->label('City')
                            ->searchable()
                            ->preload()
                            ->required(fn (Get $get): bool => State::where('country_id', $get('country_id'))->exists())
                            ->disabled(fn (Get $get): bool => State::where('country_id', $get('country_id'))->doesntExist())// Desativa cidades se não houver estados
                            ->options(fn (Get $get): Collection => City::query()
                                ->where('state_id', $get('state_id'))
                                ->pluck('name', 'id')),
                        TextInput::make('address')
                            ->label('Address')
                            ->required(),
                        TextInput::make('postal_code')
                            ->label('Postal Code')
                            ->required(),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('country.name')
                    ->label('Country')
                    ->searchable(),
                TextColumn::make('state.name')
                    ->label('State')
                    ->searchable(),
                TextColumn::make('city.name')
                    ->label('City')
                    ->searchable(),
                TextColumn::make('address')
                    ->label('Address')
                    ->searchable(),
                TextColumn::make('postal_code')
                    ->label('Postal Code')
                    ->searchable(),
            ])
            ->filters([

            ])
            ->actions([
                ActionGroup::make([
                    EditAction::make()
                        ->icon('heroicon-o-pencil-square'),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
