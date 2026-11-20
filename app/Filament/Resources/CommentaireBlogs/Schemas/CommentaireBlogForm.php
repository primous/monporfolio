<?php

namespace App\Filament\Resources\CommentaireBlogs\Schemas;

use App\Models\CommentaireBlog;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CommentaireBlogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Modération')->columnSpanFull()->schema([
                Select::make('statut')->label('Statut')->options(CommentaireBlog::STATUTS)->required()
                    ->helperText('Seuls les commentaires « Publié » apparaissent sous l’article.'),
            ]),
            Section::make('Commentaire envoyé (lecture seule)')->columnSpanFull()->schema([
                TextInput::make('nom')->label('Auteur')->disabled(),
                Textarea::make('message')->label('Message')->rows(5)->disabled(),
            ]),
        ]);
    }
}
