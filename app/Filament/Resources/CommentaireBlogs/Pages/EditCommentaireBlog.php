<?php

namespace App\Filament\Resources\CommentaireBlogs\Pages;

use App\Filament\Resources\CommentaireBlogs\CommentaireBlogResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditCommentaireBlog extends EditRecord
{
    protected static string $resource = CommentaireBlogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
