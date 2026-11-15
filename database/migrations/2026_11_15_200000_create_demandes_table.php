<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('demandes', function (Blueprint $table) {
            $table->id();
            $table->text('activity');
            $table->string('has_website');
            $table->json('difficulties');
            $table->text('main_difficulty')->nullable();
            $table->text('objective');
            $table->text('need');
            $table->string('budget')->nullable();
            $table->boolean('budget_unknown')->default(false);
            $table->string('full_name');
            $table->string('email');
            $table->string('phone');
            $table->boolean('consent')->default(true);
            $table->timestamp('consent_at')->nullable();
            $table->string('statut')->default('nouveau');
            $table->timestamps();

            $table->index('statut');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demandes');
    }
};

