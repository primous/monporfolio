<?php

namespace App\Filament\Resources\Suggestions;

use App\Filament\Resources\Suggestions\Pages\ListSuggestions;
use App\Filament\Resources\Suggestions\Pages\ViewSuggestion;
use App\Filament\Resources\Suggestions\Schemas\SuggestionForm;
use App\Filament\Resources\Suggestions\Schemas\SuggestionInfolist;
use App\Filament\Resources\Suggestions\Tables\SuggestionsTable;
use App\Models\Suggestion;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SuggestionResource extends Resource
{
    protected static ?string $model = Suggestion::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedLightBulb;

    protected static string|\UnitEnum|null $navigationGroup = 'Contenu du site';

    protected static ?string $navigationLabel = 'Suggestions anonymes';

    protected static ?string $modelLabel = 'suggestion';

    protected static ?string $pluralModelLabel = 'suggestions';

    protected static ?int $navigationSort = 7;

    public static function form(Schema $schema): Schema
    {
        return SuggestionForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SuggestionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SuggestionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    // Lecture seule : les suggestions viennent uniquement du formulaire anonyme du site.
    public static function canCreate(): bool
    {
        return false;
    }

    public static function getNavigationBadge(): ?string
    {
        $n = static::getModel()::whereNull('lu_at')->count();

        return $n > 0 ? (string) $n : null;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSuggestions::route('/'),
            'view' => ViewSuggestion::route('/{record}'),
        ];
    }
}
