<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Projet extends Model
{
    protected $fillable = ['titre', 'slug', 'resume', 'description', 'visuel', 'lien', 'ordre_affichage', 'publie'];

    protected function casts(): array
    {
        return ['publie' => 'boolean'];
    }

    protected static function booted(): void
    {
        static::saving(function (Projet $projet) {
            if (blank($projet->slug)) {
                $projet->slug = Str::slug($projet->titre);
            }
        });
    }

    public function scopeVisibles(Builder $query): Builder
    {
        return $query->where('publie', true)->orderBy('ordre_affichage')->orderBy('id');
    }

    /** URL publique du visuel : fichier livré avec le site (images/…) ou envoyé depuis l'admin (disque « site »). */
    public function visuelUrl(): ?string
    {
        if (blank($this->visuel)) {
            return null;
        }

        return str_starts_with($this->visuel, 'images/')
            ? asset($this->visuel)
            : Storage::disk('site')->url($this->visuel);
    }

    /** Chemin disque du visuel (pour lire ses dimensions réelles). */
    public function visuelChemin(): ?string
    {
        if (blank($this->visuel)) {
            return null;
        }

        return str_starts_with($this->visuel, 'images/')
            ? public_path($this->visuel)
            : Storage::disk('site')->path($this->visuel);
    }

    /** [largeur, hauteur] réelles du visuel, ou null si absent. */
    public function visuelDimensions(): ?array
    {
        $chemin = $this->visuelChemin();

        if (! $chemin || ! is_file($chemin) || ! ($info = @getimagesize($chemin))) {
            return null;
        }

        return [$info[0], $info[1]];
    }
}
