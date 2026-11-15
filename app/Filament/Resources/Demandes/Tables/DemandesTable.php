<?php

namespace App\Filament\Resources\Demandes\Tables;

use App\Models\Demande;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class DemandesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('full_name')
                    ->label('Nom')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('Téléphone'),
                TextColumn::make('budget_display')
                    ->label('Budget')
                    ->state(function (Demande $record): string {
                        if ($record->budget_unknown) {
                            return 'À conseiller';
                        }
                        return $record->budget ?? '-';
                    }),
                TextColumn::make('statut')
                    ->label('Statut')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'nouveau' => 'primary',
                        default => 'gray',
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('statut')
                    ->label('Statut')
                    ->options([
                        'nouveau' => 'Nouveau',
                        'contacté' => 'Contacté',
                        'archivé' => 'Archivé',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([]);
    }
}
