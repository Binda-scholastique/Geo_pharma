# Guide Complet : Utilisation de Firebase Firestore avec GeoPharma

## 📋 Table des Matières

1. [Introduction](#introduction)
2. [Création du Projet Firebase](#création-du-projet-firebase)
3. [Configuration Firebase dans Firebase Console](#configuration-firebase-dans-firebase-console)
4. [Installation et Configuration dans Laravel](#installation-et-configuration-dans-laravel)
5. [Architecture et Structure des Données](#architecture-et-structure-des-données)
6. [Comment Fonctionne la Connexion à Firebase](#comment-fonctionne-la-connexion-à-firebase)
7. [Utilisation dans le Code](#utilisation-dans-le-code)
8. [Migration des Données](#migration-des-données)
9. [Dépannage](#dépannage)

---

## Introduction

Ce guide explique comment **GeoPharma** utilise **Firebase Firestore** comme base de données principale au lieu de MySQL. Firebase Firestore est une base de données NoSQL orientée documents, qui offre une scalabilité automatique et une synchronisation en temps réel.

### Pourquoi Firebase Firestore ?

- ✅ **Scalabilité automatique** : Pas besoin de gérer les serveurs
- ✅ **Temps réel** : Synchronisation automatique des données
- ✅ **Sécurité** : Règles de sécurité intégrées
- ✅ **API REST** : Accès via HTTP sans extensions PHP complexes
- ✅ **Gratuit jusqu'à un certain seuil** : Plan gratuit généreux

---

## Création du Projet Firebase

### Étape 1 : Créer un Compte Firebase

1. Allez sur [https://console.firebase.google.com](https://console.firebase.google.com)
2. Connectez-vous avec votre compte Google
3. Cliquez sur **"Ajouter un projet"** ou **"Créer un projet"**

### Étape 2 : Configurer le Projet

1. **Nom du projet** : `geopharma-b25da` (ou votre nom de projet)
2. **Google Analytics** : Activez-le si vous souhaitez suivre l'utilisation (optionnel)
3. Cliquez sur **"Créer le projet"**
4. Attendez que le projet soit créé (quelques secondes)

### Étape 3 : Obtenir les Credentials

1. Dans Firebase Console, allez dans **Paramètres du projet** (icône ⚙️ en haut à gauche)
2. Allez dans l'onglet **"Comptes de service"**
3. Cliquez sur **"Générer une nouvelle clé privée"**
4. Un fichier JSON sera téléchargé (ex: `geopharma-b25da-firebase-adminsdk-xxxxx.json`)
5. **Renommez ce fichier** en `firebase.credentials.json`
6. **Placez-le** dans `storage/app/firebase.credentials.json` de votre projet Laravel

**⚠️ IMPORTANT** : Ce fichier contient des clés secrètes. Ne le commitez JAMAIS dans Git ! Ajoutez-le au `.gitignore` :

```gitignore
/storage/app/firebase.credentials.json
```

---

## Configuration Firebase dans Firebase Console

### Étape 1 : Activer Firestore Database

1. Dans Firebase Console, cliquez sur **"Firestore Database"** dans le menu de gauche
2. Si la base n'existe pas, cliquez sur **"Créer une base de données"**
3. Choisissez le mode :
   - **Mode Production** : Recommandé pour la production (nécessite des règles de sécurité)
   - **Mode Test** : Pour le développement (permet toutes les opérations pendant 30 jours)
4. Sélectionnez une région : **us-central** ou **europe-west** (selon votre localisation)
5. Cliquez sur **"Activer"**

### Étape 2 : Configurer les Règles de Sécurité

1. Dans Firestore Database, cliquez sur l'onglet **"Règles"**
2. Copiez et collez les règles suivantes :

```javascript
rules_version = '2';
service cloud.firestore {
  match /databases/{database}/documents {
    
    // Collection users
    match /users/{userId} {
      // Permettre la lecture et l'écriture pour le service account
      // (utilisé par Laravel via les credentials)
      allow read, write: if true;
    }
    
    // Collection pharmacies
    match /pharmacies/{pharmacyId} {
      // Permettre la lecture et l'écriture pour le service account
      allow read, write: if true;
    }
    
    // Collection authorization_numbers
    match /authorization_numbers/{authId} {
      // Permettre la lecture et l'écriture pour le service account
      allow read, write: if true;
    }
  }
}
```

**⚠️ ATTENTION** : Ces règles permettent toutes les opérations. Pour la production, vous devriez implémenter des règles plus strictes basées sur l'authentification.

3. Cliquez sur **"Publier"** pour sauvegarder les règles

### Étape 3 : Vérifier le Service Account

1. Allez dans **Paramètres du projet** → **Comptes de service**
2. Vérifiez que le compte `firebase-adminsdk-fbsvc@geopharma-b25da.iam.gserviceaccount.com` existe
3. Si nécessaire, créez un nouveau compte de service avec les rôles :
   - **Firebase Admin SDK Administrator Service Agent**
   - **Cloud Datastore User**

### Étape 4 : Activer l'API Firestore (si nécessaire)

1. Allez dans [Google Cloud Console](https://console.cloud.google.com)
2. Sélectionnez le projet **geopharma-b25da**
3. Allez dans **APIs & Services** → **Library**
4. Recherchez **"Cloud Firestore API"**
5. Si elle n'est pas activée, cliquez sur **"Enable"**

---

## Installation et Configuration dans Laravel

### Étape 1 : Installer les Packages Firebase

```bash
composer require kreait/firebase-php:^7.0 --with-all-dependencies --ignore-platform-req=ext-sodium
```

**Note** : L'extension `sodium` est ignorée car elle n'est pas critique. L'extension `grpc` n'est pas nécessaire car nous utilisons l'API REST HTTP.

### Étape 2 : Créer le Fichier de Configuration

Le fichier `config/firebase.php` a été créé avec la configuration suivante :

```php
<?php

return [
    'credentials' => [
        'file' => storage_path('app/firebase.credentials.json'),
    ],
    'project_id' => env('FIREBASE_PROJECT_ID', 'geopharma-b25da'),
    'database_url' => env('FIREBASE_DATABASE_URL', 'https://geopharma-b25da-default-rtdb.firebaseio.com'),
    'storage_bucket' => env('FIREBASE_STORAGE_BUCKET', 'geopharma-b25da.appspot.com'),
    'collections' => [
        'users' => 'users',
        'pharmacies' => 'pharmacies',
        'authorization_numbers' => 'authorization_numbers',
    ],
];
```

### Étape 3 : Configurer l'Authentification Laravel

Le fichier `config/auth.php` a été modifié pour utiliser Firebase :

```php
'providers' => [
    'users' => [
        'driver' => 'firebase',
        'model' => App\Models\FirebaseUser::class,
    ],
],
```

Le `AuthServiceProvider` enregistre le provider Firebase personnalisé.

---

## Architecture et Structure des Données

### Comment les Données sont Stockées dans Firebase

Firebase Firestore organise les données en **collections** et **documents** :

```
Firestore Database
├── users (collection)
│   ├── 1 (document)
│   │   ├── name: "Dr. Jedidia Umba"
│   │   ├── email: "jedidia.umba@geopharma.com"
│   │   ├── role: "pharmacist"
│   │   ├── password: "$2y$10$..."
│   │   └── ...
│   ├── 2 (document)
│   └── ...
│
├── pharmacies (collection)
│   ├── 1 (document)
│   │   ├── name: "Pharmacie Centrale"
│   │   ├── address: "Avenue Kasa-Vubu"
│   │   ├── latitude: -4.3276
│   │   ├── longitude: 15.3136
│   │   ├── pharmacist_id: "1"
│   │   ├── opening_hours: { "monday": {...}, ... }
│   │   └── ...
│   └── ...
│
└── authorization_numbers (collection)
    ├── 1 (document)
    │   ├── number: "PH001234567"
    │   ├── is_valid: true
    │   └── ...
    └── ...
```

### Structure des Documents dans Firestore

Dans Firebase Console, vous verrez les données organisées ainsi :

```
Firestore Database
└── (default) database
    ├── users (collection)
    │   ├── 1 (document ID)
    │   │   ├── name: "Dr. Jedidia Umba" (string)
    │   │   ├── email: "jedidia.umba@geopharma.com" (string)
    │   │   ├── password: "$2y$10$..." (string)
    │   │   ├── role: "pharmacist" (string)
    │   │   ├── authorization_number: "PH001234567" (string)
    │   │   ├── profile_completed: true (boolean)
    │   │   ├── latitude: -4.3276 (number)
    │   │   ├── longitude: 15.3136 (number)
    │   │   ├── address: "Avenue Kasa-Vubu, Gombe" (string)
    │   │   ├── city: "Kinshasa" (string)
    │   │   ├── postal_code: "001" (string)
    │   │   ├── email_verified_at: "2025-01-01T00:00:00Z" (timestamp)
    │   │   ├── created_at: "2025-01-01T00:00:00Z" (timestamp)
    │   │   └── updated_at: "2025-01-01T00:00:00Z" (timestamp)
    │   ├── 2 (document ID)
    │   └── ...
    │
    ├── pharmacies (collection)
    │   ├── 1 (document ID)
    │   │   ├── name: "Pharmacie Centrale" (string)
    │   │   ├── description: "..." (string)
    │   │   ├── address: "Avenue Kasa-Vubu, Gombe" (string)
    │   │   ├── city: "Kinshasa" (string)
    │   │   ├── latitude: -4.3276 (number)
    │   │   ├── longitude: 15.3136 (number)
    │   │   ├── pharmacist_id: "1" (string - référence vers users/1)
    │   │   ├── opening_hours: {...} (map)
    │   │   ├── services: [...] (array)
    │   │   ├── is_active: true (boolean)
    │   │   └── is_verified: true (boolean)
    │   └── ...
    │
    └── authorization_numbers (collection)
        ├── 1 (document ID)
        │   ├── number: "PH001234567" (string)
        │   ├── is_valid: true (boolean)
        │   ├── expires_at: "2026-01-01T00:00:00Z" (timestamp)
        │   └── pharmacist_name: "Dr. Paul Lumumba" (string)
        └── ...
```

#### Exemple de Document `users` (format JSON)

```json
{
  "id": "1",
  "name": "Dr. Jedidia Umba",
  "email": "jedidia.umba@geopharma.com",
  "password": "$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi",
  "role": "pharmacist",
  "authorization_number": "PH001234567",
  "profile_completed": true,
  "latitude": -4.3276,
  "longitude": 15.3136,
  "address": "Avenue Kasa-Vubu, Gombe",
  "city": "Kinshasa",
  "postal_code": "001",
  "email_verified_at": "2025-01-01T00:00:00Z",
  "created_at": "2025-01-01T00:00:00Z",
  "updated_at": "2025-01-01T00:00:00Z"
}
```

#### Exemple de Document `pharmacies` (format JSON)

```json
{
  "id": "1",
  "name": "Pharmacie Centrale",
  "description": "Pharmacie moderne au centre de Kinshasa",
  "address": "Avenue Kasa-Vubu, Gombe",
  "city": "Kinshasa",
  "postal_code": "001",
  "country": "RD Congo",
  "latitude": -4.3276,
  "longitude": 15.3136,
  "phone": "+243900000000",
  "email": "contact@pharmacie.cd",
  "whatsapp_number": "+243900000000",
  "opening_hours": {
    "monday": {
      "morning": {"start": "08:00", "end": "12:00"},
      "afternoon": {"start": "14:00", "end": "18:00"}
    },
    "tuesday": {
      "morning": {"start": "08:00", "end": "12:00"},
      "afternoon": {"start": "14:00", "end": "18:00"}
    },
    "wednesday": "closed"
  },
  "services": ["consultation", "vaccination", "livraison"],
  "is_active": true,
  "is_verified": true,
  "pharmacist_id": "1",
  "created_at": "2025-01-01T00:00:00Z",
  "updated_at": "2025-01-01T00:00:00Z"
}
```

**Note** : Dans Firebase Console, les données sont affichées de manière hiérarchique. Chaque document a un ID unique (généralement l'ID MySQL original) et contient des champs avec leurs types.

#### Exemple de Document `authorization_numbers` (format JSON)

```json
{
  "id": "1",
  "number": "PH001234567",
  "is_valid": true,
  "expires_at": "2026-01-01T00:00:00Z",
  "pharmacist_name": "Dr. Jedidia Umba",
  "pharmacy_name": "Pharmacie Centrale",
  "created_at": "2025-01-01T00:00:00Z",
  "updated_at": "2025-01-01T00:00:00Z"
}
```

### Visualisation dans Firebase Console

Quand vous ouvrez Firebase Console → Firestore Database, vous verrez :

1. **Liste des collections** à gauche : `users`, `pharmacies`, `authorization_numbers`
2. **Documents** dans chaque collection avec leur ID
3. **Champs** de chaque document avec leurs valeurs et types
4. **Possibilité d'éditer** directement dans la console (pour le développement)

**Exemple visuel de la structure** :
```
📁 users
  📄 1
    name: "Dr. Jedidia Umba"
    email: "jedidia.umba@geopharma.com"
    role: "pharmacist"
    ...
  📄 2
    ...

📁 pharmacies
  📄 1
    name: "Pharmacie Centrale"
    pharmacist_id: "1"  ← Référence vers users/1
    ...
```

### Différences avec MySQL

| MySQL | Firebase Firestore |
|-------|-------------------|
| Tables | Collections |
| Lignes | Documents |
| Colonnes | Champs |
| Relations (JOIN) | Références manuelles |
| Requêtes SQL | Requêtes via API REST |
| Transactions | Opérations atomiques |

---

## Comment Fonctionne la Connexion à Firebase

### 1. Le Service Firebase (`FirebaseService`)

Le service `app/Services/FirebaseService.php` gère toute la communication avec Firebase :

```php
class FirebaseService
{
    protected $projectId;
    protected $credentials;
    protected $accessToken;
    protected $baseUrl;

    public function __construct()
    {
        // 1. Charger les credentials depuis le fichier JSON
        $credentialsPath = config('firebase.credentials.file');
        $this->credentials = json_decode(file_get_contents($credentialsPath), true);
        
        // 2. Obtenir un token d'accès OAuth2
        $this->refreshAccessToken();
        
        // 3. Construire l'URL de base pour l'API Firestore REST
        $this->baseUrl = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents";
    }
}
```

### 2. Authentification OAuth2

Le service utilise **Google Auth** pour obtenir un token d'accès OAuth2 :

```php
protected function refreshAccessToken()
{
    // Utilise les credentials du service account
    $credentials = new ServiceAccountCredentials(
        ['https://www.googleapis.com/auth/cloud-platform'],
        $this->credentials
    );
    
    // Obtient un token d'accès valide pour 1 heure
    // Le token est utilisé dans l'en-tête Authorization: Bearer {token}
    $token = $credentials->fetchAuthToken();
    $this->accessToken = $token['access_token'];
}
```

**Comment ça fonctionne** :
1. Le service account (fichier JSON) contient une clé privée
2. Cette clé est utilisée pour signer une requête JWT
3. Google valide la signature et retourne un token d'accès
4. Ce token est utilisé pour toutes les requêtes à l'API Firestore
5. Le token expire après 1 heure et est automatiquement renouvelé

### 3. Communication via API REST

Toutes les opérations utilisent l'**API REST HTTP** de Firestore :

- **Créer** : `POST https://firestore.googleapis.com/v1/projects/{project}/databases/(default)/documents/{collection}`
- **Lire** : `GET https://firestore.googleapis.com/v1/projects/{project}/databases/(default)/documents/{collection}/{documentId}`
- **Mettre à jour** : `PATCH https://firestore.googleapis.com/v1/projects/{project}/databases/(default)/documents/{collection}/{documentId}`
- **Supprimer** : `DELETE https://firestore.googleapis.com/v1/projects/{project}/databases/(default)/documents/{collection}/{documentId}`

### 4. Conversion des Types de Données

Firestore utilise un format spécial pour les valeurs. Le service convertit automatiquement :

**Exemple de conversion PHP → Firestore** :

```php
// Données PHP
[
    'name' => 'Jovie',
    'age' => 30,
    'active' => true,
    'coordinates' => ['lat' => -4.3276, 'lng' => 15.3136],
    'tags' => ['pharmacy', 'medical']
]

// Devient (format Firestore)
{
    "fields": {
        "name": {"stringValue": "Jovie"},
        "age": {"integerValue": "30"},
        "active": {"booleanValue": true},
        "coordinates": {
            "mapValue": {
                "fields": {
                    "lat": {"doubleValue": -4.3276},
                    "lng": {"doubleValue": 15.3136}
                }
            }
        },
        "tags": {
            "arrayValue": {
                "values": [
                    {"stringValue": "pharmacy"},
                    {"stringValue": "medical"}
                ]
            }
        }
    }
}
```

**Types supportés** :
- `string` → `stringValue`
- `integer` → `integerValue`
- `float` → `doubleValue`
- `boolean` → `booleanValue`
- `array` → `arrayValue` ou `mapValue`
- `null` → `nullValue`
- `DateTime` → `timestampValue`

---

## Utilisation dans le Code

### 1. Les Modèles Firebase

Tous les modèles héritent de `FirebaseModel` qui encapsule `FirebaseService` :

```php
// app/Models/FirebaseUser.php
class FirebaseUser extends FirebaseModel implements Authenticatable
{
    protected $collection = 'users';
    // ...
}

// app/Models/FirebasePharmacy.php
class FirebasePharmacy extends FirebaseModel
{
    protected $collection = 'pharmacies';
    // ...
}
```

### 2. Opérations CRUD

#### Créer un Document

```php
// Ancienne méthode (MySQL/Eloquent)
$user = User::create(['name' => 'Jovie', 'email' => 'jovie@example.com']);

// Nouvelle méthode (Firebase)
$user = new FirebaseUser(['name' => 'Jovie', 'email' => 'jovie@example.com']);
$user->save();
```

#### Lire un Document

```php
// Trouver par ID
$user = FirebaseUser::find(1);

// Trouver par email
$user = FirebaseUser::whereEmail('jovie@example.com');

// Récupérer tous les utilisateurs
$users = FirebaseUser::all();
```

#### Mettre à Jour un Document

```php
// Ancienne méthode
$user->update(['name' => 'Schola']);

// Nouvelle méthode
$user->fill(['name' => 'Schola']);
$user->save();
```

#### Supprimer un Document

```php
// Même méthode
$user->delete();
```

### 3. Recherches et Filtres

```php
// Rechercher par champ
$pharmacies = FirebasePharmacy::all()
    ->where('is_active', true)
    ->where('is_verified', true);

// Recherche par texte (côté client)
$pharmacies = FirebasePharmacy::all()
    ->filter(function ($pharmacy) {
        return stripos($pharmacy->name, 'centrale') !== false;
    });

// Recherche par proximité géographique
$pharmacies = FirebasePharmacy::scopeNearby(null, -4.3276, 15.3136, 10);
```

### 4. Relations

Les relations Eloquent sont remplacées par des requêtes manuelles :

```php
// Dans FirebaseUser
public function pharmacies()
{
    if (!$this->isPharmacist()) {
        return collect([]);
    }
    
    $pharmacyModel = new FirebasePharmacy();
    $results = $pharmacyModel->where('pharmacist_id', '=', $this->getKey());
    return $results;
}

// Utilisation
$pharmacist = FirebaseUser::find(1);
$pharmacies = $pharmacist->pharmacies();
```

### 5. Authentification

L'authentification Laravel utilise maintenant Firebase :

```php
// Le FirebaseUserProvider récupère les utilisateurs depuis Firestore
class FirebaseUserProvider implements UserProvider
{
    public function retrieveByCredentials(array $credentials)
    {
        // Recherche l'utilisateur par email dans Firestore
        return FirebaseUser::whereEmail($credentials['email']);
    }
    
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        // Vérifie le mot de passe avec Hash::check()
        return Hash::check($credentials['password'], $user->getAuthPassword());
    }
}
```

---

## Migration des Données

### Étape 1 : Préparer la Migration

Assurez-vous que :
- ✅ Firebase est configuré (voir sections précédentes)
- ✅ Le fichier `firebase.credentials.json` est présent
- ✅ Les règles Firestore sont configurées

### Étape 2 : Tester la Migration (Dry-Run)

```bash
# Tester sans écrire dans Firebase
php artisan firebase:migrate --dry-run
```

Cette commande :
- ✅ Vérifie la connexion à Firebase
- ✅ Lit toutes les données depuis MySQL
- ✅ Simule la migration (sans écrire)
- ✅ Affiche les statistiques

### Étape 3 : Migrer les Données

```bash
# Migrer toutes les collections
php artisan firebase:migrate

# Migrer une collection spécifique
php artisan firebase:migrate --collection=users
php artisan firebase:migrate --collection=pharmacies
php artisan firebase:migrate --collection=authorization_numbers
```

### Étape 4 : Vérifier dans Firebase Console

1. Allez dans **Firestore Database**
2. Vérifiez que les collections `users`, `pharmacies`, et `authorization_numbers` existent
3. Cliquez sur chaque collection pour voir les documents
4. Vérifiez que les données sont correctes

### Structure de la Migration

La commande `php artisan firebase:migrate` :

1. **Lit depuis MySQL** : Utilise les modèles Eloquent existants
2. **Convertit les données** : Adapte les types pour Firestore
3. **Écrit dans Firebase** : Utilise `FirebaseService` pour créer les documents
4. **Préserve les IDs** : Les IDs MySQL sont utilisés comme IDs Firestore

**Exemple de conversion** :

```php
// Données MySQL
$user = User::find(1);
// $user->id = 1
// $user->created_at = "2025-01-01 00:00:00"

// Données Firestore
$firebaseData = [
    'id' => '1',  // ID préservé
    'name' => $user->name,
    'email' => $user->email,
    'created_at' => $user->created_at->toIso8601String(),  // Format ISO 8601
    // ...
];
```

---

## Dépannage

### Problème : "Fichier de credentials introuvable"

**Solution** :
1. Vérifiez que `storage/app/firebase.credentials.json` existe
2. Vérifiez les permissions du fichier
3. Vérifiez que le chemin dans `config/firebase.php` est correct

### Problème : "Permission denied" lors de la migration

**Solution** :
1. Vérifiez les règles Firestore dans Firebase Console
2. Pour le développement, utilisez des règles permissives :
   ```javascript
   allow read, write: if true;
   ```
3. Vérifiez que le service account a les bonnes permissions

### Problème : "Token d'accès invalide"

**Solution** :
1. Vérifiez que les credentials sont valides
2. Vérifiez que l'API Firestore est activée dans Google Cloud Console
3. Vérifiez que le projet ID est correct dans `config/firebase.php`

### Problème : Les données ne s'affichent pas

**Solution** :
1. Vérifiez que la migration a bien été exécutée
2. Vérifiez dans Firebase Console que les documents existent
3. Vérifiez les logs Laravel : `storage/logs/laravel.log`

### Problème : L'authentification ne fonctionne pas

**Solution** :
1. Vérifiez que `config/auth.php` utilise le driver `firebase`
2. Vérifiez que `FirebaseUserProvider` est enregistré dans `AuthServiceProvider`
3. Vérifiez que les utilisateurs existent dans Firestore
4. Vérifiez que les mots de passe sont correctement hashés

---

## Fichiers Clés du Projet

### Services et Modèles

- `app/Services/FirebaseService.php` : Service principal pour communiquer avec Firebase
- `app/Models/FirebaseModel.php` : Classe de base pour tous les modèles Firebase
- `app/Models/FirebaseUser.php` : Modèle utilisateur
- `app/Models/FirebasePharmacy.php` : Modèle pharmacie
- `app/Models/FirebaseAuthorizationNumber.php` : Modèle numéro d'autorisation

### Authentification

- `app/Providers/FirebaseUserProvider.php` : Provider d'authentification personnalisé
- `app/Providers/AuthServiceProvider.php` : Enregistre le provider Firebase
- `config/auth.php` : Configuration de l'authentification

### Configuration

- `config/firebase.php` : Configuration Firebase
- `storage/app/firebase.credentials.json` : Credentials Firebase (ne pas commiter)

### Migration

- `app/Console/Commands/MigrateToFirebase.php` : Commande de migration

---

## Résumé du Flux de Données

```
┌─────────────────┐
│  Laravel App    │
│  (Contrôleurs)  │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│  FirebaseModel  │
│  (User, Pharmacy)│
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│ FirebaseService │
│  (API REST)     │
└────────┬────────┘
         │
         │ HTTP + OAuth2 Token
         ▼
┌─────────────────┐
│  Firebase API   │
│  (Firestore)    │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│  Firestore DB   │
│  (Collections)  │
└─────────────────┘
```

---

## Checklist de Démarrage

Pour une nouvelle installation du projet :

- [ ] Créer un projet Firebase dans Firebase Console
- [ ] Télécharger les credentials et les placer dans `storage/app/firebase.credentials.json`
- [ ] Activer Firestore Database
- [ ] Configurer les règles de sécurité Firestore
- [ ] Installer les packages : `composer install`
- [ ] Vérifier la configuration dans `config/firebase.php`
- [ ] Tester la connexion : `php artisan firebase:migrate --dry-run`
- [ ] Migrer les données : `php artisan firebase:migrate`
- [ ] Vérifier dans Firebase Console que les données sont présentes
- [ ] Tester l'application (login, CRUD, etc.)

---

## Ressources

- [Documentation Firebase Firestore](https://firebase.google.com/docs/firestore)
- [API REST Firestore](https://firebase.google.com/docs/firestore/reference/rest)
- [Package kreait/firebase-php](https://github.com/kreait/firebase-php)

---

---

## 📖 Résumé Rapide pour Démarrage

### Pour une Nouvelle Installation

1. **Créer le projet Firebase** (section "Création du Projet Firebase")
2. **Télécharger les credentials** → `storage/app/firebase.credentials.json`
3. **Activer Firestore** (section "Configuration Firebase dans Firebase Console")
4. **Configurer les règles** (section "Configurer les Règles de Sécurité")
5. **Installer les packages** : `composer install`
6. **Migrer les données** : `php artisan firebase:migrate`

### Pour Comprendre le Fonctionnement

- **Structure des données** : Section "Architecture et Structure des Données"
- **Connexion à Firebase** : Section "Comment Fonctionne la Connexion à Firebase"
- **Utilisation dans le code** : Section "Utilisation dans le Code"
- **Migration** : Section "Migration des Données"

### Fichiers Clés à Connaître

- `app/Services/FirebaseService.php` : Service principal de communication
- `app/Models/FirebaseModel.php` : Classe de base pour tous les modèles
- `app/Models/FirebaseUser.php` : Modèle utilisateur
- `app/Models/FirebasePharmacy.php` : Modèle pharmacie
- `config/firebase.php` : Configuration Firebase
- `storage/app/firebase.credentials.json` : Credentials (ne pas commiter)

---

**Version Laravel** : 10.49.1  
**Version PHP** : 8.1+  
**Projet Firebase** : geopharma-b25da  
**Date** : 2025

