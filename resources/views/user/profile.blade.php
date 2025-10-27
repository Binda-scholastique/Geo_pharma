@extends('layouts.app')

@section('title', 'Mon Profil - GeoPharma')

@section('content')
<div class="container-fluid" style="background-color: #f8f9fa; min-height: 100vh;">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center py-4 px-4 mb-4 bg-white shadow-sm">
        <div>
            <h1 class="h3 mb-0" style="color: #495057;">
                <i class="fas fa-user me-2" style="color: #10b981;"></i>
                Mon Profil
            </h1>
            <p class="text-muted mb-0">Gérez vos informations personnelles</p>
        </div>
        <a href="{{ route('home') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            Retour à l'accueil
        </a>
    </div>

    <div class="row px-4">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 fw-bold text-primary">
                        <i class="fas fa-user-edit me-2"></i>
                        Informations Personnelles
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('user.profile.update') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nom complet</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-2"></i>
                                Mettre à jour le profil
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 fw-bold text-primary">
                        <i class="fas fa-lock me-2"></i>
                        Changer le mot de passe
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('user.password.update') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Mot de passe actuel</label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                   id="current_password" name="current_password" required>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Nouveau mot de passe</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                                <input type="password" class="form-control" 
                                       id="password_confirmation" name="password_confirmation" required>
                            </div>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-key me-2"></i>
                                Changer le mot de passe
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
                        <i class="fas fa-info-circle me-2"></i>
                        Informations du compte
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Type de compte :</strong>
                        <span class="badge bg-primary ms-2">Utilisateur</span>
                    </div>
                    <div class="mb-3">
                        <strong>Membre depuis :</strong>
                        <br>{{ auth()->user()->created_at->format('d/m/Y') }}
                    </div>
                    <div class="mb-3">
                        <strong>Email vérifié :</strong>
                        @if(auth()->user()->email_verified_at)
                            <span class="badge bg-success ms-2">
                                <i class="fas fa-check me-1"></i>Oui
                            </span>
                        @else
                            <span class="badge bg-warning ms-2">
                                <i class="fas fa-exclamation-triangle me-1"></i>Non
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 fw-bold text-primary">
                        <i class="fas fa-cog me-2"></i>
                        Actions rapides
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('user.settings') }}" class="btn btn-outline-primary">
                            <i class="fas fa-cog me-2"></i>
                            Paramètres
                        </a>
                        <a href="{{ route('pharmacies.search') }}" class="btn btn-outline-success">
                            <i class="fas fa-search me-2"></i>
                            Rechercher des pharmacies
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
