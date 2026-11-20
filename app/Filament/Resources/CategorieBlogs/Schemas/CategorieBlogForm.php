<?php

namespace App\Filament\Resources\CategorieBlogs\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CategorieBlogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('La catégorie')->columnSpanFull()->columns(2)->schema([
                TextInput::make('nom')->label('Nom')->required()->maxLength(60)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, ?string $state, string $operation) => $operation === 'create' ? $set('slug', Str::slug((string) $state)) : null),
                TextInput::make('slug')->label('Adresse (slug)')->required()->maxLength(80)->unique(ignoreRecord: true),
                TextInput::make('ordre_affichage')->label('Ordre d’affichage')->numeric()->default(0)->required(),
            ]),
        ]);
    }
}
