<?php

use App\Http\Controllers\AvisController;
use App\Http\Controllers\CommentaireController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DemandeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SuggestionController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');

// Pages du menu (contenu géré dans l'admin Filament, groupe « Contenu du site »).
Route::get('/services', [PageController::class, 'services'])->name('services');
Route::get('/projets', [PageController::class, 'projets'])->name('projets');
Route::get('/parcours', [PageController::class, 'parcours'])->name('parcours');
Route::get('/avis', [PageController::class, 'avis'])->name('avis');
Route::post('/avis', [AvisController::class, 'store'])
    ->name('avis.store')
    ->middleware('throttle:5,10');
Route::get('/blog', [PageController::class, 'blog'])->name('blog');
Route::get('/blog/{slug}', [PageController::class, 'article'])->name('blog.article');
Route::post('/blog/{slug}/commentaires', [CommentaireController::class, 'store'])
    ->name('blog.commentaire')
    ->middleware('throttle:5,10');

// Suggestion anonyme (pied de page) : JSON, 201 si enregistrée.
Route::post('/suggestion', [SuggestionController::class, 'store'])
    ->name('suggestion.store')
    ->middleware('throttle:5,10');

// Ancien formulaire de contact (conservé).
Route::post('/contact', [ContactController::class, 'store'])
    ->name('contact.store')
    ->middleware('throttle:5,10');

// Formulaire « demande de projet » en 5 étapes (POST en JSON, 201 si enregistré).
Route::view('/demande-de-projet', 'demande-de-projet')->name('demande-de-projet');

Route::post('/demande-de-projet', [DemandeController::class, 'store'])
    ->name('demande-de-projet.store')
    ->middleware('throttle:5,10');
