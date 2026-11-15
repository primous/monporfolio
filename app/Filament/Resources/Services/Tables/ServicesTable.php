<?php

namespace App\Filament\Resources\Services\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class ServicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->reorderable('ordre_affichage')
            ->defaultSort('ordre_affichage')
            ->columns([
                TextColumn::make('titre')->label('Titre')->searchable()->weight('bold'),
                TextColumn::make('description')->label('Description')->limit(70)->color('gray'),
                ToggleColumn::make('publie')->label('Visible'),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])])
            ->emptyStateHeading('Aucun service')
            ->emptyStateDescription('Ajoutez vos offres : elles apparaîtront sur la page Services.');
    }
}
