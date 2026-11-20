<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Catégorie d'un article + compteur de lectures (vues réelles, une par visiteur et par session).
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('articles_blog', function (Blueprint $table) {
            $table->foreignId('categorie_blog_id')->nullable()->after('id')
                ->constrained('categories_blog')->nullOnDelete();
            $table->unsignedInteger('vues')->default(0)->after('date_publication');
        });
    }

    public function down(): void
    {
        Schema::table('articles_blog', function (Blueprint $table) {
            $table->dropConstrainedForeignId('categorie_blog_id');
            $table->dropColumn('vues');
        });
    }
};
