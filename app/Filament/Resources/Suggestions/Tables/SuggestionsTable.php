<?php

namespace App\Filament\Resources\Suggestions\Tables;

use App\Models\Suggestion;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class SuggestionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                IconColumn::make('lu_at')->label('Lue')->boolean()
                    ->state(fn (Suggestion $record) => filled($record->lu_at)),
                TextColumn::make('message')->label('Suggestion')->limit(90)->wrap(),
                TextColumn::make('page')->label('Envoyée depuis')->color('gray')->placeholder('—'),
                TextColumn::make('created_at')->label('Reçue le')->dateTime('d/m/Y à H:i')->sortable(),
            ])
            ->filters([
                TernaryFilter::make('lu_at')->label('Lecture')->nullable()
                    ->trueLabel('Lues')->falseLabel('Non lues'),
            ])
            ->recordActions([
                ViewAction::make(),
                Action::make('marquerLue')->label('Marquer comme lue')->icon('heroicon-o-check')
                    ->visible(fn (Suggestion $record) => blank($record->lu_at))
                    ->action(fn (Suggestion $record) => $record->update(['lu_at' => now()])),
            ])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])])
            ->emptyStateHeading('Aucune suggestion')
            ->emptyStateDescription('Les suggestions envoyées depuis le pied de page arriveront ici.');
    }
}
