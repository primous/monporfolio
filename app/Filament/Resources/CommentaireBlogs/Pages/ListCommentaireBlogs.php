<?php

namespace App\Filament\Resources\CommentaireBlogs\Pages;

use App\Filament\Resources\CommentaireBlogs\CommentaireBlogResource;
use Filament\Resources\Pages\ListRecords;

class ListCommentaireBlogs extends ListRecords
{
    protected static string $resource = CommentaireBlogResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
