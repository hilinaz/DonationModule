<?php

namespace App\Filament\Resources\Campaigns\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CampaignForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('code')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('type')
                    ->required()
                    ->default('general'),
                TextInput::make('goal_amount')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('goal_currency')
                    ->required()
                    ->default('USD'),
                TextInput::make('status')
                    ->required()
                    ->default('draft'),
                DateTimePicker::make('starts_at'),
                DateTimePicker::make('ends_at'),
                Toggle::make('is_public')
                    ->required(),
            ]);
    }
}
