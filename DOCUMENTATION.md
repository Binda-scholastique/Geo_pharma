# Documentation Complète - GeoPharma

## Table des Matières

1. [Vue d'ensemble](#vue-densemble)
2. [Architecture de l'Application](#architecture-de-lapplication)
3. [Structure des Dossiers](#structure-des-dossiers)
4. [Détails des Fichiers Importants](#détails-des-fichiers-importants)
5. [Flux de Données](#flux-de-données)
6. [Système d'Authentification](#système-dauthentification)
7. [API et Routes](#api-et-routes)
8. [Base de Données](#base-de-données)
9. [Frontend et Interfaces](#frontend-et-interfaces)
10. [Services et Logique Métier](#services-et-logique-métier)

---

## Vue d'ensemble

**GeoPharma** est une application web Laravel permettant la géolocalisation et la gestion des pharmacies. L'application supporte trois types d'utilisateurs :
- **Utilisateurs** : Consultation des pharmacies, recherche par géolocalisation
- **Pharmaciens** : Gestion de leurs pharmacies, complétion de profil
- **Administrateurs** : Gestion complète de la plateforme (utilisateurs, pharmacies, autorisations)

---

## Architecture de l'Application

L'application suit le pattern **MVC (Model-View-Controller)** de Laravel :

```
Requête HTTP → Routes → Middleware → Contrôleur → Modèle → Vue → Réponse
```

### Technologies principales
- **Backend** : Laravel 10.49.1
- **Frontend** : Blade Templates, Bootstrap 5, Tailwind CSS
- **JavaScript** : Vanilla JS, Leaflet.js (cartes)
- **Base de données** : Firebase Firestore (NoSQL)
- **Authentification** : Laravel UI + Sanctum (avec Firebase User Provider)
- **PHP** : ^8.1 (requis pour Laravel 10)
- **Firebase** : Firestore pour le stockage des données en temps réel

---

## Structure des Dossiers

### 📁 `/app` - Logique Métier de l'Application

#### `/app/Http/Controllers` - Contrôleurs MVC

Les contrôleurs gèrent la logique HTTP de l'application. Ils reçoivent les requêtes, traitent les données et retournent les réponses.

##### `AdminController.php`
**Rôle** : Gestion complète du panneau d'administration
- Dashboard avec statistiques
- CRUD utilisateurs (Create, Read, Update, Delete)
- CRUD pharmacies (création, modification, validation, activation/désactivation)
- Gestion des numéros d'autorisation
- Paramètres système
- Redirection automatique vers le dashboard admin à l'accueil

**Routes associées** : `/admin/*`

##### `PharmacyController.php`
**Rôle** : Gestion publique des pharmacies
- Affichage de la carte avec toutes les pharmacies
- Recherche par ville
- Recherche par nom de pharmacie
- Recherche par proximité géographique
- Affichage des détails d'une pharmacie
- API pour la carte interactive
- Messages pré-définis pour contact (WhatsApp/Email)

**Routes associées** : `/`, `/pharmacies/*`

##### `PharmacistController.php`
**Rôle** : Espace dédié aux pharmaciens
- Dashboard pharmacien avec statistiques personnelles
- CRUD des pharmacies (création, modification de leurs pharmacies)
- Gestion des horaires d'ouverture (mode simple et séparé)
- Gestion du profil et complétion
- Gestion de la localisation GPS avec carte interactive
- Paramètres personnels

**Routes associées** : `/pharmacist/*`

##### `UserController.php`
**Rôle** : Gestion des profils utilisateurs standards
- Affichage et modification du profil
- Changement de mot de passe
- Paramètres personnels

**Routes associées** : `/user/*`

##### `/app/Http/Controllers/Auth` - Authentification

- `RegisterController.php` : Inscription avec gestion des rôles (utilisateur/pharmacien)
- `LoginController.php` : Connexion utilisateur
- `ForgotPasswordController.php` : Récupération de mot de passe
- `ResetPasswordController.php` : Réinitialisation de mot de passe
- `VerificationController.php` : Vérification d'email
- `ConfirmPasswordController.php` : Confirmation de mot de passe

##### `/app/Http/Controllers/Api/PharmacyApiController.php`
**Rôle** : API REST pour les pharmacies
- Endpoints JSON pour l'intégration frontend
- Recherche par proximité
- Recherche par ville
- Liste pour affichage carte

#### `/app/Models` - Modèles Firebase

Les modèles représentent les entités stockées dans Firebase Firestore et gèrent les opérations CRUD.

##### `FirebaseModel.php` (Classe de base)
**Rôle** : Classe abstraite de base pour tous les modèles Firebase
**Fonctionnalités** :
- Encapsule `FirebaseService` pour communiquer avec Firestore
- Méthodes CRUD : `find()`, `all()`, `create()`, `update()`, `delete()`
- Gestion des attributs et casts
- Pagination manuelle
- Relations manuelles (pas de relations Eloquent)

##### `FirebaseUser.php`
**Collection** : `users`
**Rôle** : Représente tous les utilisateurs (admin, pharmacien, utilisateur)
**Hérite de** : `FirebaseModel` et implémente `Authenticatable`
**Attributs clés** :
- `role` : Type d'utilisateur (admin/pharmacist/user)
- `authorization_number` : Numéro d'autorisation pour pharmacien
- `profile_completed` : Statut de complétion du profil
- `latitude`, `longitude` : Coordonnées GPS
- `address`, `city`, `postal_code` : Adresse

**Relations** :
- `pharmacies()` : Retourne les pharmacies du pharmacien (relation manuelle via Firestore)

**Méthodes importantes** :
- `isAdmin()`, `isPharmacist()`, `isUser()` : Vérification du rôle
- `whereEmail($email)` : Recherche par email

**Note** : Utilisé par `FirebaseUserProvider` pour l'authentification Laravel.

##### `FirebasePharmacy.php`
**Collection** : `pharmacies`
**Rôle** : Représente une pharmacie
**Hérite de** : `FirebaseModel`
**Attributs clés** :
- `name`, `description` : Informations de base
- `address`, `city`, `postal_code`, `country` : Localisation
- `latitude`, `longitude` : Coordonnées GPS précises
- `phone`, `email`, `whatsapp_number` : Contacts
- `opening_hours` : Horaires (JSON)
- `services` : Services proposés (JSON)
- `is_active` : Statut d'activation
- `is_verified` : Statut de vérification par admin
- `pharmacist_id` : Référence au pharmacien propriétaire (ID Firestore)

**Relations** :
- `pharmacist()` : Retourne le pharmacien propriétaire (relation manuelle)

**Scopes** :
- `scopeActive()` : Pharmacies actives uniquement (filtre sur collection)
- `scopeVerified()` : Pharmacies vérifiées uniquement (filtre sur collection)
- `scopeNearby()` : Recherche par proximité géographique (formule Haversine calculée côté client)

##### `FirebaseAuthorizationNumber.php`
**Collection** : `authorization_numbers`
**Rôle** : Gère les numéros d'autorisation valides pour les pharmaciens
**Hérite de** : `FirebaseModel`
**Attributs clés** :
- `number` : Numéro d'autorisation
- `pharmacist_name` : Nom du pharmacien autorisé
- `is_valid` : Statut de validité
- `expires_at` : Date d'expiration

**Scopes** :
- `scopeValid()` : Numéros valides uniquement
- `scopeNotExpired()` : Numéros non expirés

**Note** : Pour plus de détails sur l'utilisation de Firebase, consultez **[FIREBASE_COMPLETE_GUIDE.md](FIREBASE_COMPLETE_GUIDE.md)**.

#### `/app/Http/Middleware` - Middlewares

Les middlewares interceptent les requêtes HTTP pour exécuter du code avant/après le traitement.

##### `AdminMiddleware.php`
**Rôle** : Vérifie que l'utilisateur est administrateur
- Redirige les non-admins vers l'accueil
- Utilisé pour protéger les routes `/admin/*`

##### `Authenticate.php`
**Rôle** : Vérifie l'authentification
- Redirige vers la page de connexion si non connecté

##### `VerifyCsrfToken.php`
**Rôle** : Protection CSRF (Cross-Site Request Forgery)
- Valide les tokens CSRF pour les formulaires POST

##### Autres middlewares standards Laravel :
- `EncryptCookies.php` : Chiffrement des cookies
- `RedirectIfAuthenticated.php` : Redirection si déjà connecté
- `TrimStrings.php` : Nettoyage des chaînes
- `TrustProxies.php` : Gestion des proxies
- `PreventRequestsDuringMaintenance.php` : Mode maintenance

#### `/app/Services` - Services Métier

##### `FirebaseService.php`
**Rôle** : Service principal pour communiquer avec Firebase Firestore via l'API REST
**Fonctionnalités** :
- Authentification OAuth2 avec Google Auth
- Conversion automatique des types PHP ↔ Firestore
- Méthodes CRUD : `create()`, `read()`, `update()`, `delete()`
- Méthodes de requête : `getAll()`, `where()`
- Gestion automatique des tokens d'accès (renouvellement)
- Communication via API REST HTTP (pas besoin d'extension gRPC)

**Méthodes principales** :
- `create($collection, $data, $id = null)` : Créer un document
- `read($collection, $id)` : Lire un document
- `update($collection, $id, $data)` : Mettre à jour un document
- `delete($collection, $id)` : Supprimer un document
- `getAll($collection)` : Récupérer tous les documents
- `where($collection, $field, $operator, $value)` : Requête avec filtre

**Configuration** : `config/firebase.php`

##### `AuthorizationService.php`
**Rôle** : Service de validation des numéros d'autorisation
**Méthodes** :
- `validate($number)` : Valide un numéro d'autorisation
- Simule une API externe pour la vérification
- Pour le développement : accepte les numéros commençant par "PH"

#### `/app/Providers` - Service Providers

Les providers enregistrent des services dans le conteneur d'injection de dépendances Laravel.

- `AppServiceProvider.php` : Configuration générale de l'application
- `AuthServiceProvider.php` : Politiques d'autorisation + Enregistrement du `FirebaseUserProvider`
- `RouteServiceProvider.php` : Configuration des routes
- `BroadcastServiceProvider.php` : Broadcasting en temps réel
- `EventServiceProvider.php` : Gestion des événements

##### `FirebaseUserProvider.php`
**Rôle** : Provider d'authentification personnalisé pour Firebase
**Fonctionnalités** :
- Implémente `UserProviderContract` de Laravel
- Récupère les utilisateurs depuis Firestore
- Valide les credentials (email/password)
- Utilisé par `config/auth.php` avec le driver `firebase`

#### `/app/Console` - Commandes Artisan

##### `Kernel.php`
**Rôle** : Définit les commandes Artisan personnalisées et les tâches planifiées (schedulers)

#### `/app/Exceptions` - Gestion des Erreurs

##### `Handler.php`
**Rôle** : Gestion centralisée des exceptions
- Capture et traite les erreurs
- Logs personnalisés
- Pages d'erreur personnalisées

---

### 📁 `/bootstrap` - Initialisation de l'Application

#### `app.php`
**Rôle** : Point d'entrée de l'application Laravel
- Charge les services providers
- Configure le conteneur d'injection de dépendances
- Initialise le kernel HTTP

---

### 📁 `/config` - Fichiers de Configuration

Tous les fichiers de configuration de l'application Laravel.

#### Fichiers principaux :

- `app.php` : Configuration générale (nom, timezone, locale, debug)
- `auth.php` : Configuration de l'authentification (guards, providers) - **Utilise le driver `firebase`**
- `firebase.php` : Configuration Firebase (credentials, project_id, collections)
- `database.php` : Configuration des bases de données (MySQL, SQLite) - **Non utilisé pour Firestore**
- `mail.php` : Configuration de l'envoi d'emails
- `session.php` : Configuration des sessions
- `cache.php` : Configuration du cache
- `filesystems.php` : Configuration du stockage de fichiers
- `sanctum.php` : Configuration de Laravel Sanctum (API tokens)
- `cors.php` : Configuration CORS pour l'API
- `queue.php` : Configuration des files d'attente
- `services.php` : Configuration des services externes (API, OAuth)
- `broadcasting.php` : Configuration du broadcasting
- `hashing.php` : Configuration du hachage des mots de passe
- `logging.php` : Configuration des logs
- `view.php` : Configuration des vues

---

### 📁 `/database` - Base de Données

**⚠️ IMPORTANT** : L'application utilise maintenant **Firebase Firestore** comme base de données principale. Les migrations MySQL sont conservées pour référence historique mais ne sont plus utilisées.

Pour migrer les données vers Firebase, consultez **[FIREBASE_COMPLETE_GUIDE.md](FIREBASE_COMPLETE_GUIDE.md)**.

#### Structure Firebase Firestore

Les données sont organisées en **collections** et **documents** :

- **Collection `users`** : Tous les utilisateurs (admin, pharmacien, utilisateur)
- **Collection `pharmacies`** : Toutes les pharmacies
- **Collection `authorization_numbers`** : Numéros d'autorisation

Chaque document a un ID unique et contient des champs avec leurs types (string, number, boolean, timestamp, map, array).

#### `/database/migrations` - Migrations de Schéma (Historique)

**Note** : Ces migrations ne sont plus exécutées. Elles sont conservées pour référence.

Les migrations définissaient la structure MySQL (maintenant remplacée par Firestore).

#### `/database/seeders` - Seeders (Données de Test)

**Note** : Les seeders peuvent être adaptés pour créer des données dans Firebase.

##### `DatabaseSeeder.php`
**Rôle** : Seeder principal qui appelle tous les autres seeders

##### `AdminSeeder.php`
**Rôle** : Crée un compte administrateur par défaut
- Email : admin@geopharma.com
- Mot de passe : password

##### `PharmacySeeder.php`
**Rôle** : Crée des pharmacies de test avec géolocalisation (données de Kinshasa, RDC)

##### `TestPharmaciesSeeder.php`
**Rôle** : Crée des pharmacies supplémentaires pour les tests

#### Migration des Données

Pour migrer les données existantes de MySQL vers Firebase :

```bash
php artisan firebase:migrate --dry-run  # Test d'abord
php artisan firebase:migrate            # Migration réelle
```

Voir **[FIREBASE_COMPLETE_GUIDE.md](FIREBASE_COMPLETE_GUIDE.md)** pour les détails.

---

### 📁 `/resources` - Ressources Frontend

#### `/resources/views` - Vues Blade

Les vues Blade génèrent le HTML retourné aux utilisateurs.

##### `/resources/views/layouts`

###### `app.blade.php`
**Rôle** : Layout principal de l'application
- Structure HTML de base (DOCTYPE, head, body)
- Navigation bar (navbar) avec menu utilisateur
- Footer
- Inclusion des CSS/JS communs (Bootstrap, Tailwind, Leaflet, Font Awesome)
- Sections : @yield('content'), @stack('styles'), @stack('scripts')
- Flash messages (success, error)
- Design moderne avec gradients verts, responsive

##### `/resources/views/admin` - Vues Administration

###### `dashboard.blade.php`
**Rôle** : Dashboard administrateur
- Statistiques : total utilisateurs, pharmaciens, pharmacies, en attente
- Actions rapides : liens vers gestion utilisateurs, pharmacies, autorisations
- Activités récentes : dernières pharmacies et utilisateurs créés
- Design avec cartes statistiques colorées, gradients

###### `/admin/users`
- `index.blade.php` : Liste tous les utilisateurs avec filtres
- `create.blade.php` : Formulaire de création d'utilisateur
- `edit.blade.php` : Formulaire de modification d'utilisateur
- `show.blade.php` : Détails d'un utilisateur

###### `/admin/pharmacies`
- `index.blade.php` : Liste toutes les pharmacies avec filtres avancés
- `edit.blade.php` : Modification d'une pharmacie
- `show.blade.php` : Détails d'une pharmacie

###### `/admin/authorization-numbers`
- `index.blade.php` : Liste des numéros d'autorisation
- `create.blade.php` : Création d'un numéro d'autorisation
- `edit.blade.php` : Modification d'un numéro d'autorisation

###### `profile.blade.php` : Profil administrateur
###### `settings.blade.php` : Paramètres système

##### `/resources/views/pharmacist` - Vues Pharmacien

###### `dashboard.blade.php`
**Rôle** : Dashboard pharmacien
- Statistiques personnelles : mes pharmacies, actives, vérifiées, en attente
- Actions rapides : créer pharmacie, modifier profil, localisation, paramètres
- Liste de mes pharmacies
- Alerte si profil incomplet
- Informations du profil

###### `create-pharmacy.blade.php` : Formulaire de création de pharmacie
###### `complete-profile.blade.php` : Complétion du profil pharmacien
###### `profile.blade.php` : Profil pharmacien
###### `profile-location.blade.php` : Gestion de la localisation GPS
###### `settings.blade.php` : Paramètres personnels

##### `/resources/views/pharmacies` - Vues Publiques

###### `index.blade.php`
**Rôle** : Page d'accueil avec carte interactive
- Hero section avec recherche par ville
- Carte Leaflet avec tous les marqueurs de pharmacies
- Sidebar avec liste des pharmacies trouvées
- Statistiques rapides
- Scripts JavaScript pour géolocalisation et affichage carte

###### `search.blade.php` : Page de recherche avancée
###### `show.blade.php` : Détails publics d'une pharmacie

##### `/resources/views/user` - Vues Utilisateur

###### `profile.blade.php` : Profil utilisateur standard
###### `settings.blade.php` : Paramètres utilisateur

##### `/resources/views/auth` - Authentification

- `login.blade.php` : Formulaire de connexion
- `register.blade.php` : Formulaire d'inscription avec sélection de rôle
- `verify.blade.php` : Vérification d'email
- `/passwords/reset.blade.php` : Réinitialisation de mot de passe
- `/passwords/email.blade.php` : Demande de réinitialisation
- `/passwords/confirm.blade.php` : Confirmation de mot de passe

##### `/resources/views/welcome.blade.php`
**Rôle** : Page d'accueil publique alternative
- Présentation de l'application
- Liens vers inscription/connexion

##### `/resources/views/home.blade.php`
**Rôle** : Page d'accueil après connexion
- Dashboard simple pour utilisateurs connectés

#### `/resources/css` - Feuilles de Style

##### `app.css`
**Rôle** : CSS compilé principal (généré par Laravel Mix)

##### `custom.css`
**Rôle** : Styles personnalisés GeoPharma
- Variables CSS pour couleurs vertes (primary-green, secondary-green)
- Styles pour boutons, cartes, formulaires
- Animations (fadeInUp, loading)
- Styles pour badges, tooltips
- Scrollbars personnalisées
- Responsive design

#### `/resources/sass` - Sources SCSS

##### `app.scss`
**Rôle** : Source SCSS principal
- Import de Bootstrap
- Variables personnalisées

##### `_variables.scss`
**Rôle** : Variables SCSS personnalisées

#### `/resources/js` - JavaScript

##### `app.js`
**Rôle** : JavaScript principal
- Configuration Axios (CSRF token)
- Initialisations communes

##### `bootstrap.js`
**Rôle** : Configuration Bootstrap
- Import des composants Bootstrap nécessaires

#### `/resources/lang` - Traductions

##### `/lang/en`
- Fichiers de traduction en anglais pour validation, messages

---

### 📁 `/routes` - Définition des Routes

#### `web.php`
**Rôle** : Routes web principales
- Routes publiques : `/`, `/pharmacies/*`
- Routes authentifiées : `/pharmacist/*`, `/user/*`
- Routes admin : `/admin/*` (protégées par middleware `admin`)
- Routes d'authentification Laravel UI

**Routes clés** :
- `GET /` : Page d'accueil (carte)
- `GET /pharmacies` : Liste des pharmacies
- `GET /pharmacies/{id}` : Détails d'une pharmacie
- `POST /pharmacies/search-by-city` : Recherche par ville
- `GET /pharmacist/dashboard` : Dashboard pharmacien
- `GET /admin/dashboard` : Dashboard admin

#### `api.php`
**Rôle** : Routes API REST
- Endpoints JSON pour intégration frontend/mobile
- Préfixe `/api/pharmacies/*`
- Endpoints : index, map, nearby, search-by-city, search, show
- Route Sanctum : `/api/user` (utilisateur connecté)

#### `channels.php`
**Rôle** : Définit les canaux de broadcasting (WebSockets)

#### `console.php`
**Rôle** : Définit les commandes Artisan personnalisées

---

### 📁 `/public` - Fichiers Publics

Point d'entrée HTTP de l'application. Contient les fichiers accessibles publiquement.

#### `index.php`
**Rôle** : Point d'entrée HTTP principal
- Charge l'autoloader Composer
- Bootstrap l'application Laravel
- Traite la requête HTTP et retourne la réponse

#### `/css/app.css`
**Rôle** : CSS compilé (généré par `npm run dev`)

#### `/js/app.js`
**Rôle** : JavaScript compilé (généré par `npm run dev`)

#### `mix-manifest.json`
**Rôle** : Manifest Laravel Mix avec hash des assets pour cache busting

---

### 📁 `/storage` - Stockage des Fichiers

#### `/storage/app`
**Rôle** : Fichiers uploadés par les utilisateurs (si nécessaire)

#### `/storage/app/firebase.credentials.json`
**Rôle** : Credentials Firebase (Service Account JSON)
**⚠️ IMPORTANT** : Ne JAMAIS commiter ce fichier dans Git !
- Contient les clés privées pour accéder à Firebase
- Ajouté au `.gitignore`
- Téléchargé depuis Firebase Console → Paramètres du projet → Comptes de service

#### `/storage/framework`
- `/cache` : Cache de l'application
- `/sessions` : Fichiers de session
- `/views` : Vues compilées Blade
- `/testing` : Fichiers de test

#### `/storage/logs`
**Rôle** : Logs de l'application
- `laravel.log` : Log principal avec toutes les erreurs

---

### 📁 `/tests` - Tests Automatisés

#### `TestCase.php`
**Rôle** : Classe de base pour tous les tests

#### `/Feature`
**Rôle** : Tests d'intégration (feature tests)
- Testent des fonctionnalités complètes

#### `/Unit`
**Rôle** : Tests unitaires
- Testent des classes/méthodes individuelles

---

## Flux de Données

### 1. Recherche de Pharmacies par Géolocalisation

```
Utilisateur → Clic "Ma position" → JavaScript (getCurrentLocation)
→ API HTML5 Geolocation → Coordonnées GPS
→ POST /pharmacies/search → PharmacyController@search
→ FirebasePharmacy::scopeNearby() → Récupère toutes les pharmacies depuis Firestore
→ Calcul distance côté client (formule Haversine)
→ Filtre par rayon → Tri par distance
→ Retour JSON → JavaScript → Affichage sur carte Leaflet
```

**Note** : Avec Firestore, toutes les pharmacies sont récupérées puis filtrées côté client car Firestore ne supporte pas directement les requêtes géospatiales complexes.

### 2. Inscription d'un Pharmacien

```
GET /register → RegisterController@showRegistrationForm
→ Vue register.blade.php (sélection rôle)
→ POST /register → RegisterController@register
→ Validation → AuthorizationService::validate()
→ Création FirebaseUser avec role='pharmacist'
→ FirebaseService::create() → Écriture dans Firestore collection 'users'
→ Redirection dashboard
```

### 3. Création d'une Pharmacie par un Pharmacien

```
GET /pharmacist/pharmacy/create → PharmacistController@createPharmacy
→ Vue create-pharmacy.blade.php
→ POST /pharmacist/pharmacy/store → PharmacistController@storePharmacy
→ Validation → Création FirebasePharmacy avec pharmacist_id
→ FirebaseService::create() → Écriture dans Firestore collection 'pharmacies'
→ is_verified = false (nécessite validation admin)
→ Redirection dashboard
```

### 4. Validation d'une Pharmacie par Admin

```
GET /admin/pharmacies → AdminController@pharmacies
→ FirebasePharmacy::all() → Récupère toutes les pharmacies depuis Firestore
→ Filtrage et pagination côté client
→ Liste avec badge "En attente"
→ POST /admin/pharmacies/{id}/toggle-verification
→ AdminController@togglePharmacyVerification
→ FirebaseService::update() → Mise à jour dans Firestore
→ is_verified = true
→ Pharmacie visible publiquement
```

---

## Système d'Authentification

### Rôles et Permissions

1. **User (role='user')**
   - Consultation des pharmacies
   - Recherche par géolocalisation
   - Accès au profil personnel

2. **Pharmacist (role='pharmacist')**
   - Toutes les permissions User
   - Dashboard pharmacien
   - Création/gestion de pharmacies
   - Gestion du profil et localisation
   - Nécessite numéro d'autorisation valide

3. **Admin (role='admin')**
   - Toutes les permissions Pharmacist
   - Dashboard admin
   - Gestion complète des utilisateurs
   - Validation des pharmacies
   - Gestion des numéros d'autorisation
   - Paramètres système

### Middleware de Protection

- `auth` : Vérifie l'authentification
- `admin` : Vérifie le rôle administrateur
- `verified` : Vérifie l'email confirmé (optionnel)

---

## API et Routes

### Routes Web Principales

| Route | Méthode | Contrôleur | Description |
|-------|---------|------------|-------------|
| `/` | GET | PharmacyController@index | Page d'accueil (carte) |
| `/pharmacies` | GET | PharmacyController@index | Liste pharmacies |
| `/pharmacies/{id}` | GET | PharmacyController@show | Détails pharmacie |
| `/pharmacies/search-by-city` | POST | PharmacyController@searchByCity | Recherche par ville |
| `/pharmacist/dashboard` | GET | PharmacistController@dashboard | Dashboard pharmacien |
| `/admin/dashboard` | GET | AdminController@dashboard | Dashboard admin |

### Endpoints API

| Endpoint | Méthode | Description |
|----------|---------|-------------|
| `/api/pharmacies` | GET | Liste toutes les pharmacies (JSON) |
| `/api/pharmacies/map` | GET | Pharmacies pour carte (JSON) |
| `/api/pharmacies/nearby` | POST | Recherche par proximité (JSON) |
| `/api/pharmacies/search-by-city` | POST | Recherche par ville (JSON) |
| `/api/pharmacies/{id}` | GET | Détails d'une pharmacie (JSON) |

---

## Base de Données

**⚠️ IMPORTANT** : L'application utilise **Firebase Firestore** (NoSQL) comme base de données principale.

### Structure Firebase Firestore

Les données sont organisées en **collections** (équivalent des tables MySQL) et **documents** (équivalent des lignes).

#### Collection `users`
**Documents** : Chaque utilisateur est un document avec un ID unique
**Champs** :
- `id` : ID du document (string)
- `name`, `email`, `password` : Informations de base
- `role` : user/pharmacist/admin (string)
- `authorization_number` : Numéro d'autorisation (string, nullable)
- `profile_completed` : boolean
- `latitude`, `longitude` : number (nullable)
- `address`, `city`, `postal_code` : string (nullable)
- `email_verified_at` : timestamp (nullable)
- `created_at`, `updated_at` : timestamp

#### Collection `pharmacies`
**Documents** : Chaque pharmacie est un document avec un ID unique
**Champs** :
- `id` : ID du document (string)
- `name`, `description` : string
- `address`, `city`, `postal_code`, `country` : string
- `latitude`, `longitude` : number
- `phone`, `email`, `whatsapp_number` : string (nullable)
- `opening_hours` : map (JSON) - structure flexible
- `services` : array (JSON) - liste des services
- `is_active`, `is_verified` : boolean
- `pharmacist_id` : string (référence vers users/{id})
- `created_at`, `updated_at` : timestamp

#### Collection `authorization_numbers`
**Documents** : Chaque numéro d'autorisation est un document
**Champs** :
- `id` : ID du document (string)
- `number` : string (unique)
- `pharmacist_name` : string
- `is_valid` : boolean
- `expires_at` : timestamp (nullable)
- `created_at`, `updated_at` : timestamp

### Relations

Dans Firestore, les relations sont gérées manuellement via des références (IDs) :

- `FirebaseUser` (1) ↔ (N) `FirebasePharmacy` : Un pharmacien peut avoir plusieurs pharmacies
  - Relation via `pharmacist_id` dans le document pharmacy
  - Méthode `$user->pharmacies()` récupère manuellement depuis Firestore

- `FirebasePharmacy` (N) ↔ (1) `FirebaseUser` : Une pharmacie appartient à un pharmacien
  - Relation via `pharmacist_id`
  - Méthode `$pharmacy->pharmacist()` récupère manuellement depuis Firestore

**Note** : Pour plus de détails sur la structure et l'utilisation de Firebase, consultez **[FIREBASE_COMPLETE_GUIDE.md](FIREBASE_COMPLETE_GUIDE.md)**.

---

## Frontend et Interfaces

### Technologies Frontend

- **Blade Templates** : Moteur de templates Laravel
- **Bootstrap 5** : Framework CSS (grilles, composants)
- **Tailwind CSS** : Utility-first CSS (design moderne)
- **Font Awesome** : Icônes
- **Leaflet.js** : Bibliothèque de cartes interactives
- **Vanilla JavaScript** : Logique frontend (géolocalisation, cartes)

### Structure des Vues

Toutes les vues utilisent le layout principal `app.blade.php` qui fournit :
- Navigation bar responsive
- Footer
- Styles communs (CSS/JS)
- Flash messages
- Structure responsive

### Design System

- **Couleurs principales** : Vert (#10b981) pour thème médical
- **Typographie** : Inter (Google Fonts)
- **Composants** : Cartes, badges, boutons avec animations
- **Responsive** : Mobile-first avec breakpoints Bootstrap

---

## Services et Logique Métier

### AuthorizationService

**Fichier** : `app/Services/AuthorizationService.php`

**Rôle** : Valide les numéros d'autorisation des pharmaciens

**Méthode principale** :
```php
validate($number): bool
```

**Logique** :
- Pour développement : accepte les numéros commençant par "PH"
- Production : appellerait une API externe de vérification
- Vérifie aussi dans la table `authorization_numbers`

### Calcul de Distance (Haversine)

**Fichier** : `app/Models/FirebasePharmacy.php` - Scope `scopeNearby()`

**Formule** : Calcul de la distance entre deux points GPS (formule Haversine)
```php
$distance = 6371 * acos(
    cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
    cos(deg2rad($lon2) - deg2rad($lon1)) +
    sin(deg2rad($lat1)) * sin(deg2rad($lat2))
);
```

**Utilisation** : 
- Récupère toutes les pharmacies depuis Firestore
- Calcule la distance pour chaque pharmacie côté client
- Filtre par rayon (par défaut 10 km)
- Trie par distance croissante

**Note** : Firestore ne supporte pas directement les requêtes géospatiales complexes, donc le calcul est fait côté client après récupération de toutes les pharmacies.

---

## Commandes Utiles

### Développement

```bash
# Installer les dépendances
composer install
npm install

# Compiler les assets
npm run dev        # Développement
npm run production # Production

# Démarrer le serveur
php artisan serve

# Migrer les données vers Firebase (si vous avez des données MySQL)
php artisan firebase:migrate --dry-run  # Test d'abord
php artisan firebase:migrate            # Migration réelle

# Note : Les migrations MySQL ne sont plus utilisées
# L'application utilise Firebase Firestore comme base de données
```

### Génération de Code

```bash
# Créer un contrôleur
php artisan make:controller NomController

# Créer un modèle
php artisan make:model NomModel

# Créer une migration
php artisan make:migration nom_migration

# Créer un seeder
php artisan make:seeder NomSeeder
```

---

## Sécurité

### Mesures Implémentées

1. **CSRF Protection** : Tous les formulaires incluent des tokens CSRF
2. **Authentification** : Laravel UI avec hachage bcrypt des mots de passe + Firebase User Provider
3. **Middleware** : Protection des routes sensibles (admin)
4. **Validation** : Validation des données côté serveur
5. **Sanitization** : Échappement automatique dans Blade
6. **Firebase Security Rules** : Règles de sécurité Firestore configurées (voir FIREBASE_COMPLETE_GUIDE.md)
7. **OAuth2 Authentication** : Authentification sécurisée avec Google Auth pour l'accès à Firebase
8. **Credentials Protection** : Fichier `firebase.credentials.json` dans `.gitignore`

---

## Points d'Extension

### Ajouter une Nouvelle Fonctionnalité

1. **Créer le modèle Firebase** : Étendre `FirebaseModel` (ex: `FirebaseNomModel`)
   - Définir `protected $collection = 'nom_collection';`
   - Définir `$fillable`, `$casts`, etc.
2. **Créer le contrôleur** : `php artisan make:controller NomController`
   - Utiliser le modèle Firebase pour les opérations CRUD
3. **Ajouter les routes** : `routes/web.php` ou `routes/api.php`
4. **Créer les vues** : `resources/views/nom/`
5. **Tester** : Feature tests dans `tests/Feature/`

**Note** : Plus besoin de migrations MySQL. Les collections Firestore sont créées automatiquement lors de la première écriture.

### Personnaliser le Design

- Modifier `resources/css/custom.css` pour les styles globaux
- Modifier `resources/views/layouts/app.blade.php` pour la structure
- Utiliser les variables CSS dans `:root` pour les couleurs

---

## Nouvelles Fonctionnalités (Mises à Jour)

### Gestion des Horaires d'Ouverture

**Fichiers concernés** :
- `resources/views/pharmacist/create-pharmacy.blade.php`
- `resources/views/pharmacist/edit-pharmacy.blade.php`
- `app/Http/Controllers/PharmacistController.php`
- `app/Http/Controllers/AdminController.php`

**Fonctionnalités** :
- Formulaire interactif pour définir les horaires par jour de la semaine
- Mode simple : un créneau horaire par jour (ex: 08:00 - 18:00)
- Mode séparé : horaires distincts pour le matin et l'après-midi
- Possibilité de marquer un jour comme fermé
- Stockage en JSON dans la base de données
- Format : `{jour: {start: "HH:MM", end: "HH:MM"}}` ou `{jour: {morning: {start, end}, afternoon: {start, end}}}`

### Création de Pharmacie par l'Administrateur

**Fichiers concernés** :
- `app/Http/Controllers/AdminController.php` (méthodes `createPharmacy`, `storePharmacy`)
- `resources/views/admin/pharmacies/create.blade.php`
- `resources/views/admin/pharmacies/index.blade.php`

**Fonctionnalités** :
- L'admin peut créer des pharmacies directement
- Sélection du pharmacien propriétaire via dropdown
- Options administratives : vérification et activation directes
- Formulaire partagé avec les pharmaciens mais adapté pour l'admin

### Recherche Avancée

**Fichiers concernés** :
- `app/Http/Controllers/PharmacyController.php`
- `resources/views/pharmacies/search.blade.php`

**Fonctionnalités** :
- Recherche par nom de pharmacie
- Recherche par ville
- Recherche par proximité géographique
- Interface unifiée pour tous les types de recherche

### Messages Pré-définis pour Contact

**Fichiers concernés** :
- `app/Models/FirebasePharmacy.php` (méthodes `getPredefinedMessage`, `getWhatsappUrlAttribute`, `getEmailUrlAttribute`)

**Fonctionnalités** :
- Génération automatique de messages selon l'heure (Bonjour/Bonsoir)
- Inclusion du nom de l'utilisateur connecté
- Messages personnalisés pour WhatsApp et Email
- Format : "Bonjour/Bonsoir {nom_pharmacie}, je suis {nom_user} depuis l'application GeoPharma..."

### Améliorations de l'Interface

**Corrections apportées** :
- Boutons de retour : style amélioré avec fond blanc et texte vert pour meilleure visibilité
- Redirection admin : l'accueil redirige automatiquement vers le dashboard admin
- Design moderne : gradient headers, breadcrumbs, cartes modernisées

### Redirections par Rôle

**Fichiers concernés** :
- `app/Http/Controllers/HomeController.php`
- `app/Http/Controllers/Auth/LoginController.php`

**Comportement** :
- **Admin** : Accueil → Dashboard admin (`/admin/dashboard`)
- **Pharmacien** : Accueil → Dashboard pharmacien (`/pharmacist/dashboard`)
- **Utilisateur** : Accueil → Page des pharmacies (`/pharmacies`)

---

## Migration vers Laravel 10

### Changements Principaux

L'application a été migrée de Laravel 8 vers Laravel 10. Voici les principaux changements :

#### 1. **Support CORS Natif**
- **Avant** : Utilisation du package `fruitcake/laravel-cors`
- **Maintenant** : Support CORS natif intégré dans Laravel 10
- **Fichier modifié** : `app/Http/Kernel.php`
  - Remplacement de `\Fruitcake\Cors\HandleCors::class` par `\Illuminate\Http\Middleware\HandleCors::class`
- **Configuration** : Le fichier `config/cors.php` reste valide et fonctionne avec le middleware natif

#### 2. **RouteServiceProvider**
- Suppression des références au `namespace` (déprécié)
- Utilisation de la syntaxe nullsafe PHP 8.1+ : `$request->user()?->id` au lieu de `optional($request->user())->id`

#### 3. **Dépendances Mises à Jour**
- **PHP** : ^8.1 (requis pour Laravel 10)
- **Laravel Framework** : 10.49.1
- **Laravel Sanctum** : ^3.2
- **Laravel Tinker** : ^2.8
- **PHPUnit** : ^10.1 (pour les tests)
- **Collision** : ^7.0 (pour les erreurs CLI)
- **Laravel Ignition** : ^2.0 (pour le debugging)

#### 4. **Nouveaux Packages**
- **Laravel Prompts** : Nouveau package pour les interactions CLI améliorées

#### 5. **Packages Supprimés**
- `fruitcake/laravel-cors` : Remplacé par le support natif

### Compatibilité

- ✅ Toutes les routes fonctionnent correctement
- ✅ Tous les contrôleurs sont compatibles
- ✅ Les modèles Eloquent fonctionnent sans modification
- ✅ Les vues Blade sont compatibles
- ✅ L'authentification Laravel UI fonctionne

### Notes Importantes

- **PHP 8.1+ requis** : Laravel 10 nécessite PHP 8.1 ou supérieur
- **Tests** : Si vous avez des tests, ils peuvent nécessiter des ajustements pour PHPUnit 10
- **CORS** : La configuration CORS dans `config/cors.php` fonctionne avec le middleware natif

---

## Conclusion

Cette documentation couvre l'ensemble de l'architecture et des composants de **GeoPharma**. Chaque dossier et fichier a un rôle spécifique dans le fonctionnement de l'application. 

Pour toute question ou amélioration, consultez la documentation Laravel officielle : https://laravel.com/docs/10.x

Pour comprendre l'utilisation complète de Firebase dans ce projet, consultez **[FIREBASE_COMPLETE_GUIDE.md](FIREBASE_COMPLETE_GUIDE.md)**.

---

**Développé par Scholastique Binda, Joviette Kandolo et Jedidia Umba**

*Dernière mise à jour : 2025 - Laravel 10.49.1*


