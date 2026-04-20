<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Personal Details')
                    ->columns(2)
                    ->schema([
                        TextInput::make('first_name')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('last_name')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ]),

                Section::make('Access & Role')
                    ->schema([
                        Select::make('roles')
                            ->relationship('roles', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->required()
                            // client_manager can only assign the client role
                            ->options(function () {
                                if (auth()->user()->hasRole('client_manager')) {
                                    return Role::where('name', 'client')->pluck('name', 'id');
                                }
                                return Role::pluck('name', 'id');
                            })
                            // lock it to client role if client_manager
                            ->disabled(fn () => auth()->user()->hasRole('client_manager'))
                            ->default(function () {
                                if (auth()->user()->hasRole('client_manager')) {
                                    return [Role::where('name', 'client')->first()?->id];
                                }
                                return null;
                            }),

                        TextInput::make('password')
                            ->password()
                            ->label('Temporary Password')
                            ->helperText('A temporary password the user will use to log in and then reset.')
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->maxLength(255),
                    ]),
            ]);
    }
}