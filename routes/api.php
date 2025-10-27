<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PharmacyApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Routes API publiques pour les pharmacies
Route::prefix('pharmacies')->group(function () {
    // Obtenir toutes les pharmacies
    Route::get('/', [PharmacyApiController::class, 'index']);
    
    // Obtenir les pharmacies pour l'affichage sur carte
    Route::get('/map', [PharmacyApiController::class, 'forMap']);
    
    // Rechercher des pharmacies par proximité
    Route::post('/nearby', [PharmacyApiController::class, 'nearby']);
    
    // Rechercher des pharmacies par ville
    Route::post('/search-by-city', [PharmacyApiController::class, 'searchByCity']);
    
    // Recherche avancée avec filtres
    Route::post('/search', [PharmacyApiController::class, 'search']);
    
    // Obtenir les détails d'une pharmacie
    Route::get('/{pharmacy}', [PharmacyApiController::class, 'show']);
});

// Route pour l'utilisateur connecté (Sanctum)
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
