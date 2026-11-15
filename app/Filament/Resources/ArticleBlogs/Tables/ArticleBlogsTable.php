<?php

namespace App\Filament\Resources\ArticleBlogs\Tables;

use App\Models\ArticleBlog;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ArticleBlogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('date_publication', 'desc')
            ->columns([
                TextColumn::make('titre')->label('Titre')->searchable()->weight('bold')->limit(60),
                TextColumn::make('statut')->label('Statut')->badge()
                    ->color(fn (string $state) => $state === 'Publié' ? 'primary' : 'gray'),
                TextColumn::make('date_publication')->label('Publication')->dateTime('d/m/Y')->sortable()->placeholder('—'),
            ])
            ->filters([SelectFilter::make('statut')->label('Statut')->options(ArticleBlog::STATUTS)])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])])
            ->emptyStateHeading('Aucun article');
    }
}
