<?php

namespace App\Filament\Resources\ArticleBlogs\Pages;

use App\Filament\Resources\ArticleBlogs\ArticleBlogResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditArticleBlog extends EditRecord
{
    protected static string $resource = ArticleBlogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
