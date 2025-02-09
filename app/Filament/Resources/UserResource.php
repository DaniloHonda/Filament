<?php

namespace App\Filament\Resources;

use App\Models\User;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Altwaireb\World\Models\City;
use Filament\Resources\Resource;
use Altwaireb\World\Models\State;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Section;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms\Components\Wizard\Step;
use App\Filament\Resources\UserResource\Pages;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationLabel = 'Employees';

    protected static ?string $navigationGroup = 'Employees';

    protected static ?int $navigationSort = 9;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    public static function form(Form $form): Form
    {
        $operation = $form->getOperation();

        if ($operation === 'create') {
            return $form->schema([
                Wizard::make([
                    Step::make('Personal Info')
                        ->icon('heroicon-m-shopping-bag')
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
                                ]),
                        ]),
                    Step::make('Address Info')
                        ->schema([
                            Section::make('Address Info')
                                ->schema([
                                    Select::make('country_id')
                                        ->columnSpanFull()
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
                                        ->disabled(fn (Get $get): bool => State::where('country_id', $get('country_id'))->doesntExist())
                                        ->options(fn (Get $get): Collection => State::query()
                                            ->where('country_id', $get('country_id'))
                                            ->pluck('name', 'id')),
                                    Select::make('city_id')
                                        ->label('City')
                                        ->searchable()
                                        ->preload()
                                        ->required(fn (Get $get): bool => State::where('country_id', $get('country_id'))->exists())
                                        ->disabled(fn (Get $get): bool => State::where('country_id', $get('country_id'))->doesntExist())
                                        ->options(fn (Get $get): Collection => City::query()
                                            ->where('state_id', $get('state_id'))
                                            ->pluck('name', 'id')),
                                    TextInput::make('address')
                                        ->label('Address'),
                                    TextInput::make('postal_code')
                                        ->label('Postal Code'),
                                ]),
                        ]),
                ])
            ]);
        }

        return $form->schema([
            Tabs::make('Tabs')
                ->tabs([
                    Tabs\Tab::make('Personal Information')
                        ->schema([
                            Section::make('Personal Info')
                                ->icon('heroicon-m-shopping-bag')
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
                                ]),
                        ]),
                    Tabs\Tab::make('Address Information')
                        ->schema([
                            Section::make('Address Info')
                                ->schema([
                                    Select::make('country_id')
                                        ->columnSpanFull()
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
                                        ->disabled(fn (Get $get): bool => State::where('country_id', $get('country_id'))->doesntExist())
                                        ->options(fn (Get $get): Collection => State::query()
                                            ->where('country_id', $get('country_id'))
                                            ->pluck('name', 'id')),
                                    Select::make('city_id')
                                        ->label('City')
                                        ->searchable()
                                        ->preload()
                                        ->required(fn (Get $get): bool => State::where('country_id', $get('country_id'))->exists())
                                        ->disabled(fn (Get $get): bool => State::where('country_id', $get('country_id'))->doesntExist())
                                        ->options(fn (Get $get): Collection => City::query()
                                            ->where('state_id', $get('state_id'))
                                            ->pluck('name', 'id')),
                                    TextInput::make('address')
                                        ->label('Address'),
                                    TextInput::make('postal_code')
                                        ->label('Postal Code'),
                                ]),
                        ]),
                ])
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
