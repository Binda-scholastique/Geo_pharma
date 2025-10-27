@extends('layouts.app')

@section('title', 'Paramètres - GeoPharma')

@section('content')
<div class="container-fluid" style="background-color: #f8f9fa; min-height: 100vh;">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center py-4 px-4 mb-4 bg-white shadow-sm">
        <div>
            <h1 class="h3 mb-0" style="color: #495057;">
                <i class="fas fa-cog me-2" style="color: #10b981;"></i>
                Paramètres
            </h1>
            <p class="text-muted mb-0">Personnalisez votre expérience</p>
        </div>
        <a href="{{ route('user.profile') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            Retour au profil
        </a>
    </div>

    <div class="row px-4">
        <div class="col-lg-8">
            <!-- Notifications -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 fw-bold text-primary">
                        <i class="fas fa-bell me-2"></i>
                        Notifications
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('user.settings.notifications') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="email_notifications" name="email_notifications" checked>
                            <label class="form-check-label" for="email_notifications">
                                Notifications par email
                            </label>
                            <small class="form-text text-muted d-block">Recevez des notifications par email</small>
                        </div>
                        
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="pharmacy_updates" name="pharmacy_updates" checked>
                            <label class="form-check-label" for="pharmacy_updates">
                                Mises à jour des pharmacies
                            </label>
                            <small class="form-text text-muted d-block">Soyez informé des nouvelles pharmacies près de chez vous</small>
                        </div>
                        
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="promotional_emails" name="promotional_emails">
                            <label class="form-check-label" for="promotional_emails">
                                Emails promotionnels
                            </label>
                            <small class="form-text text-muted d-block">Recevez des offres spéciales et promotions</small>
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
            
            <!-- Préférences d'affichage -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 fw-bold text-primary">
                        <i class="fas fa-palette me-2"></i>
                        Préférences d'affichage
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('user.settings.display') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="language" class="form-label">Langue</label>
                            <select class="form-select" id="language" name="language">
                                <option value="fr" selected>Français</option>
                                <option value="en">English</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="timezone" class="form-label">Fuseau horaire</label>
                            <select class="form-select" id="timezone" name="timezone">
                                <option value="Europe/Paris" selected>Europe/Paris (GMT+1)</option>
                                <option value="Europe/London">Europe/London (GMT+0)</option>
                                <option value="America/New_York">America/New_York (GMT-5)</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="distance_unit" class="form-label">Unité de distance</label>
                            <select class="form-select" id="distance_unit" name="distance_unit">
                                <option value="km" selected>Kilomètres</option>
                                <option value="miles">Miles</option>
                            </select>
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
                        <i class="fas fa-shield-alt me-2"></i>
                        Sécurité
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('user.profile') }}" class="btn btn-outline-primary">
                            <i class="fas fa-user-edit me-2"></i>
                            Modifier le profil
                        </a>
                        <a href="{{ route('user.profile') }}#password" class="btn btn-outline-warning">
                            <i class="fas fa-key me-2"></i>
                            Changer le mot de passe
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 fw-bold text-primary">
                        <i class="fas fa-download me-2"></i>
                        Données
                    </h6>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">Téléchargez vos données personnelles</p>
                    <div class="d-grid">
                        <button type="button" class="btn btn-outline-info">
                            <i class="fas fa-download me-2"></i>
                            Exporter mes données
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
