<?php

namespace App\Filament\Resources\Pledges;

use App\Filament\Resources\Pledges\Pages\CreatePledge;
use App\Filament\Resources\Pledges\Pages\EditPledge;
use App\Filament\Resources\Pledges\Pages\ListPledges;
use App\Filament\Resources\Pledges\Schemas\PledgeForm;
use App\Filament\Resources\Pledges\Tables\PledgesTable;
use App\Models\Pledge;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PledgeResource extends Resource
{
    protected static ?string $model = Pledge::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;
    protected static string|\UnitEnum|null $navigationGroup = 'Fundraising';
    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return PledgeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PledgesTable::configure($table);
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
            'index' => ListPledges::route('/'),
            'create' => CreatePledge::route('/create'),
            'edit' => EditPledge::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}

