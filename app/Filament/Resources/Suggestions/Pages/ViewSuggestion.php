<?php

namespace App\Filament\Resources\Suggestions\Pages;

use App\Filament\Resources\Suggestions\SuggestionResource;
use Filament\Resources\Pages\ViewRecord;

class ViewSuggestion extends ViewRecord
{
    protected static string $resource = SuggestionResource::class;

    public function mount(int|string $record): void
    {
        parent::mount($record);

        if (blank($this->record->lu_at)) {
            $this->record->update(['lu_at' => now()]);
        }
    }

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
