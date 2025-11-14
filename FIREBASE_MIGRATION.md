# Guide de Migration vers Firebase - GeoPharma

## Table des Matières

1. [Vue d'ensemble](#vue-densemble)
2. [Prérequis](#prérequis)
3. [Installation et Configuration](#installation-et-configuration)
4. [Migration des Modèles](#migration-des-modèles)
5. [Migration des Contrôleurs](#migration-des-contrôleurs)
6. [Migration des Vues](#migration-des-vues)
7. [Authentification Firebase](#authentification-firebase)
8. [Stockage des Fichiers](#stockage-des-fichiers)
9. [Déploiement](#déploiement)
10. [Troubleshooting](#troubleshooting)

---

## Vue d'ensemble

Ce guide vous accompagne dans la migration de **GeoPharma** (Laravel 8.83.29) de MySQL vers **Firebase** (Firestore). Firebase offre une base de données NoSQL en temps réel, une authentification intégrée, et un stockage de fichiers cloud.

**Version Laravel** : 8.83.29
**Version PHP requise** : ^7.3|^8.0 (selon composer.json)

### Avantages de Firebase

- **Base de données NoSQL** : Structure flexible, pas de schéma rigide
- **Temps réel** : Synchronisation automatique des données
- **Authentification intégrée** : Gestion des utilisateurs simplifiée
- **Scalabilité** : Gestion automatique de la montée en charge
- **Stockage cloud** : Stockage de fichiers et images
- **Gratuit jusqu'à un certain quota** : Plan Spark gratuit généreux

### Structure Firebase vs MySQL

| MySQL | Firebase Firestore |
|-------|-------------------|
| Tables | Collections |
| Lignes | Documents |
| Colonnes | Champs |
| Relations (JOIN) | Références ou données imbriquées |
| Requêtes SQL | Requêtes Firestore |

---

## Prérequis

### 1. Compte Firebase

1. Créer un compte sur [Firebase Console](https://console.firebase.google.com/)
2. Créer un nouveau projet Firebase
3. Activer **Firestore Database**
4. Activer **Authentication**
5. Activer **Storage** (optionnel, pour les fichiers)

### 2. Installation des Packages Laravel

```bash
composer require kreait/firebase-php
composer require kreait/laravel-firebase
```

### 3. Configuration Laravel

```bash
php artisan vendor:publish --provider="Kreait\Laravel\Firebase\ServiceProvider"
```

---

## Installation et Configuration

### 1. Configuration Firebase

#### Étape 1 : Obtenir les clés Firebase

1. Dans Firebase Console, allez dans **Paramètres du projet** → **Comptes de service**
2. Cliquez sur **Générer une nouvelle clé privée**
3. Téléchargez le fichier JSON

#### Étape 2 : Configuration dans Laravel

**Fichier : `.env`**

```env
# Firebase Configuration
FIREBASE_CREDENTIALS=storage/app/firebase-credentials.json
FIREBASE_DATABASE_URL=https://votre-projet.firebaseio.com
FIREBASE_PROJECT_ID=votre-projet-id
FIREBASE_STORAGE_BUCKET=votre-projet.appspot.com
```

**Fichier : `config/firebase.php`** (généré automatiquement)

```php
<?php

return [
    'credentials' => [
        'file' => env('FIREBASE_CREDENTIALS', storage_path('app/firebase-credentials.json')),
    ],
    'database' => [
        'url' => env('FIREBASE_DATABASE_URL'),
    ],
    'project_id' => env('FIREBASE_PROJECT_ID'),
    'storage' => [
        'bucket' => env('FIREBASE_STORAGE_BUCKET'),
    ],
];
```

#### Étape 3 : Placer le fichier de credentials

```bash
# Copier le fichier JSON téléchargé
cp ~/Downloads/votre-projet-firebase-adminsdk-xxxxx.json storage/app/firebase-credentials.json
```

### 2. Configuration Firestore

Dans Firebase Console :
1. Allez dans **Firestore Database**
2. Créez la base de données en mode **Production** ou **Test**
3. Configurez les règles de sécurité (voir section ci-dessous)

**Règles de sécurité Firestore (exemple)** :

```javascript
rules_version = '2';
service cloud.firestore {
  match /databases/{database}/documents {
    // Collection users
    match /users/{userId} {
      allow read: if request.auth != null;
      allow write: if request.auth != null && request.auth.uid == userId;
    }
    
    // Collection pharmacies
    match /pharmacies/{pharmacyId} {
      allow read: if true; // Public read
      allow create: if request.auth != null && 
                       (request.auth.token.role == 'pharmacist' || 
                        request.auth.token.role == 'admin');
      allow update, delete: if request.auth != null && 
                                (resource.data.pharmacist_id == request.auth.uid ||
                                 request.auth.token.role == 'admin');
    }
    
    // Collection authorization_numbers
    match /authorization_numbers/{authId} {
      allow read: if request.auth != null && request.auth.token.role == 'admin';
      allow write: if request.auth != null && request.auth.token.role == 'admin';
    }
  }
}
```

---

## Migration des Modèles

### 1. Créer un Service Firebase

**Fichier : `app/Services/FirebaseService.php`**

```php
<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Firestore;

class FirebaseService
{
    protected $firestore;
    
    public function __construct()
    {
        $factory = (new Factory)
            ->withServiceAccount(config('firebase.credentials.file'));
            
        $this->firestore = $factory->createFirestore();
    }
    
    public function getFirestore(): Firestore
    {
        return $this->firestore;
    }
    
    public function getDatabase()
    {
        return $this->firestore->database();
    }
}
```

### 2. Adapter le Modèle User

**Fichier : `app/Models/User.php`** (version Firebase)

```php
<?php

namespace App\Models;

use App\Services\FirebaseService;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;

class User implements Authenticatable
{
    use AuthenticatableTrait;
    
    protected $collection = 'users';
    protected $fillable = [
        'name', 'email', 'password', 'role', 'authorization_number',
        'profile_completed', 'latitude', 'longitude', 'address',
        'city', 'postal_code', 'email_verified_at'
    ];
    
    protected $hidden = ['password', 'remember_token'];
    
    protected $firebaseService;
    
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->firebaseService = app(FirebaseService::class);
    }
    
    /**
     * Récupérer un utilisateur par ID
     */
    public static function find($id)
    {
        $firebase = app(FirebaseService::class);
        $database = $firebase->getDatabase();
        $collection = $database->collection('users');
        
        $document = $collection->document($id)->snapshot();
        
        if (!$document->exists()) {
            return null;
        }
        
        $user = new static();
        $user->id = $id;
        $user->fill($document->data());
        
        return $user;
    }
    
    /**
     * Récupérer un utilisateur par email
     */
    public static function whereEmail($email)
    {
        $firebase = app(FirebaseService::class);
        $database = $firebase->getDatabase();
        $collection = $database->collection('users');
        
        $query = $collection->where('email', '=', $email);
        $documents = $query->documents();
        
        if ($documents->isEmpty()) {
            return null;
        }
        
        $doc = $documents->first();
        $user = new static();
        $user->id = $doc->id();
        $user->fill($doc->data());
        
        return $user;
    }
    
    /**
     * Créer un nouvel utilisateur
     */
    public function save(array $options = [])
    {
        $firebase = app(FirebaseService::class);
        $database = $firebase->getDatabase();
        $collection = $database->collection('users');
        
        $data = $this->toArray();
        unset($data['id']);
        
        if (isset($this->id)) {
            // Mise à jour
            $collection->document($this->id)->set($data, ['merge' => true]);
        } else {
            // Création
            $docRef = $collection->add($data);
            $this->id = $docRef->id();
        }
        
        return true;
    }
    
    /**
     * Supprimer un utilisateur
     */
    public function delete()
    {
        $firebase = app(FirebaseService::class);
        $database = $firebase->getDatabase();
        $collection = $database->collection('users');
        
        $collection->document($this->id)->delete();
        
        return true;
    }
    
    /**
     * Vérifier si l'utilisateur est admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
    
    /**
     * Vérifier si l'utilisateur est pharmacien
     */
    public function isPharmacist()
    {
        return $this->role === 'pharmacist';
    }
    
    /**
     * Convertir en tableau
     */
    public function toArray()
    {
        $attributes = [];
        foreach ($this->fillable as $field) {
            if (isset($this->attributes[$field])) {
                $attributes[$field] = $this->attributes[$field];
            }
        }
        return $attributes;
    }
}
```

### 3. Adapter le Modèle Pharmacy

**Fichier : `app/Models/Pharmacy.php`** (version Firebase)

```php
<?php

namespace App\Models;

use App\Services\FirebaseService;

class Pharmacy
{
    protected $collection = 'pharmacies';
    protected $fillable = [
        'name', 'description', 'address', 'city', 'postal_code', 'country',
        'latitude', 'longitude', 'phone', 'email', 'whatsapp_number',
        'opening_hours', 'services', 'is_active', 'is_verified', 'pharmacist_id'
    ];
    
    protected $casts = [
        'opening_hours' => 'array',
        'services' => 'array',
        'is_active' => 'boolean',
        'is_verified' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float',
    ];
    
    public $id;
    protected $attributes = [];
    
    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);
    }
    
    /**
     * Remplir les attributs
     */
    public function fill(array $attributes)
    {
        foreach ($attributes as $key => $value) {
            if (in_array($key, $this->fillable)) {
                $this->attributes[$key] = $value;
            }
        }
        return $this;
    }
    
    /**
     * Récupérer un attribut
     */
    public function __get($key)
    {
        if (isset($this->attributes[$key])) {
            $value = $this->attributes[$key];
            
            // Appliquer les casts
            if (isset($this->casts[$key])) {
                switch ($this->casts[$key]) {
                    case 'array':
                        return is_array($value) ? $value : json_decode($value, true);
                    case 'boolean':
                        return (bool) $value;
                    case 'float':
                        return (float) $value;
                }
            }
            
            return $value;
        }
        
        // Relations
        if ($key === 'pharmacist') {
            return $this->pharmacist();
        }
        
        return null;
    }
    
    /**
     * Définir un attribut
     */
    public function __set($key, $value)
    {
        $this->attributes[$key] = $value;
    }
    
    /**
     * Récupérer une pharmacie par ID
     */
    public static function find($id)
    {
        $firebase = app(FirebaseService::class);
        $database = $firebase->getDatabase();
        $collection = $database->collection('pharmacies');
        
        $document = $collection->document($id)->snapshot();
        
        if (!$document->exists()) {
            return null;
        }
        
        $pharmacy = new static();
        $pharmacy->id = $id;
        $pharmacy->fill($document->data());
        
        return $pharmacy;
    }
    
    /**
     * Créer une nouvelle pharmacie
     */
    public static function create(array $attributes)
    {
        $pharmacy = new static($attributes);
        $pharmacy->save();
        return $pharmacy;
    }
    
    /**
     * Sauvegarder la pharmacie
     */
    public function save()
    {
        $firebase = app(FirebaseService::class);
        $database = $firebase->getDatabase();
        $collection = $database->collection('pharmacies');
        
        $data = [];
        foreach ($this->fillable as $field) {
            if (isset($this->attributes[$field])) {
                $value = $this->attributes[$field];
                
                // Convertir les arrays en format Firestore
                if (is_array($value) && in_array($field, ['opening_hours', 'services'])) {
                    $data[$field] = $value;
                } else {
                    $data[$field] = $value;
                }
            }
        }
        
        if (isset($this->id)) {
            // Mise à jour
            $collection->document($this->id)->set($data, ['merge' => true]);
        } else {
            // Création
            $docRef = $collection->add($data);
            $this->id = $docRef->id();
        }
        
        return true;
    }
    
    /**
     * Mettre à jour la pharmacie
     */
    public function update(array $attributes)
    {
        $this->fill($attributes);
        return $this->save();
    }
    
    /**
     * Supprimer la pharmacie
     */
    public function delete()
    {
        $firebase = app(FirebaseService::class);
        $database = $firebase->getDatabase();
        $collection = $database->collection('pharmacies');
        
        $collection->document($this->id)->delete();
        
        return true;
    }
    
    /**
     * Relation avec le pharmacien
     */
    public function pharmacist()
    {
        if (!$this->pharmacist_id) {
            return null;
        }
        
        return User::find($this->pharmacist_id);
    }
    
    /**
     * Scope pour les pharmacies actives
     */
    public static function active()
    {
        $firebase = app(FirebaseService::class);
        $database = $firebase->getDatabase();
        $collection = $database->collection('pharmacies');
        
        $query = $collection->where('is_active', '=', true);
        $documents = $query->documents();
        
        $pharmacies = [];
        foreach ($documents as $doc) {
            $pharmacy = new static();
            $pharmacy->id = $doc->id();
            $pharmacy->fill($doc->data());
            $pharmacies[] = $pharmacy;
        }
        
        return collect($pharmacies);
    }
    
    /**
     * Scope pour les pharmacies vérifiées
     */
    public static function verified()
    {
        $firebase = app(FirebaseService::class);
        $database = $firebase->getDatabase();
        $collection = $database->collection('pharmacies');
        
        $query = $collection->where('is_verified', '=', true);
        $documents = $query->documents();
        
        $pharmacies = [];
        foreach ($documents as $doc) {
            $pharmacy = new static();
            $pharmacy->id = $doc->id();
            $pharmacy->fill($doc->data());
            $pharmacies[] = $pharmacy;
        }
        
        return collect($pharmacies);
    }
    
    /**
     * Recherche par proximité géographique
     */
    public static function nearby($latitude, $longitude, $radius = 10)
    {
        $firebase = app(FirebaseService::class);
        $database = $firebase->getDatabase();
        $collection = $database->collection('pharmacies');
        
        // Firestore ne supporte pas nativement les requêtes géospatiales
        // Il faut récupérer toutes les pharmacies et filtrer en PHP
        $query = $collection->where('is_active', '=', true)
                            ->where('is_verified', '=', true);
        $documents = $query->documents();
        
        $pharmacies = [];
        foreach ($documents as $doc) {
            $pharmacy = new static();
            $pharmacy->id = $doc->id();
            $pharmacy->fill($doc->data());
            
            // Calculer la distance
            $distance = $pharmacy->calculateDistance($latitude, $longitude);
            
            if ($distance <= $radius) {
                $pharmacy->distance = $distance;
                $pharmacies[] = $pharmacy;
            }
        }
        
        // Trier par distance
        usort($pharmacies, function($a, $b) {
            return $a->distance <=> $b->distance;
        });
        
        return collect($pharmacies);
    }
    
    /**
     * Calculer la distance (formule Haversine)
     */
    protected function calculateDistance($lat1, $lon1)
    {
        $lat2 = $this->latitude;
        $lon2 = $this->longitude;
        
        $earthRadius = 6371; // Rayon de la Terre en km
        
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        
        $a = sin($dLat/2) * sin($dLat/2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon/2) * sin($dLon/2);
        
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        
        return $earthRadius * $c;
    }
    
    /**
     * Messages pré-définis pour contact
     */
    public function getPredefinedMessage()
    {
        $hour = (int) date('H');
        $greeting = ($hour >= 18 || $hour < 6) ? 'Bonsoir' : 'Bonjour';
        
        $userName = auth()->check() ? auth()->user()->name : 'un utilisateur';
        
        $message = "{$greeting} {$this->name}, je suis {$userName} depuis l'application GeoPharma. Je souhaite obtenir des informations sur vos services et vos horaires d'ouverture. Pourriez-vous me renseigner ?";
        
        return $message;
    }
    
    /**
     * URL WhatsApp avec message pré-défini
     */
    public function getWhatsappUrlAttribute()
    {
        if (!$this->whatsapp_number) {
            return null;
        }
        
        $phone = preg_replace('/[^0-9]/', '', $this->whatsapp_number);
        $message = urlencode($this->getPredefinedMessage());
        
        return "https://wa.me/{$phone}?text={$message}";
    }
    
    /**
     * URL Email avec message pré-défini
     */
    public function getEmailUrlAttribute()
    {
        if (!$this->email) {
            return null;
        }
        
        $subject = urlencode("Contact depuis GeoPharma");
        $message = urlencode($this->getPredefinedMessage());
        
        return "mailto:{$this->email}?subject={$subject}&body={$message}";
    }
}
```

---

## Migration des Contrôleurs

### Exemple : PharmacyController avec Firebase

**Fichier : `app/Http/Controllers/PharmacyController.php`** (extrait)

```php
<?php

namespace App\Http\Controllers;

use App\Models\Pharmacy;
use App\Services\FirebaseService;
use Illuminate\Http\Request;

class PharmacyController extends Controller
{
    protected $firebase;
    
    public function __construct()
    {
        $this->firebase = app(FirebaseService::class);
    }
    
    /**
     * Afficher toutes les pharmacies sur la carte
     */
    public function index()
    {
        $database = $this->firebase->getDatabase();
        $collection = $database->collection('pharmacies');
        
        // Récupérer uniquement les pharmacies actives et vérifiées
        $query = $collection->where('is_active', '=', true)
                            ->where('is_verified', '=', true);
        $documents = $query->documents();
        
        $pharmacies = [];
        foreach ($documents as $doc) {
            $pharmacy = new Pharmacy();
            $pharmacy->id = $doc->id();
            $pharmacy->fill($doc->data());
            $pharmacies[] = $pharmacy;
        }
        
        return view('pharmacies.index', compact('pharmacies'));
    }
    
    /**
     * Recherche par nom
     */
    public function searchByName(Request $request)
    {
        $searchName = $request->input('search_name');
        
        if (!$searchName) {
            return response()->json(['pharmacies' => []]);
        }
        
        $database = $this->firebase->getDatabase();
        $collection = $database->collection('pharmacies');
        
        // Firestore ne supporte pas LIKE, il faut récupérer et filtrer
        $query = $collection->where('is_active', '=', true)
                            ->where('is_verified', '=', true);
        $documents = $query->documents();
        
        $pharmacies = [];
        foreach ($documents as $doc) {
            $data = $doc->data();
            $name = strtolower($data['name'] ?? '');
            $search = strtolower($searchName);
            
            if (strpos($name, $search) !== false) {
                $pharmacy = new Pharmacy();
                $pharmacy->id = $doc->id();
                $pharmacy->fill($data);
                $pharmacies[] = $pharmacy;
            }
        }
        
        return response()->json(['pharmacies' => $pharmacies]);
    }
    
    /**
     * Recherche par proximité
     */
    public function searchByProximity(Request $request)
    {
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $radius = $request->input('radius', 10);
        
        $pharmacies = Pharmacy::nearby($latitude, $longitude, $radius);
        
        return response()->json(['pharmacies' => $pharmacies->toArray()]);
    }
}
```

---

## Authentification Firebase

### Option 1 : Utiliser Firebase Authentication

**Fichier : `app/Http/Controllers/Auth/LoginController.php`** (version Firebase)

```php
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Kreait\Firebase\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    protected $auth;
    
    public function __construct()
    {
        $this->auth = app('firebase.auth');
    }
    
    /**
     * Authentifier un utilisateur
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        try {
            // Vérifier les credentials avec Firebase
            $signInResult = $this->auth->signInWithEmailAndPassword(
                $request->email,
                $request->password
            );
            
            $user = $signInResult->data();
            
            // Récupérer les données utilisateur depuis Firestore
            $firebase = app(\App\Services\FirebaseService::class);
            $database = $firebase->getDatabase();
            $collection = $database->collection('users');
            
            $query = $collection->where('email', '=', $request->email);
            $documents = $query->documents();
            
            if ($documents->isEmpty()) {
                return back()->withErrors(['email' => 'Utilisateur non trouvé.']);
            }
            
            $doc = $documents->first();
            $userData = $doc->data();
            
            // Créer la session Laravel
            session(['firebase_user' => $userData]);
            session(['firebase_uid' => $doc->id()]);
            
            // Rediriger selon le rôle
            if ($userData['role'] === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($userData['role'] === 'pharmacist') {
                return redirect()->route('pharmacist.dashboard');
            } else {
                return redirect()->route('pharmacies.index');
            }
            
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Identifiants incorrects.']);
        }
    }
}
```

### Option 2 : Garder Laravel Auth avec Firebase comme source

Créer un **User Provider personnalisé** :

**Fichier : `app/Auth/FirebaseUserProvider.php`**

```php
<?php

namespace App\Auth;

use App\Models\User;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable;

class FirebaseUserProvider implements UserProvider
{
    public function retrieveById($identifier)
    {
        return User::find($identifier);
    }
    
    public function retrieveByToken($identifier, $token)
    {
        // Implémenter si nécessaire
        return null;
    }
    
    public function updateRememberToken(Authenticatable $user, $token)
    {
        // Implémenter si nécessaire
    }
    
    public function retrieveByCredentials(array $credentials)
    {
        if (!isset($credentials['email'])) {
            return null;
        }
        
        return User::whereEmail($credentials['email']);
    }
    
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        return Hash::check($credentials['password'], $user->password);
    }
}
```

**Fichier : `config/auth.php`** (modification)

```php
'providers' => [
    'users' => [
        'driver' => 'firebase', // Nouveau driver
        'model' => App\Models\User::class,
    ],
],
```

**Fichier : `app/Providers/AuthServiceProvider.php`**

```php
public function boot()
{
    Auth::provider('firebase', function ($app, array $config) {
        return new \App\Auth\FirebaseUserProvider();
    });
}
```

---

## Migration des Données

### Script de Migration MySQL → Firebase

**Fichier : `database/migrations/migrate_to_firebase.php`**

```php
<?php

require __DIR__.'/../../vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use App\Services\FirebaseService;

$app = require_once __DIR__.'/../../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$firebase = app(FirebaseService::class);
$database = $firebase->getDatabase();

// Migrer les utilisateurs
echo "Migration des utilisateurs...\n";
$users = DB::table('users')->get();
$usersCollection = $database->collection('users');

foreach ($users as $user) {
    $data = (array) $user;
    unset($data['id']);
    $usersCollection->document($user->id)->set($data);
    echo "Utilisateur {$user->id} migré\n";
}

// Migrer les pharmacies
echo "Migration des pharmacies...\n";
$pharmacies = DB::table('pharmacies')->get();
$pharmaciesCollection = $database->collection('pharmacies');

foreach ($pharmacies as $pharmacy) {
    $data = (array) $pharmacy;
    unset($data['id']);
    
    // Convertir les champs JSON
    if (isset($data['opening_hours'])) {
        $data['opening_hours'] = json_decode($data['opening_hours'], true);
    }
    if (isset($data['services'])) {
        $data['services'] = json_decode($data['services'], true);
    }
    
    $pharmaciesCollection->document($pharmacy->id)->set($data);
    echo "Pharmacie {$pharmacy->id} migrée\n";
}

// Migrer les numéros d'autorisation
echo "Migration des numéros d'autorisation...\n";
$authNumbers = DB::table('authorization_numbers')->get();
$authCollection = $database->collection('authorization_numbers');

foreach ($authNumbers as $auth) {
    $data = (array) $auth;
    unset($data['id']);
    $authCollection->document($auth->id)->set($data);
    echo "Numéro d'autorisation {$auth->id} migré\n";
}

echo "Migration terminée !\n";
```

**Exécution** :

```bash
php database/migrations/migrate_to_firebase.php
```

---

## Stockage des Fichiers

### Configuration Firebase Storage

**Fichier : `app/Services/FirebaseStorageService.php`**

```php
<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Storage;

class FirebaseStorageService
{
    protected $storage;
    
    public function __construct()
    {
        $factory = (new Factory)
            ->withServiceAccount(config('firebase.credentials.file'));
            
        $this->storage = $factory->createStorage();
    }
    
    /**
     * Uploader un fichier
     */
    public function upload($file, $path)
    {
        $bucket = $this->storage->getBucket();
        $object = $bucket->upload(
            fopen($file->getRealPath(), 'r'),
            ['name' => $path]
        );
        
        return $object->signedUrl(new \DateTime('+1 year'));
    }
    
    /**
     * Supprimer un fichier
     */
    public function delete($path)
    {
        $bucket = $this->storage->getBucket();
        $object = $bucket->object($path);
        $object->delete();
    }
}
```

---

## Déploiement

### Variables d'Environnement de Production

**Fichier : `.env.production`**

```env
APP_ENV=production
APP_DEBUG=false

# Firebase
FIREBASE_CREDENTIALS=/path/to/firebase-credentials.json
FIREBASE_DATABASE_URL=https://votre-projet.firebaseio.com
FIREBASE_PROJECT_ID=votre-projet-id
FIREBASE_STORAGE_BUCKET=votre-projet.appspot.com
```

### Sécurité

1. **Ne jamais commiter** le fichier `firebase-credentials.json`
2. Ajouter à `.gitignore` :
   ```
   storage/app/firebase-credentials.json
   ```
3. Utiliser des variables d'environnement pour les credentials en production
4. Configurer les règles Firestore strictement

---

## Troubleshooting

### Problème : Erreur de credentials

**Solution** : Vérifier que le fichier JSON est au bon endroit et lisible

```bash
ls -la storage/app/firebase-credentials.json
chmod 600 storage/app/firebase-credentials.json
```

### Problème : Requêtes lentes

**Solution** : 
- Utiliser des index Firestore pour les requêtes fréquentes
- Limiter le nombre de documents récupérés
- Utiliser la pagination

### Problème : Relations entre collections

**Solution** : 
- Utiliser des références Firestore
- Ou stocker les données nécessaires directement dans le document
- Éviter les requêtes multiples dans les boucles

### Problème : Authentification

**Solution** :
- Vérifier que Firebase Authentication est activé
- Vérifier les règles de sécurité Firestore
- Vérifier les tokens dans les requêtes

---

## Ressources

- [Documentation Firebase PHP](https://firebase-php.readthedocs.io/)
- [Documentation Firestore](https://firebase.google.com/docs/firestore)
- [Documentation Firebase Authentication](https://firebase.google.com/docs/auth)
- [Kreait Firebase PHP](https://github.com/kreait/firebase-php)

---

## Conclusion

Cette migration vers Firebase offre une architecture plus flexible et scalable. Les principaux avantages sont :

- **Flexibilité** : Structure de données adaptative
- **Temps réel** : Synchronisation automatique
- **Scalabilité** : Gestion automatique de la charge
- **Intégration** : Services Firebase intégrés (Auth, Storage, etc.)

**Note importante** : Cette migration nécessite des tests approfondis avant la mise en production. Assurez-vous de tester toutes les fonctionnalités critiques.

---

**Dernière mise à jour : 2025**

