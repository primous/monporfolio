<?php

namespace App\Filament\Resources\Suggestions\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class SuggestionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Textarea::make('message')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('page'),
                DateTimePicker::make('lu_at'),
            ]);
    }
}
