<?php

namespace App\Filament\Resources\ArticleBlogs;

use App\Filament\Resources\ArticleBlogs\Pages\CreateArticleBlog;
use App\Filament\Resources\ArticleBlogs\Pages\EditArticleBlog;
use App\Filament\Resources\ArticleBlogs\Pages\ListArticleBlogs;
use App\Filament\Resources\ArticleBlogs\Pages\ViewArticleBlog;
use App\Filament\Resources\ArticleBlogs\Schemas\ArticleBlogForm;
use App\Filament\Resources\ArticleBlogs\Schemas\ArticleBlogInfolist;
use App\Filament\Resources\ArticleBlogs\Tables\ArticleBlogsTable;
use App\Models\ArticleBlog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ArticleBlogResource extends Resource
{
    protected static ?string $model = ArticleBlog::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedNewspaper;

    protected static string|\UnitEnum|null $navigationGroup = 'Contenu du site';

    protected static ?string $navigationLabel = 'Articles du blog';

    protected static ?string $modelLabel = 'article';

    protected static ?string $pluralModelLabel = 'articles';

    protected static ?int $navigationSort = 4;

    protected static ?string $recordTitleAttribute = 'titre';

    public static function form(Schema $schema): Schema
    {
        return ArticleBlogForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ArticleBlogInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ArticleBlogsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListArticleBlogs::route('/'),
            'create' => CreateArticleBlog::route('/create'),
            'view' => ViewArticleBlog::route('/{record}'),
            'edit' => EditArticleBlog::route('/{record}/edit'),
        ];
    }
}
