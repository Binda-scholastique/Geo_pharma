# GeoPharma - Application de Géolocalisation de Pharmacies

## Description

GeoPharma est une application web moderne développée avec Laravel qui permet aux utilisateurs de trouver facilement les pharmacies à proximité grâce à la géolocalisation. L'application offre également un système de gestion pour les pharmaciens qui peuvent ajouter et gérer leurs pharmacies.

## Fonctionnalités

### Pour les Utilisateurs
- 🔍 **Recherche par géolocalisation** : Trouvez les pharmacies les plus proches de votre position
- 🗺️ **Carte interactive** : Visualisez les pharmacies sur une carte avec Leaflet
- 📱 **Contact WhatsApp** : Contactez directement les pharmacies via WhatsApp
- 🔐 **Inscription optionnelle** : Créez un compte pour accéder aux détails complets des pharmacies
- 📍 **Recherche par ville** : Recherchez des pharmacies dans une ville spécifique

### Pour les Pharmaciens
- 👨‍⚕️ **Inscription sécurisée** : Création de compte avec vérification du numéro d'autorisation
- 🏥 **Gestion des pharmacies** : Ajoutez et gérez vos pharmacies
- 📊 **Dashboard complet** : Suivez vos pharmacies et leurs statuts
- ✅ **Vérification automatique** : Système de vérification des numéros d'autorisation
- 📝 **Profil complet** : Complétez vos informations pour activer toutes les fonctionnalités

## Technologies Utilisées

- **Backend** : Laravel 10.49.1
- **Frontend** : Blade Templates + Tailwind CSS
- **Base de données** : MySQL/SQLite
- **Cartes** : Leaflet.js
- **Icônes** : Font Awesome
- **Géolocalisation** : API HTML5 Geolocation
- **Authentification** : Laravel UI + Sanctum
- **PHP** : ^8.1 (requis pour Laravel 10)

## Installation

### Prérequis
- PHP 8.1 ou supérieur (requis pour Laravel 10)
- Composer
- Node.js et NPM
- MySQL ou SQLite

### Étapes d'installation

1. **Cloner le projet**
```bash
git clone [url-du-repo]
cd Geo_pharma
```

2. **Installer les dépendances PHP**
```bash
composer install
```

3. **Installer les dépendances JavaScript**
```bash
npm install && npm run dev
```

4. **Configuration de l'environnement**
```bash
cp .env.example .env
php artisan key:generate
```

5. **Configuration de la base de données**
Modifiez le fichier `.env` pour configurer votre base de données :
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=geo_pharma
DB_USERNAME=root
DB_PASSWORD=
```

6. **Exécuter les migrations**
```bash
php artisan migrate
```

7. **Ajouter des données de test (optionnel)**
```bash
php artisan db:seed
```

8. **Démarrer le serveur**
```bash
php artisan serve
```

L'application sera accessible à l'adresse `http://localhost:8000`

## Structure du Projet

```
Geo_pharma/
├── app/
│   ├── Http/Controllers/
│   │   ├── Auth/RegisterController.php
│   │   ├── PharmacyController.php
│   │   ├── PharmacistController.php
│   │   └── Api/PharmacyApiController.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Pharmacy.php
│   │   └── AuthorizationNumber.php
│   └── Services/
│       └── AuthorizationService.php
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   └── views/
│       ├── layouts/
│       ├── pharmacies/
│       ├── pharmacist/
│       └── auth/
└── routes/
    ├── web.php
    └── api.php
```

## API Endpoints

### Pharmacies
- `GET /api/pharmacies` - Liste toutes les pharmacies
- `GET /api/pharmacies/map` - Pharmacies pour l'affichage sur carte
- `POST /api/pharmacies/nearby` - Recherche par proximité
- `POST /api/pharmacies/search-by-city` - Recherche par ville
- `POST /api/pharmacies/search` - Recherche avancée
- `GET /api/pharmacies/{id}` - Détails d'une pharmacie

## Comptes de Test

Après avoir exécuté les seeders, vous pouvez utiliser ces comptes :

### Utilisateur
- **Email** : jean.dupont@example.com
- **Mot de passe** : password

### Pharmacien
- **Email** : marie.martin@pharmacie.fr
- **Mot de passe** : password
- **Numéro d'autorisation** : PH123456789

## Fonctionnalités Avancées

### Système d'Autorisation
- Vérification des numéros d'autorisation via API externe
- Simulation d'API pour le développement (numéros commençant par "PH")
- Gestion des autorisations expirées

### Géolocalisation
- Calcul de distance en temps réel
- Recherche par rayon personnalisable
- Géocodage automatique des adresses

### Interface Moderne
- Design responsive avec Tailwind CSS
- Animations et transitions fluides
- Interface intuitive et accessible

## Développement

### Ajout de Nouvelles Fonctionnalités
1. Créer les migrations nécessaires
2. Développer les modèles et relations
3. Implémenter les contrôleurs
4. Créer les vues Blade
5. Ajouter les routes
6. Tester les fonctionnalités

### Personnalisation
- Modifiez les styles dans `resources/views/layouts/app.blade.php`
- Ajoutez de nouveaux services dans `app/Services/`
- Étendez les modèles selon vos besoins

## Contribution

1. Fork le projet
2. Créez une branche pour votre fonctionnalité
3. Committez vos changements
4. Poussez vers la branche
5. Ouvrez une Pull Request

## Licence

Ce projet est sous licence MIT. Voir le fichier `LICENSE` pour plus de détails.

## Documentation Complète

### Documentation Principale

Pour une documentation détaillée de l'architecture, de tous les dossiers et fichiers de l'application, consultez **[DOCUMENTATION.md](DOCUMENTATION.md)**.

Cette documentation inclut :
- Architecture complète de l'application
- Description détaillée de chaque dossier et fichier
- Flux de données et processus métier
- Structure de la base de données
- Guide des routes et API
- Instructions pour étendre l'application
- Nouvelles fonctionnalités (horaires d'ouverture, création admin, etc.)

### Migration vers Firebase

Pour migrer l'application de MySQL vers Firebase (Firestore), consultez **[FIREBASE_MIGRATION.md](FIREBASE_MIGRATION.md)**.

Ce guide inclut :
- Installation et configuration Firebase
- Migration des modèles Eloquent vers Firestore
- Adaptation des contrôleurs
- Authentification Firebase
- Scripts de migration des données
- Stockage de fichiers cloud
- Guide de déploiement

## Support

Pour toute question ou problème, veuillez ouvrir une issue sur GitHub.

---

**Développé par Scholastique Binda, Joviette Kandolo et Jedidia Umba**