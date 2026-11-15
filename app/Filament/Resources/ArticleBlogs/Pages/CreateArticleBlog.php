<?php

namespace App\Filament\Resources\ArticleBlogs\Pages;

use App\Filament\Resources\ArticleBlogs\ArticleBlogResource;
use Filament\Resources\Pages\CreateRecord;

class CreateArticleBlog extends CreateRecord
{
    protected static string $resource = ArticleBlogResource::class;
}
