<?php

namespace App\Filament\Resources\Donations\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class DonationForm
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
                TextInput::make('donation_type')
                    ->required()
                    ->default('one_time'),
                TextInput::make('source')
                    ->required()
                    ->default('offline'),
                TextInput::make('payment_status')
                    ->required()
                    ->default('pending'),
                TextInput::make('currency')
                    ->required()
                    ->default('USD'),
                TextInput::make('amount_original')
                    ->required()
                    ->numeric(),
                TextInput::make('exchange_rate')
                    ->required()
                    ->numeric()
                    ->default(1),
                TextInput::make('amount_base')
                    ->required()
                    ->numeric(),
                TextInput::make('base_currency')
                    ->required()
                    ->default('USD'),
                Toggle::make('gift_aid_eligible')
                    ->required(),
                TextInput::make('payment_gateway'),
                TextInput::make('transaction_reference'),
                DateTimePicker::make('donated_at'),
                TextInput::make('meta'),
            ]);
    }
}
