<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Reprise de la table « projets » de monportfolio.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projets', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->string('slug')->unique();
            $table->string('resume')->nullable();        // une ligne (cartes)
            $table->text('description');
            $table->string('visuel')->nullable();        // chemin dans public/ (ex. images/hero/hero-1.jpg)
            $table->string('lien')->nullable();          // site en ligne, si public
            $table->unsignedInteger('ordre_affichage')->default(0);
            $table->boolean('publie')->default(true);
            $table->timestamps();

            $table->index(['publie', 'ordre_affichage']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projets');
    }
};
