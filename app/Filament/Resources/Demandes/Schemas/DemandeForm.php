<?php

namespace App\Filament\Resources\Demandes\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DemandeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Statut de la demande')
                    ->schema([
                        Select::make('statut')
                            ->label('Statut du dossier')
                            ->options([
                                'nouveau' => 'Nouveau',
                                'contacté' => 'Contacté',
                                'archivé' => 'Archivé',
                            ])
                            ->required(),
                    ]),
                Section::make('Informations soumises (lecture seule)')
                    ->schema([
                        TextInput::make('full_name')->label('Nom complet')->disabled(),
                        TextInput::make('email')->label('Email')->disabled(),
                        TextInput::make('phone')->label('Téléphone / WhatsApp')->disabled(),
                        Textarea::make('activity')->label('Activité')->disabled()->rows(3),
                        TextInput::make('has_website')->label('Site internet actuel')->disabled(),
                        Textarea::make('objective')->label('Objectif')->disabled()->rows(2),
                        Textarea::make('need')->label('Besoin')->disabled()->rows(3),
                    ])
                    ->collapsible(),
            ]);
    }
}
