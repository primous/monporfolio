<?php

namespace App\Filament\Resources\CategorieBlogs\Pages;

use App\Filament\Resources\CategorieBlogs\CategorieBlogResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCategorieBlogs extends ListRecords
{
    protected static string $resource = CategorieBlogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
