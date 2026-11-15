<?php

namespace App\Filament\Resources\Projets\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProjetInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('titre'),
                TextEntry::make('slug'),
                TextEntry::make('resume')
                    ->placeholder('-'),
                TextEntry::make('description')
                    ->columnSpanFull(),
                TextEntry::make('visuel')
                    ->placeholder('-'),
                TextEntry::make('lien')
                    ->placeholder('-'),
                TextEntry::make('ordre_affichage')
                    ->numeric(),
                IconEntry::make('publie')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
