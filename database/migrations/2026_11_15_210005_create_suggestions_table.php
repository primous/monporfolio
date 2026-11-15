<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Suggestions anonymes (footer) : aucun nom, aucune coordonnée, aucune adresse IP enregistrée.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suggestions', function (Blueprint $table) {
            $table->id();
            $table->text('message');
            $table->string('page')->nullable();          // chemin de la page d'où elle a été envoyée
            $table->timestamp('lu_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suggestions');
    }
};
