<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Avis extends Model
{
    protected $table = 'avis';

    protected $fillable = ['nom', 'activite', 'contenu', 'statut'];

    public const STATUTS = ['En attente' => 'En attente', 'Publié' => 'Publié', 'Retiré' => 'Retiré'];

    public function scopePublies(Builder $query): Builder
    {
        return $query->where('statut', 'Publié')->latest();
    }
}
