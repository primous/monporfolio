<?php

namespace App\Filament\Resources\CommentaireBlogs;

use App\Filament\Resources\CommentaireBlogs\Pages\EditCommentaireBlog;
use App\Filament\Resources\CommentaireBlogs\Pages\ListCommentaireBlogs;
use App\Filament\Resources\CommentaireBlogs\Pages\ViewCommentaireBlog;
use App\Filament\Resources\CommentaireBlogs\Schemas\CommentaireBlogForm;
use App\Filament\Resources\CommentaireBlogs\Schemas\CommentaireBlogInfolist;
use App\Filament\Resources\CommentaireBlogs\Tables\CommentaireBlogsTable;
use App\Models\CommentaireBlog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CommentaireBlogResource extends Resource
{
    protected static ?string $model = CommentaireBlog::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static string|\UnitEnum|null $navigationGroup = 'Contenu du site';

    protected static ?string $navigationLabel = 'Commentaires du blog';

    protected static ?string $modelLabel = 'commentaire';

    protected static ?string $pluralModelLabel = 'commentaires';

    protected static ?int $navigationSort = 6;

    protected static ?string $recordTitleAttribute = 'nom';

    public static function form(Schema $schema): Schema
    {
        return CommentaireBlogForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CommentaireBlogInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CommentaireBlogsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    // Les commentaires viennent uniquement du site : on les modère, on ne les crée pas ici.
    public static function canCreate(): bool
    {
        return false;
    }

    public static function getNavigationBadge(): ?string
    {
        $n = static::getModel()::where('statut', 'En attente')->count();

        return $n > 0 ? (string) $n : null;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCommentaireBlogs::route('/'),
            'view' => ViewCommentaireBlog::route('/{record}'),
            'edit' => EditCommentaireBlog::route('/{record}/edit'),
        ];
    }
}
