<?php

namespace App\Filament\Resources\CategorieBlogs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CategorieBlogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->withCount('articles'))
            ->reorderable('ordre_affichage')
            ->defaultSort('ordre_affichage')
            ->columns([
                TextColumn::make('nom')->label('Catégorie')->searchable()->weight('bold'),
                TextColumn::make('slug')->label('Adresse')->color('gray'),
                TextColumn::make('articles_count')->label('Articles')->numeric()->sortable(),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])])
            ->emptyStateHeading('Aucune catégorie');
    }
}
