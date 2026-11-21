<?php

namespace App\Filament\Resources\Demandes\Pages;

use App\Actions\EffacerDonneesPersonne;
use App\Filament\Resources\Demandes\DemandeResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListDemandes extends ListRecords
{
    protected static string $resource = DemandeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Droit à l'effacement : toutes les données liées à un e-mail, après confirmation.
            Action::make('effacerDonnees')
                ->label('Effacer les données d’une personne')
                ->icon('heroicon-o-user-minus')
                ->color('danger')
                ->modalHeading('Effacer les données d’une personne')
                ->modalDescription('Supprime définitivement les demandes de projet et les messages de contact envoyés avec cette adresse. Les avis et commentaires (sans e-mail) se suppriment un par un.')
                ->modalSubmitActionLabel('Supprimer définitivement')
                ->schema([
                    TextInput::make('email')->label('Adresse e-mail de la personne')->email()->required(),
                    TextInput::make('confirmation')->label('Tapez EFFACER pour confirmer')->required()->in(['EFFACER'])
                        ->validationMessages(['in' => 'Tapez EFFACER en majuscules pour confirmer.']),
                ])
                ->action(function (array $data, EffacerDonneesPersonne $effacer): void {
                    $fait = $effacer($data['email']);

                    Notification::make()
                        ->title(array_sum($fait) > 0 ? 'Données effacées' : 'Aucune donnée pour cette adresse')
                        ->body("{$fait['demandes']} demande(s) et {$fait['messages']} message(s) supprimé(s).")
                        ->success()
                        ->send();
                }),
            CreateAction::make(),
        ];
    }
}
