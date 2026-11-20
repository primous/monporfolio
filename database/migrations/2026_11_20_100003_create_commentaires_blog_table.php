<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Commentaires des articles : modérés dans l'admin (seuls les « Publié » s'affichent).
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commentaires_blog', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_blog_id')->constrained('articles_blog')->cascadeOnDelete();
            $table->string('nom', 80);
            $table->text('message');
            $table->enum('statut', ['En attente', 'Publié', 'Retiré'])->default('En attente');
            $table->timestamps();

            $table->index(['article_blog_id', 'statut']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commentaires_blog');
    }
};
