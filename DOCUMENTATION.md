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
- **Backend** : Laravel 8.83.29
- **Frontend** : Blade Templates, Bootstrap 5, Tailwind CSS
- **JavaScript** : Vanilla JS, Leaflet.js (cartes)
- **Base de données** : MySQL/SQLite
- **Authentification** : Laravel UI + Sanctum
- **PHP** : ^7.3|^8.0

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

#### `/app/Models` - Modèles Eloquent

Les modèles représentent les entités de la base de données et gèrent les relations entre elles.

##### `User.php`
**Table** : `users`
**Rôle** : Représente tous les utilisateurs (admin, pharmacien, utilisateur)
**Attributs clés** :
- `role` : Type d'utilisateur (admin/pharmacist/user)
- `authorization_number` : Numéro d'autorisation pour pharmacien
- `profile_completed` : Statut de complétion du profil
- `latitude`, `longitude` : Coordonnées GPS
- `address`, `city`, `postal_code` : Adresse

**Relations** :
- `hasMany(Pharmacy::class)` : Un pharmacien peut avoir plusieurs pharmacies

**Méthodes importantes** :
- `isAdmin()`, `isPharmacist()`, `isUser()` : Vérification du rôle

##### `Pharmacy.php`
**Table** : `pharmacies`
**Rôle** : Représente une pharmacie
**Attributs clés** :
- `name`, `description` : Informations de base
- `address`, `city`, `postal_code`, `country` : Localisation
- `latitude`, `longitude` : Coordonnées GPS précises
- `phone`, `email`, `whatsapp_number` : Contacts
- `opening_hours` : Horaires (JSON)
- `services` : Services proposés (JSON)
- `is_active` : Statut d'activation
- `is_verified` : Statut de vérification par admin
- `pharmacist_id` : Référence au pharmacien propriétaire

**Relations** :
- `belongsTo(User::class)` : Appartient à un pharmacien

**Scopes** :
- `scopeActive()` : Pharmacies actives uniquement
- `scopeVerified()` : Pharmacies vérifiées uniquement
- `scopeNearby()` : Recherche par proximité géographique (formule Haversine)

##### `AuthorizationNumber.php`
**Table** : `authorization_numbers`
**Rôle** : Gère les numéros d'autorisation valides pour les pharmaciens
**Attributs clés** :
- `number` : Numéro d'autorisation
- `pharmacist_name` : Nom du pharmacien autorisé
- `is_active` : Statut d'activation
- `expires_at` : Date d'expiration

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

##### `AuthorizationService.php`
**Rôle** : Service de validation des numéros d'autorisation
**Méthodes** :
- `validate($number)` : Valide un numéro d'autorisation
- Simule une API externe pour la vérification
- Pour le développement : accepte les numéros commençant par "PH"

#### `/app/Providers` - Service Providers

Les providers enregistrent des services dans le conteneur d'injection de dépendances Laravel.

- `AppServiceProvider.php` : Configuration générale de l'application
- `AuthServiceProvider.php` : Politiques d'autorisation
- `RouteServiceProvider.php` : Configuration des routes
- `BroadcastServiceProvider.php` : Broadcasting en temps réel
- `EventServiceProvider.php` : Gestion des événements

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
- `auth.php` : Configuration de l'authentification (guards, providers)
- `database.php` : Configuration des bases de données (MySQL, SQLite)
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

#### `/database/migrations` - Migrations de Schéma

Les migrations définissent et modifient la structure de la base de données de manière versionnée.

##### `2014_10_12_000000_create_users_table.php`
**Rôle** : Crée la table `users` de base
- Champs standards : id, name, email, password, remember_token
- `email_verified_at` : Vérification d'email

##### `2014_10_12_021422_add_role_to_users_table.php`
**Rôle** : Ajoute le champ `role` aux utilisateurs
- Valeurs possibles : 'user', 'pharmacist', 'admin'

##### `2014_10_12_021549_create_pharmacies_table.php`
**Rôle** : Crée la table `pharmacies`
- Informations de base : nom, description, adresse
- Coordonnées GPS : latitude, longitude
- Contacts : phone, email, whatsapp_number
- Horaires d'ouverture : opening_hours (JSON) - supporte mode simple et séparé (matin/après-midi)
- Services : services (JSON) - liste des services proposés
- Statuts : is_active, is_verified
- Relation : pharmacist_id (clé étrangère vers users)

##### `2014_10_12_021624_create_authorization_numbers_table.php`
**Rôle** : Crée la table `authorization_numbers`
- Gère les numéros d'autorisation valides

##### `2019_12_14_000001_create_personal_access_tokens_table.php`
**Rôle** : Table pour Laravel Sanctum (tokens API)
- Authentification API sans état

##### `2025_09_24_211647_add_admin_role_to_users_table.php`
**Rôle** : Migration spécifique pour ajouter le rôle admin si nécessaire

##### `2025_09_25_123911_add_location_to_users_table.php`
**Rôle** : Ajoute les champs de localisation GPS aux utilisateurs
- latitude, longitude, address, city, postal_code

#### `/database/seeders` - Seeders (Données de Test)

##### `DatabaseSeeder.php`
**Rôle** : Seeder principal qui appelle tous les autres seeders

##### `AdminSeeder.php`
**Rôle** : Crée un compte administrateur par défaut
- Email : admin@geopharma.com
- Mot de passe : password

##### `PharmacySeeder.php`
**Rôle** : Crée des pharmacies de test avec géolocalisation

##### `TestPharmaciesSeeder.php`
**Rôle** : Crée des pharmacies supplémentaires pour les tests

#### `/database/factories` - Factories (Générateurs de Données)

##### `UserFactory.php`
**Rôle** : Génère des utilisateurs fictifs pour les tests
- Utilisé avec Faker pour créer des données réalistes

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
→ Pharmacy::scopeNearby() → Calcul distance (formule Haversine)
→ Retour JSON → JavaScript → Affichage sur carte Leaflet
```

### 2. Inscription d'un Pharmacien

```
GET /register → RegisterController@showRegistrationForm
→ Vue register.blade.php (sélection rôle)
→ POST /register → RegisterController@register
→ Validation → AuthorizationService::validate()
→ Création User avec role='pharmacist'
→ Redirection dashboard
```

### 3. Création d'une Pharmacie par un Pharmacien

```
GET /pharmacist/pharmacy/create → PharmacistController@createPharmacy
→ Vue create-pharmacy.blade.php
→ POST /pharmacist/pharmacy/store → PharmacistController@storePharmacy
→ Validation → Création Pharmacy avec pharmacist_id
→ is_verified = false (nécessite validation admin)
→ Redirection dashboard
```

### 4. Validation d'une Pharmacie par Admin

```
GET /admin/pharmacies → AdminController@pharmacies
→ Liste avec badge "En attente"
→ POST /admin/pharmacies/{id}/toggle-verification
→ AdminController@togglePharmacyVerification
→ Mise à jour is_verified = true
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

### Schéma des Tables

#### `users`
- `id` (PK)
- `name`, `email`, `password`
- `role` (user/pharmacist/admin)
- `authorization_number`
- `profile_completed` (boolean)
- `latitude`, `longitude`
- `address`, `city`, `postal_code`
- `email_verified_at`, `remember_token`
- `created_at`, `updated_at`

#### `pharmacies`
- `id` (PK)
- `name`, `description`
- `address`, `city`, `postal_code`, `country`
- `latitude`, `longitude`
- `phone`, `email`, `whatsapp_number`
- `opening_hours` (JSON)
- `services` (JSON)
- `is_active` (boolean)
- `is_verified` (boolean)
- `pharmacist_id` (FK → users.id)
- `created_at`, `updated_at`

#### `authorization_numbers`
- `id` (PK)
- `number` (unique)
- `pharmacist_name`
- `is_active` (boolean)
- `expires_at`
- `created_at`, `updated_at`

### Relations

- `User` (1) ↔ (N) `Pharmacy` : Un pharmacien peut avoir plusieurs pharmacies
- `Pharmacy` (N) ↔ (1) `User` : Une pharmacie appartient à un pharmacien

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

**Fichier** : `app/Models/Pharmacy.php` - Scope `scopeNearby()`

**Formule** : Calcul de la distance entre deux points GPS
```sql
6371 * acos(
    cos(radians(?)) * cos(radians(latitude)) *
    cos(radians(longitude) - radians(?)) +
    sin(radians(?)) * sin(radians(latitude))
) AS distance
```

**Utilisation** : Recherche des pharmacies dans un rayon donné (par défaut 10 km)

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

# Lancer les migrations
php artisan migrate

# Ajouter des données de test
php artisan db:seed
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
2. **Authentification** : Laravel UI avec hachage bcrypt des mots de passe
3. **Middleware** : Protection des routes sensibles (admin)
4. **Validation** : Validation des données côté serveur
5. **Sanitization** : Échappement automatique dans Blade
6. **SQL Injection** : Protégé par Eloquent ORM (requêtes préparées)

---

## Points d'Extension

### Ajouter une Nouvelle Fonctionnalité

1. **Créer la migration** : `php artisan make:migration create_table_name`
2. **Créer le modèle** : `php artisan make:model NomModel`
3. **Créer le contrôleur** : `php artisan make:controller NomController`
4. **Ajouter les routes** : `routes/web.php` ou `routes/api.php`
5. **Créer les vues** : `resources/views/nom/`
6. **Tester** : Feature tests dans `tests/Feature/`

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
- `app/Models/Pharmacy.php` (méthodes `getPredefinedMessage`, `getWhatsappUrlAttribute`, `getEmailUrlAttribute`)

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

## Conclusion

Cette documentation couvre l'ensemble de l'architecture et des composants de **GeoPharma**. Chaque dossier et fichier a un rôle spécifique dans le fonctionnement de l'application. 

Pour toute question ou amélioration, consultez la documentation Laravel officielle : https://laravel.com/docs

Pour migrer vers Firebase, consultez **[FIREBASE_MIGRATION.md](FIREBASE_MIGRATION.md)**.

---

**Développé par Scholastique Binda, Joviette Kandolo et Jedidia Umba**

*Dernière mise à jour : 2025*


