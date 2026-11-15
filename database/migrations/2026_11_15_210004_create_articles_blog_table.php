<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Reprise de la table « articles_blog » de monportfolio (+ statut Brouillon et extrait).
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articles_blog', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->string('slug')->unique();
            $table->string('extrait', 300)->nullable();
            $table->longText('contenu');
            $table->enum('statut', ['Brouillon', 'Publié', 'Retiré'])->default('Brouillon');
            $table->dateTime('date_publication')->nullable();
            $table->timestamps();

            $table->index(['statut', 'date_publication']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles_blog');
    }
};
