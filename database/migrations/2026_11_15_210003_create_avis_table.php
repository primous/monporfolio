<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Reprise de la table « avis » de monportfolio : avis clients publics (nom + activité).
// Seuls les avis « Publié » s'affichent sur le site.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('avis', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('activite')->nullable();
            $table->text('contenu');
            $table->enum('statut', ['En attente', 'Publié', 'Retiré'])->default('En attente');
            $table->timestamps();

            $table->index('statut');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('avis');
    }
};
