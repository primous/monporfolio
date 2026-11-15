<?php

namespace App\Filament\Resources\ArticleBlogs\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ArticleBlogInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('titre'),
                TextEntry::make('slug'),
                TextEntry::make('extrait')
                    ->placeholder('-'),
                TextEntry::make('contenu')
                    ->columnSpanFull(),
                TextEntry::make('statut')
                    ->badge(),
                TextEntry::make('date_publication')
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
