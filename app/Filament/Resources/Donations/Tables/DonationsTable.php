<?php

namespace App\Filament\Resources\Donations\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class DonationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('donor.id')
                    ->searchable(),
                TextColumn::make('campaign.name')
                    ->searchable(),
                TextColumn::make('donation_type')
                    ->searchable(),
                TextColumn::make('source')
                    ->searchable(),
                TextColumn::make('payment_status')
                    ->searchable(),
                TextColumn::make('currency')
                    ->searchable(),
                TextColumn::make('amount_original')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('exchange_rate')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('amount_base')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('base_currency')
                    ->searchable(),
                IconColumn::make('gift_aid_eligible')
                    ->boolean(),
                TextColumn::make('payment_gateway')
                    ->searchable(),
                TextColumn::make('transaction_reference')
                    ->searchable(),
                TextColumn::make('donated_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
