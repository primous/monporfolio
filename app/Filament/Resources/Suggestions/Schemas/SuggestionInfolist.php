<?php

namespace App\Filament\Resources\Suggestions\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SuggestionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('message')
                    ->columnSpanFull(),
                TextEntry::make('page')
                    ->placeholder('-'),
                TextEntry::make('lu_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
