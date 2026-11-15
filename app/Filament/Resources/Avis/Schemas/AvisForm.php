<?php

namespace App\Filament\Resources\Avis\Schemas;

use App\Models\Avis;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AvisForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('L’avis')->columnSpanFull()->columns(2)->schema([
                TextInput::make('nom')->label('Nom du client')->required()->maxLength(120),
                TextInput::make('activite')->label('Activité, ville')->maxLength(160)
                    ->placeholder('Ex. Boutique de cosmétiques, Cotonou'),
                Textarea::make('contenu')->label('Ce que le client dit')->required()->rows(5)->columnSpanFull()
                    ->helperText('Uniquement des avis réels, avec l’accord du client.'),
                Select::make('statut')->label('Statut')->options(Avis::STATUTS)->default('En attente')->required()
                    ->helperText('Seuls les avis « Publié » apparaissent sur le site.'),
            ]),
        ]);
    }
}
