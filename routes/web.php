<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PharmacyController;
use App\Http\Controllers\PharmacistController;
use App\Http\Controllers\Auth\RegisterController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Page d'accueil - Carte des pharmacies
Route::get('/', [PharmacyController::class, 'index'])->name('home');

// Routes d'authentification
Auth::routes();

// Routes d'inscription personnalisées
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Routes publiques pour les pharmacies
Route::get('/pharmacies', [PharmacyController::class, 'index'])->name('pharmacies.index');
Route::get('/pharmacies/search', [PharmacyController::class, 'searchPage'])->name('pharmacies.search');
Route::get('/pharmacies/{pharmacy}', [PharmacyController::class, 'show'])->name('pharmacies.show');
Route::post('/pharmacies/search', [PharmacyController::class, 'search'])->name('pharmacies.search');
Route::post('/pharmacies/search-by-city', [PharmacyController::class, 'searchByCity'])->name('pharmacies.search-by-city');
Route::get('/pharmacies-api/map', [PharmacyController::class, 'getPharmaciesForMap'])->name('pharmacies.api.map');

// Routes protégées pour les pharmaciens
Route::middleware(['auth'])->group(function () {
    // Dashboard pharmacien
    Route::get('/pharmacist/dashboard', [PharmacistController::class, 'dashboard'])->name('pharmacist.dashboard');
    
    // Gestion des pharmacies
    Route::get('/pharmacist/pharmacy/create', [PharmacistController::class, 'createPharmacy'])->name('pharmacist.create-pharmacy');
    Route::post('/pharmacist/pharmacy/store', [PharmacistController::class, 'storePharmacy'])->name('pharmacist.store-pharmacy');
    Route::get('/pharmacist/pharmacy/{pharmacy}/edit', [PharmacistController::class, 'editPharmacy'])->name('pharmacist.edit-pharmacy');
    Route::put('/pharmacist/pharmacy/{pharmacy}/update', [PharmacistController::class, 'updatePharmacy'])->name('pharmacist.update-pharmacy');
    
    // Complétion du profil
    Route::get('/pharmacist/complete-profile', [PharmacistController::class, 'createPharmacy'])->name('pharmacist.complete-profile');
    Route::post('/pharmacist/complete-profile', [PharmacistController::class, 'completeProfile'])->name('pharmacist.complete-profile.store');
    
    // Gestion du profil pharmacien
    Route::get('/pharmacist/profile', [PharmacistController::class, 'profile'])->name('pharmacist.profile');
    Route::put('/pharmacist/profile', [PharmacistController::class, 'updateProfile'])->name('pharmacist.profile.update');
    Route::put('/pharmacist/password', [PharmacistController::class, 'updatePassword'])->name('pharmacist.password.update');
    
    // Paramètres du pharmacien
    Route::get('/pharmacist/settings', [PharmacistController::class, 'settings'])->name('pharmacist.settings');
    Route::put('/pharmacist/settings/notifications', [PharmacistController::class, 'updateNotifications'])->name('pharmacist.settings.notifications');
    Route::put('/pharmacist/settings/display', [PharmacistController::class, 'updateDisplay'])->name('pharmacist.settings.display');

    // Localisation du pharmacien
    Route::get('/pharmacist/location', [PharmacistController::class, 'location'])->name('pharmacist.location');
    Route::put('/pharmacist/location', [PharmacistController::class, 'updateLocation'])->name('pharmacist.update-location');
});

// Routes pour les utilisateurs normaux
Route::middleware(['auth'])->group(function () {
    Route::get('/user/profile', [App\Http\Controllers\UserController::class, 'profile'])->name('user.profile');
    Route::put('/user/profile', [App\Http\Controllers\UserController::class, 'updateProfile'])->name('user.profile.update');
    Route::put('/user/password', [App\Http\Controllers\UserController::class, 'updatePassword'])->name('user.password.update');
    Route::get('/user/settings', [App\Http\Controllers\UserController::class, 'settings'])->name('user.settings');
    Route::put('/user/settings/notifications', [App\Http\Controllers\UserController::class, 'updateNotifications'])->name('user.settings.notifications');
    Route::put('/user/settings/display', [App\Http\Controllers\UserController::class, 'updateDisplay'])->name('user.settings.display');
});

// Routes d'administration
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
    
    // Gestion des utilisateurs
    Route::get('/users', [App\Http\Controllers\AdminController::class, 'users'])->name('users');
    Route::get('/users/create', [App\Http\Controllers\AdminController::class, 'createUser'])->name('users.create');
    Route::post('/users', [App\Http\Controllers\AdminController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{user}', [App\Http\Controllers\AdminController::class, 'showUser'])->name('users.show');
    Route::get('/users/{user}/edit', [App\Http\Controllers\AdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}', [App\Http\Controllers\AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [App\Http\Controllers\AdminController::class, 'destroyUser'])->name('users.destroy');
    
    // Gestion des pharmacies
    Route::get('/pharmacies', [App\Http\Controllers\AdminController::class, 'pharmacies'])->name('pharmacies');
    Route::get('/pharmacies/{pharmacy}', [App\Http\Controllers\AdminController::class, 'showPharmacy'])->name('pharmacies.show');
    Route::get('/pharmacies/{pharmacy}/edit', [App\Http\Controllers\AdminController::class, 'editPharmacy'])->name('pharmacies.edit');
    Route::put('/pharmacies/{pharmacy}', [App\Http\Controllers\AdminController::class, 'updatePharmacy'])->name('pharmacies.update');
    Route::delete('/pharmacies/{pharmacy}', [App\Http\Controllers\AdminController::class, 'destroyPharmacy'])->name('pharmacies.destroy');
    Route::post('/pharmacies/{pharmacy}/toggle-verification', [App\Http\Controllers\AdminController::class, 'togglePharmacyVerification'])->name('pharmacies.toggle-verification');
    Route::post('/pharmacies/{pharmacy}/toggle-status', [App\Http\Controllers\AdminController::class, 'togglePharmacyStatus'])->name('pharmacies.toggle-status');
    
    // Gestion des numéros d'autorisation
    Route::get('/authorization-numbers', [App\Http\Controllers\AdminController::class, 'authorizationNumbers'])->name('authorization-numbers');
    Route::get('/authorization-numbers/create', [App\Http\Controllers\AdminController::class, 'createAuthorizationNumber'])->name('authorization-numbers.create');
    Route::post('/authorization-numbers', [App\Http\Controllers\AdminController::class, 'storeAuthorizationNumber'])->name('authorization-numbers.store');
    Route::get('/authorization-numbers/{authorizationNumber}/edit', [App\Http\Controllers\AdminController::class, 'editAuthorizationNumber'])->name('authorization-numbers.edit');
    Route::put('/authorization-numbers/{authorizationNumber}', [App\Http\Controllers\AdminController::class, 'updateAuthorizationNumber'])->name('authorization-numbers.update');
            Route::delete('/authorization-numbers/{authorizationNumber}', [App\Http\Controllers\AdminController::class, 'destroyAuthorizationNumber'])->name('authorization-numbers.destroy');

            // Profil et paramètres administrateur
            Route::get('/profile', [App\Http\Controllers\AdminController::class, 'profile'])->name('profile');
            Route::put('/profile', [App\Http\Controllers\AdminController::class, 'updateProfile'])->name('profile.update');
            Route::put('/password', [App\Http\Controllers\AdminController::class, 'updatePassword'])->name('password.update');
            Route::get('/settings', [App\Http\Controllers\AdminController::class, 'settings'])->name('settings');
            Route::put('/settings/notifications', [App\Http\Controllers\AdminController::class, 'updateNotifications'])->name('settings.notifications');
            Route::put('/settings/system', [App\Http\Controllers\AdminController::class, 'updateSystemSettings'])->name('settings.system');
        });

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
