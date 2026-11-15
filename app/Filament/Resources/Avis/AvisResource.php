<?php

namespace App\Filament\Resources\Avis;

use App\Filament\Resources\Avis\Pages\CreateAvis;
use App\Filament\Resources\Avis\Pages\EditAvis;
use App\Filament\Resources\Avis\Pages\ListAvis;
use App\Filament\Resources\Avis\Pages\ViewAvis;
use App\Filament\Resources\Avis\Schemas\AvisForm;
use App\Filament\Resources\Avis\Schemas\AvisInfolist;
use App\Filament\Resources\Avis\Tables\AvisTable;
use App\Models\Avis;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AvisResource extends Resource
{
    protected static ?string $model = Avis::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleBottomCenterText;

    protected static string|\UnitEnum|null $navigationGroup = 'Contenu du site';

    protected static ?string $navigationLabel = 'Avis clients';

    protected static ?string $modelLabel = 'avis client';

    protected static ?string $pluralModelLabel = 'avis clients';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'nom';

    public static function form(Schema $schema): Schema
    {
        return AvisForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AvisInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AvisTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $n = static::getModel()::where('statut', 'En attente')->count();

        return $n > 0 ? (string) $n : null;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAvis::route('/'),
            'create' => CreateAvis::route('/create'),
            'view' => ViewAvis::route('/{record}'),
            'edit' => EditAvis::route('/{record}/edit'),
        ];
    }
}
