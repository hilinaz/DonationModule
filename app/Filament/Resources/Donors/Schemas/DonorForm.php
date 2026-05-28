<?php

namespace App\Filament\Resources\Donors\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class DonorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('first_name')
                    ->required(),
                TextInput::make('last_name')
                    ->required(),
                TextInput::make('organization_name'),
                TextInput::make('donor_type')
                    ->required(),
                TextInput::make('category')
                    ->required()
                    ->default('regular'),
                TextInput::make('lifecycle_stage')
                    ->required()
                    ->default('new'),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                TextInput::make('phone')
                    ->tel(),
                TextInput::make('preferred_channel'),
                Textarea::make('address')
                    ->columnSpanFull(),
                TextInput::make('interests'),
                TextInput::make('engagement_score')
                    ->required()
                    ->numeric()
                    ->default(0),
                DateTimePicker::make('last_engaged_at'),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
