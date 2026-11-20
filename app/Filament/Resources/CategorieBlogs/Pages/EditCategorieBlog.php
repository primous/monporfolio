<?php

namespace App\Filament\Resources\CategorieBlogs\Pages;

use App\Filament\Resources\CategorieBlogs\CategorieBlogResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCategorieBlog extends EditRecord
{
    protected static string $resource = CategorieBlogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
