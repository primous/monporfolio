<?php

namespace App\Filament\Resources\Projets\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class ProjetsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->reorderable('ordre_affichage')
            ->defaultSort('ordre_affichage')
            ->columns([
                ImageColumn::make('visuel')->label('')->disk('site')->imageHeight(44)->imageWidth(96),
                TextColumn::make('titre')->label('Projet')->searchable()->weight('bold'),
                TextColumn::make('resume')->label('Résumé')->limit(60)->color('gray'),
                ToggleColumn::make('publie')->label('Visible'),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])])
            ->emptyStateHeading('Aucun projet');
    }
}
