<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Reprise de la table « services » de monportfolio (sans lien vers une table d'administrateurs).
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->text('description');
            $table->json('points')->nullable();          // ce qui est inclus (liste courte)
            $table->unsignedInteger('ordre_affichage')->default(0);
            $table->boolean('publie')->default(true);
            $table->timestamps();

            $table->index(['publie', 'ordre_affichage']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
