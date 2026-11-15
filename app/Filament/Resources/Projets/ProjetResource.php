<?php

namespace App\Filament\Resources\Projets;

use App\Filament\Resources\Projets\Pages\CreateProjet;
use App\Filament\Resources\Projets\Pages\EditProjet;
use App\Filament\Resources\Projets\Pages\ListProjets;
use App\Filament\Resources\Projets\Pages\ViewProjet;
use App\Filament\Resources\Projets\Schemas\ProjetForm;
use App\Filament\Resources\Projets\Schemas\ProjetInfolist;
use App\Filament\Resources\Projets\Tables\ProjetsTable;
use App\Models\Projet;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProjetResource extends Resource
{
    protected static ?string $model = Projet::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;

    protected static string|\UnitEnum|null $navigationGroup = 'Contenu du site';

    protected static ?string $navigationLabel = 'Projets';

    protected static ?string $modelLabel = 'projet';

    protected static ?string $pluralModelLabel = 'projets';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'titre';

    public static function form(Schema $schema): Schema
    {
        return ProjetForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ProjetInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProjetsTable::configure($table);
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
            'index' => ListProjets::route('/'),
            'create' => CreateProjet::route('/create'),
            'view' => ViewProjet::route('/{record}'),
            'edit' => EditProjet::route('/{record}/edit'),
        ];
    }
}
