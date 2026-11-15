<?php

namespace App\Filament\Resources\Avis\Pages;

use App\Filament\Resources\Avis\AvisResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewAvis extends ViewRecord
{
    protected static string $resource = AvisResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
