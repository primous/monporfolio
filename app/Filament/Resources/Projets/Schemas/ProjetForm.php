<?php

namespace App\Filament\Resources\Projets\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProjetForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Le projet')->columnSpanFull()->columns(2)->schema([
                TextInput::make('titre')->label('Nom du projet')->required()->maxLength(120)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, ?string $state, string $operation) => $operation === 'create' ? $set('slug', Str::slug((string) $state)) : null),
                TextInput::make('slug')->label('Adresse (slug)')->required()->maxLength(140)
                    ->unique(ignoreRecord: true)->helperText('Rempli automatiquement à partir du nom.'),
                TextInput::make('resume')->label('Résumé en une ligne')->maxLength(160)->columnSpanFull()
                    ->helperText('Affiché sur les cartes. Ex. Plateforme d’événements business en Afrique de l’Ouest'),
                Textarea::make('description')->label('Description')->required()->rows(5)->columnSpanFull()
                    ->helperText('Le contexte, ce que vous avez construit, le résultat pour le client.'),
                TextInput::make('lien')->label('Lien vers le site en ligne')->url()->maxLength(255)->columnSpanFull()
                    ->placeholder('https://…'),
            ]),
            Section::make('Visuel')->columnSpanFull()->schema([
                FileUpload::make('visuel')->label('Capture du site')
                    ->disk('site')->directory('projets')->visibility('public')
                    ->image()->maxSize(4096)->imagePreviewHeight('220')
                    ->helperText('Capture d’écran de la page d’accueil du projet (JPG, PNG ou WebP, 4 Mo max).'),
            ]),
            Section::make('Affichage')->columnSpanFull()->columns(2)->schema([
                TextInput::make('ordre_affichage')->label('Ordre d’affichage')->numeric()->default(0)->required(),
                Toggle::make('publie')->label('Visible sur le site')->default(true)->inline(false),
            ]),
        ]);
    }
}
