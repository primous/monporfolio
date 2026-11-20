<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommentaireBlog extends Model
{
    protected $table = 'commentaires_blog';

    protected $fillable = ['article_blog_id', 'nom', 'message', 'statut'];

    public const STATUTS = ['En attente' => 'En attente', 'Publié' => 'Publié', 'Retiré' => 'Retiré'];

    public function article(): BelongsTo
    {
        return $this->belongsTo(ArticleBlog::class, 'article_blog_id');
    }
}
