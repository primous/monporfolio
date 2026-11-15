<?php

namespace App\Filament\Resources\Demandes\Pages;

use App\Filament\Resources\Demandes\DemandeResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDemande extends ViewRecord
{
    protected static string $resource = DemandeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
