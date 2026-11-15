<?php

namespace App\Filament\Resources\Avis\Tables;

use App\Models\Avis;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AvisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('nom')->label('Client')->searchable()->weight('bold')
                    ->description(fn (Avis $record) => $record->activite),
                TextColumn::make('contenu')->label('Avis')->limit(70)->color('gray'),
                TextColumn::make('statut')->label('Statut')->badge()
                    ->color(fn (string $state) => $state === 'Publié' ? 'primary' : 'gray'),
                TextColumn::make('created_at')->label('Reçu le')->dateTime('d/m/Y')->sortable(),
            ])
            ->filters([SelectFilter::make('statut')->label('Statut')->options(Avis::STATUTS)])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])])
            ->emptyStateHeading('Aucun avis')
            ->emptyStateDescription('Ajoutez les avis de vos clients et passez-les en « Publié » pour les afficher.');
    }
}
