<?php

namespace App\Filament\Resources\CategorieBlogs;

use App\Filament\Resources\CategorieBlogs\Pages\CreateCategorieBlog;
use App\Filament\Resources\CategorieBlogs\Pages\EditCategorieBlog;
use App\Filament\Resources\CategorieBlogs\Pages\ListCategorieBlogs;
use App\Filament\Resources\CategorieBlogs\Schemas\CategorieBlogForm;
use App\Filament\Resources\CategorieBlogs\Tables\CategorieBlogsTable;
use App\Models\CategorieBlog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CategorieBlogResource extends Resource
{
    protected static ?string $model = CategorieBlog::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static string|\UnitEnum|null $navigationGroup = 'Contenu du site';

    protected static ?string $navigationLabel = 'Catégories du blog';

    protected static ?string $modelLabel = 'catégorie';

    protected static ?string $pluralModelLabel = 'catégories';

    protected static ?int $navigationSort = 5;

    protected static ?string $recordTitleAttribute = 'nom';

    public static function form(Schema $schema): Schema
    {
        return CategorieBlogForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CategorieBlogsTable::configure($table);
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
            'index' => ListCategorieBlogs::route('/'),
            'create' => CreateCategorieBlog::route('/create'),
            'edit' => EditCategorieBlog::route('/{record}/edit'),
        ];
    }
}
