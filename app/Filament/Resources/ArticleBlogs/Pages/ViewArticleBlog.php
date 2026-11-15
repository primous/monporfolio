<?php

namespace App\Filament\Resources\ArticleBlogs\Pages;

use App\Filament\Resources\ArticleBlogs\ArticleBlogResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewArticleBlog extends ViewRecord
{
    protected static string $resource = ArticleBlogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
