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

        $suggestion = $this->getRecord();

        if ($suggestion instanceof \App\Models\Suggestion && blank($suggestion->lu_at)) {
            $suggestion->update(['lu_at' => now()]);
        }
    }

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
