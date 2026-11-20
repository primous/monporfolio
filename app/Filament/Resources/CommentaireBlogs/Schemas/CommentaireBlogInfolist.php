<?php

namespace App\Filament\Resources\CommentaireBlogs\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CommentaireBlogInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('article_blog_id')
                    ->numeric(),
                TextEntry::make('nom'),
                TextEntry::make('message')
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
