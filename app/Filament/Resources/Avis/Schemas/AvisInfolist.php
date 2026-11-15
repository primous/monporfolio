<?php

namespace App\Filament\Resources\Avis\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class AvisInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('nom'),
                TextEntry::make('activite')
                    ->placeholder('-'),
                TextEntry::make('contenu')
                    ->columnSpanFull(),
                TextEntry::make('statut')
                    ->badge(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
