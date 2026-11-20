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
            ->modifyQueryUsing(fn ($query) => $query->withCount(['commentaires', 'commentaires as commentaires_en_attente_count' => fn ($q) => $q->where('statut', 'En attente')]))
            ->defaultSort('date_publication', 'desc')
            ->columns([
                TextColumn::make('titre')->label('Titre')->searchable()->weight('bold')->limit(50),
                TextColumn::make('categorie.nom')->label('Catégorie')->badge()->color('gray')->placeholder('—'),
                TextColumn::make('statut')->label('Statut')->badge()
                    ->color(fn (string $state) => $state === 'Publié' ? 'primary' : 'gray'),
                TextColumn::make('vues')->label('Lectures')->numeric()->sortable(),
                TextColumn::make('commentaires_count')->label('Commentaires')->numeric()->sortable()
                    ->description(fn ($record) => $record->commentaires_en_attente_count > 0 ? $record->commentaires_en_attente_count.' en attente' : null),
                TextColumn::make('date_publication')->label('Publication')->dateTime('d/m/Y')->sortable()->placeholder('—'),
            ])
            ->filters([
                SelectFilter::make('statut')->label('Statut')->options(ArticleBlog::STATUTS),
                SelectFilter::make('categorie_blog_id')->label('Catégorie')->relationship('categorie', 'nom'),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])])
            ->emptyStateHeading('Aucun article');
    }
}
