<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['titre', 'description', 'points', 'ordre_affichage', 'publie'];

    protected function casts(): array
    {
        return ['points' => 'array', 'publie' => 'boolean'];
    }

    public function scopeVisibles(Builder $query): Builder
    {
        return $query->where('publie', true)->orderBy('ordre_affichage')->orderBy('id');
    }
}
