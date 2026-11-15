<?php

namespace App\Filament\Resources\ArticleBlogs\Pages;

use App\Filament\Resources\ArticleBlogs\ArticleBlogResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListArticleBlogs extends ListRecords
{
    protected static string $resource = ArticleBlogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
