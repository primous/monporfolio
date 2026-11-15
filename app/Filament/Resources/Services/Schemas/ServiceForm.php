<?php

namespace App\Filament\Resources\Services\Schemas;

use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Le service')->columnSpanFull()->schema([
                TextInput::make('titre')->label('Titre')->required()->maxLength(120)
                    ->placeholder('Ex. Site vitrine'),
                Textarea::make('description')->label('Description')->required()->rows(4)
                    ->helperText('Deux ou trois phrases : pour qui, et ce que le client y gagne.'),
                TagsInput::make('points')->label('Ce qui est inclus')
                    ->placeholder('Ajouter un élément puis Entrée')
                    ->helperText('Trois à cinq éléments courts, affichés avec une coche.'),
            ]),
            Section::make('Affichage')->columnSpanFull()->columns(2)->schema([
                TextInput::make('ordre_affichage')->label('Ordre d’affichage')->numeric()->default(0)->required(),
                Toggle::make('publie')->label('Visible sur le site')->default(true)->inline(false),
            ]),
        ]);
    }
}
