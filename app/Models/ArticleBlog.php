<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ArticleBlog extends Model
{
    protected $table = 'articles_blog';

    protected $fillable = ['categorie_blog_id', 'titre', 'slug', 'extrait', 'contenu', 'statut', 'date_publication'];

    public const STATUTS = ['Brouillon' => 'Brouillon', 'Publié' => 'Publié', 'Retiré' => 'Retiré'];

    protected function casts(): array
    {
        return ['date_publication' => 'datetime', 'vues' => 'integer'];
    }

    public function categorie(): BelongsTo
    {
        return $this->belongsTo(CategorieBlog::class, 'categorie_blog_id');
    }

    public function commentaires(): HasMany
    {
        return $this->hasMany(CommentaireBlog::class, 'article_blog_id');
    }

    public function commentairesPublies(): HasMany
    {
        return $this->commentaires()->where('statut', 'Publié')->oldest();
    }

    /** +1 lecture, sans toucher à updated_at (la date de modification reste celle de l'auteur). */
    public function compterUneVue(): void
    {
        DB::table($this->getTable())->where('id', $this->getKey())->increment('vues');
        $this->vues = (int) $this->vues + 1;
    }

    protected static function booted(): void
    {
        static::saving(function (ArticleBlog $article) {
            if (blank($article->slug)) {
                $article->slug = Str::slug($article->titre);
            }
            if ($article->statut === 'Publié' && blank($article->date_publication)) {
                $article->date_publication = now();
            }
        });
    }

    /** Articles visibles : publiés et dont la date de publication est passée. */
    public function scopePublies(Builder $query): Builder
    {
        return $query->where('statut', 'Publié')
            ->where(fn ($q) => $q->whereNull('date_publication')->orWhere('date_publication', '<=', now()))
            ->orderByDesc('date_publication');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /** Extrait affiché dans la liste : celui saisi, sinon le début du texte. */
    public function resume(int $limite = 180): string
    {
        return $this->extrait ?: Str::limit(trim(strip_tags($this->contenu)), $limite);
    }

    /** Temps de lecture estimé (200 mots par minute, minimum 1). */
    public function minutesDeLecture(): int
    {
        return max(1, (int) ceil(str_word_count(strip_tags($this->contenu)) / 200));
    }
}
