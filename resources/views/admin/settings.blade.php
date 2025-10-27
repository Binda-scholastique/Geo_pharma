@extends('layouts.app')

@section('title', 'Paramètres Administrateur - GeoPharma')

@section('content')
<div class="container-fluid" style="background-color: #f8f9fa; min-height: 100vh;">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center py-4 px-4 mb-4 bg-white shadow-sm">
        <div>
            <h1 class="h3 mb-0" style="color: #495057;">
                <i class="fas fa-cog me-2" style="color: #10b981;"></i>
                Paramètres Administrateur
            </h1>
            <p class="text-muted mb-0">Configuration du système</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            Retour au dashboard
        </a>
    </div>

    <div class="row px-4">
        <div class="col-lg-8">
            <!-- Configuration système -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 fw-bold text-primary">
                        <i class="fas fa-server me-2"></i>
                        Configuration Système
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.settings.system') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="site_name" class="form-label">Nom du site</label>
                            <input type="text" class="form-control" id="site_name" name="site_name" value="GeoPharma">
                        </div>
                        
                        <div class="mb-3">
                            <label for="default_radius" class="form-label">Rayon de recherche par défaut (km)</label>
                            <input type="number" class="form-control" id="default_radius" name="default_radius" value="10" min="1" max="100">
                        </div>
                        
                        <div class="mb-3">
                            <label for="max_pharmacies_per_user" class="form-label">Nombre maximum de pharmacies par pharmacien</label>
                            <input type="number" class="form-control" id="max_pharmacies_per_user" name="max_pharmacies_per_user" value="5" min="1" max="50">
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-2"></i>
                                Sauvegarder la configuration
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Notifications administrateur -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 fw-bold text-primary">
                        <i class="fas fa-bell me-2"></i>
                        Notifications Administrateur
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.settings.notifications') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="new_user_notifications" name="new_user_notifications" checked>
                            <label class="form-check-label" for="new_user_notifications">
                                Notifications nouveaux utilisateurs
                            </label>
                            <small class="form-text text-muted d-block">Recevez une notification lors de l'inscription d'un nouvel utilisateur</small>
                        </div>
                        
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="new_pharmacy_notifications" name="new_pharmacy_notifications" checked>
                            <label class="form-check-label" for="new_pharmacy_notifications">
                                Notifications nouvelles pharmacies
                            </label>
                            <small class="form-text text-muted d-block">Recevez une notification lors de l'ajout d'une nouvelle pharmacie</small>
                        </div>
                        
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="system_alerts" name="system_alerts" checked>
                            <label class="form-check-label" for="system_alerts">
                                Alertes système
                            </label>
                            <small class="form-text text-muted d-block">Recevez des alertes en cas de problème système</small>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-2"></i>
                                Sauvegarder les préférences
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 fw-bold text-primary">
                        <i class="fas fa-database me-2"></i>
                        Maintenance
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-info">
                            <i class="fas fa-download me-2"></i>
                            Sauvegarde de la base de données
                        </button>
                        <button type="button" class="btn btn-outline-warning">
                            <i class="fas fa-broom me-2"></i>
                            Nettoyer le cache
                        </button>
                        <button type="button" class="btn btn-outline-secondary">
                            <i class="fas fa-sync me-2"></i>
                            Optimiser la base de données
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 fw-bold text-primary">
                        <i class="fas fa-chart-line me-2"></i>
                        Statistiques rapides
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="border-end">
                                <h5 class="text-primary mb-1">{{ \App\Models\User::where('role', 'user')->count() }}</h5>
                                <small class="text-muted">Utilisateurs</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <h5 class="text-success mb-1">{{ \App\Models\User::where('role', 'pharmacist')->count() }}</h5>
                            <small class="text-muted">Pharmaciens</small>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="border-end">
                                <h5 class="text-info mb-1">{{ \App\Models\Pharmacy::where('is_verified', true)->count() }}</h5>
                                <small class="text-muted">Pharmacies vérifiées</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <h5 class="text-warning mb-1">{{ \App\Models\Pharmacy::where('is_verified', false)->count() }}</h5>
                            <small class="text-muted">En attente</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 fw-bold text-primary">
                        <i class="fas fa-shield-alt me-2"></i>
                        Sécurité
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.profile') }}" class="btn btn-outline-primary">
                            <i class="fas fa-user-edit me-2"></i>
                            Modifier le profil
                        </a>
                        <a href="{{ route('admin.profile') }}#password" class="btn btn-outline-warning">
                            <i class="fas fa-key me-2"></i>
                            Changer le mot de passe
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
