<?php

namespace App\Filament\Resources\Demandes\Schemas;

use App\Models\Demande;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DemandeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informations du contact')
                    ->schema([
                        Grid::make(3)->schema([
                            TextEntry::make('full_name')->label('Nom complet'),
                            TextEntry::make('email')->label('Email'),
                            TextEntry::make('phone')->label('Téléphone / WhatsApp'),
                        ]),
                        Grid::make(2)->schema([
                            TextEntry::make('statut')
                                ->label('Statut actuel')
                                ->badge()
                                ->color(fn (string $state): string => match ($state) {
                                    'nouveau' => 'primary',
                                    default => 'gray',
                                }),
                            TextEntry::make('created_at')
                                ->label('Date de réception')
                                ->dateTime('d/m/Y à H:i'),
                        ]),
                    ]),

                Section::make('Activité et Présence en ligne')
                    ->schema([
                        TextEntry::make('activity')->label('Activité'),
                        TextEntry::make('has_website')->label('Site internet actuel'),
                        TextEntry::make('difficulties')
                            ->label('Difficultés rencontrées')
                            ->badge(),
                        TextEntry::make('main_difficulty')
                            ->label('Difficulté principale')
                            ->placeholder('Non précisée'),
                    ]),

                Section::make('Projet & Budget')
                    ->schema([
                        TextEntry::make('objective')->label('Objectif attendu'),
                        TextEntry::make('need')->label('Description du besoin'),
                        TextEntry::make('budget_display')
                            ->label('Budget prévu')
                            ->state(function (Demande $record): string {
                                if ($record->budget_unknown) {
                                    return 'Je ne sais pas encore, conseillez-moi';
                                }
                                return $record->budget ?? 'Non renseigné';
                            }),
                        TextEntry::make('consent_at')
                            ->label('Consentement RGPD')
                            ->dateTime('d/m/Y à H:i'),
                    ]),
            ]);
    }
}
