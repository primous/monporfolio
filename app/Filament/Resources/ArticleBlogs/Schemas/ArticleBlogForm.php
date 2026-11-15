<?php

namespace App\Filament\Resources\ArticleBlogs\Schemas;

use App\Models\ArticleBlog;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ArticleBlogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('L’article')->columnSpanFull()->columns(2)->schema([
                TextInput::make('titre')->label('Titre')->required()->maxLength(160)->columnSpanFull()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, ?string $state, string $operation) => $operation === 'create' ? $set('slug', Str::slug((string) $state)) : null),
                TextInput::make('slug')->label('Adresse (slug)')->required()->maxLength(180)
                    ->unique(ignoreRecord: true)->columnSpanFull(),
                Textarea::make('extrait')->label('Extrait')->rows(2)->maxLength(300)->columnSpanFull()
                    ->helperText('Une ou deux phrases affichées dans la liste des articles. Sinon, le début du texte est utilisé.'),
                RichEditor::make('contenu')->label('Contenu')->required()->columnSpanFull()
                    ->toolbarButtons([['bold', 'italic', 'link'], ['h2', 'h3'], ['bulletList', 'orderedList', 'blockquote'], ['undo', 'redo']]),
            ]),
            Section::make('Publication')->columnSpanFull()->columns(2)->schema([
                Select::make('statut')->label('Statut')->options(ArticleBlog::STATUTS)->default('Brouillon')->required(),
                DateTimePicker::make('date_publication')->label('Date de publication')
                    ->helperText('Laissez vide : la date du jour est utilisée à la publication.'),
            ]),
        ]);
    }
}
