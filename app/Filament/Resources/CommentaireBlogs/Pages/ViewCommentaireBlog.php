<?php

namespace App\Filament\Resources\CommentaireBlogs\Pages;

use App\Filament\Resources\CommentaireBlogs\CommentaireBlogResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCommentaireBlog extends ViewRecord
{
    protected static string $resource = CommentaireBlogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
