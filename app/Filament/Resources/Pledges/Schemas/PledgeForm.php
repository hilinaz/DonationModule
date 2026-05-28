<?php

namespace App\Filament\Resources\Pledges\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PledgeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('donor_id')
                    ->relationship('donor', 'id')
                    ->required(),
                Select::make('campaign_id')
                    ->relationship('campaign', 'name'),
                TextInput::make('pledged_amount')
                    ->required()
                    ->numeric(),
                TextInput::make('fulfilled_amount')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('currency')
                    ->required()
                    ->default('USD'),
                DatePicker::make('due_date'),
                TextInput::make('status')
                    ->required()
                    ->default('open'),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}
