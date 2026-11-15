<?php

namespace App\Filament\Resources\Demandes;

use App\Filament\Resources\Demandes\Pages\EditDemande;
use App\Filament\Resources\Demandes\Pages\ListDemandes;
use App\Filament\Resources\Demandes\Pages\ViewDemande;
use App\Filament\Resources\Demandes\Schemas\DemandeForm;
use App\Filament\Resources\Demandes\Schemas\DemandeInfolist;
use App\Filament\Resources\Demandes\Tables\DemandesTable;
use App\Models\Demande;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DemandeResource extends Resource
{
    protected static ?string $model = Demande::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInboxArrowDown;

    protected static ?string $navigationLabel = 'Demandes de projet';

    protected static ?string $modelLabel = 'Demande de projet';

    protected static ?string $pluralModelLabel = 'Demandes de projet';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::where('statut', 'nouveau')->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function form(Schema $schema): Schema
    {
        return DemandeForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DemandeInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DemandesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDemandes::route('/'),
            'view' => ViewDemande::route('/{record}'),
            'edit' => EditDemande::route('/{record}/edit'),
        ];
    }
}
