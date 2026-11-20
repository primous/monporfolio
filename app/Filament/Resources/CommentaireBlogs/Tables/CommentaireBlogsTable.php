<?php

namespace App\Filament\Resources\CommentaireBlogs\Tables;

use App\Models\CommentaireBlog;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CommentaireBlogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('nom')->label('Auteur')->searchable()->weight('bold'),
                TextColumn::make('message')->label('Commentaire')->limit(80)->wrap()->searchable(),
                TextColumn::make('article.titre')->label('Article')->limit(40)->color('gray'),
                TextColumn::make('statut')->label('Statut')->badge()
                    ->color(fn (string $state) => $state === 'Publié' ? 'primary' : 'gray'),
                TextColumn::make('created_at')->label('Reçu le')->dateTime('d/m/Y à H:i')->sortable(),
            ])
            ->filters([SelectFilter::make('statut')->label('Statut')->options(CommentaireBlog::STATUTS)])
            ->recordActions([
                Action::make('publier')->label('Publier')->icon('heroicon-o-check')->color('primary')
                    ->visible(fn (CommentaireBlog $record) => $record->statut !== 'Publié')
                    ->action(fn (CommentaireBlog $record) => $record->update(['statut' => 'Publié'])),
                Action::make('retirer')->label('Retirer')->icon('heroicon-o-eye-slash')->color('gray')
                    ->visible(fn (CommentaireBlog $record) => $record->statut === 'Publié')
                    ->action(fn (CommentaireBlog $record) => $record->update(['statut' => 'Retiré'])),
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])])
            ->emptyStateHeading('Aucun commentaire')
            ->emptyStateDescription('Les commentaires laissés sous les articles arriveront ici, en attente de relecture.');
    }
}
