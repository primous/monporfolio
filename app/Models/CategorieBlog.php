<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class CategorieBlog extends Model
{
    protected $table = 'categories_blog';

    protected $fillable = ['nom', 'slug', 'ordre_affichage'];

    protected static function booted(): void
    {
        static::saving(function (CategorieBlog $categorie) {
            if (blank($categorie->slug)) {
                $categorie->slug = Str::slug($categorie->nom);
            }
        });
    }

    public function articles(): HasMany
    {
        return $this->hasMany(ArticleBlog::class, 'categorie_blog_id');
    }
}
