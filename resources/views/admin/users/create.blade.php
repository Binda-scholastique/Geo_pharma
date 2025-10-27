@extends('layouts.app')

@section('title', 'Créer un Utilisateur - Administration')

@section('content')
<div class="container-fluid" style="background-color: #f8f9fa; min-height: 100vh;">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center py-4 px-4 mb-4 bg-white shadow-sm">
        <div>
            <h1 class="h3 mb-0 text-dark">
                <i class="fas fa-user-plus text-success me-2"></i>
                Créer un Utilisateur
            </h1>
            <p class="text-muted mb-0">Ajouter un nouvel utilisateur à la plateforme</p>
        </div>
        <a href="{{ route('admin.users') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            Retour à la liste
        </a>
    </div>

    <!-- Formulaire -->
    <div class="row px-4">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 fw-bold text-primary">
                        <i class="fas fa-user me-2"></i>
                        Informations de l'utilisateur
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.users.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nom complet <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Adresse email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Mot de passe <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Minimum 8 caractères</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Confirmer le mot de passe <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" 
                                       id="password_confirmation" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="role" class="form-label">Rôle <span class="text-danger">*</span></label>
                                <select class="form-control @error('role') is-invalid @enderror" 
                                        id="role" name="role" required>
                                    <option value="">Sélectionner un rôle</option>
                                    <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>Utilisateur</option>
                                    <option value="pharmacist" {{ old('role') == 'pharmacist' ? 'selected' : '' }}>Pharmacien</option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrateur</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="authorization_number" class="form-label">Numéro d'autorisation</label>
                                <input type="text" class="form-control @error('authorization_number') is-invalid @enderror" 
                                       id="authorization_number" name="authorization_number" 
                                       value="{{ old('authorization_number') }}">
                                @error('authorization_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Requis pour les pharmaciens</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="profile_completed" 
                                           name="profile_completed" value="1" {{ old('profile_completed') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="profile_completed">
                                        Profil complété
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="email_verified" 
                                           name="email_verified" value="1" {{ old('email_verified') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="email_verified">
                                        Email vérifié
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('admin.users') }}" class="btn btn-secondary me-2">
                                <i class="fas fa-times me-2"></i>
                                Annuler
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-2"></i>
                                Créer l'utilisateur
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Aide -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 fw-bold text-primary">
                        <i class="fas fa-info-circle me-2"></i>
                        Informations
                    </h6>
                </div>
                <div class="card-body">
                    <h6>Rôles disponibles :</h6>
                    <ul class="list-unstyled">
                        <li><span class="badge badge-info">Utilisateur</span> - Accès public</li>
                        <li><span class="badge badge-success">Pharmacien</span> - Gestion pharmacies</li>
                        <li><span class="badge badge-danger">Administrateur</span> - Accès complet</li>
                    </ul>

                    <hr>

                    <h6>Champs obligatoires :</h6>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-check text-success mr-1"></i> Nom complet</li>
                        <li><i class="fas fa-check text-success mr-1"></i> Adresse email</li>
                        <li><i class="fas fa-check text-success mr-1"></i> Mot de passe</li>
                        <li><i class="fas fa-check text-success mr-1"></i> Rôle</li>
                    </ul>

                    <hr>

                    <h6>Notes :</h6>
                    <p class="small text-muted">
                        Le numéro d'autorisation est requis pour les pharmaciens. 
                        L'utilisateur recevra un email de bienvenue avec ses identifiants.
                    </p>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 fw-bold text-primary">
                        <i class="fas fa-chart-bar me-2"></i>
                        Statistiques
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="border-right">
                                <div class="h4 mb-0 text-primary">{{ \App\Models\User::count() }}</div>
                                <div class="small text-muted">Total</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border-right">
                                <div class="h4 mb-0 text-success">{{ \App\Models\User::where('role', 'pharmacist')->count() }}</div>
                                <div class="small text-muted">Pharmaciens</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="h4 mb-0 text-info">{{ \App\Models\User::where('role', 'user')->count() }}</div>
                            <div class="small text-muted">Utilisateurs</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('role');
    const authorizationField = document.getElementById('authorization_number');
    const authorizationLabel = authorizationField.previousElementSibling;

    function toggleAuthorizationField() {
        if (roleSelect.value === 'pharmacist') {
            authorizationField.required = true;
            authorizationLabel.innerHTML = 'Numéro d\'autorisation <span class="text-danger">*</span>';
        } else {
            authorizationField.required = false;
            authorizationLabel.innerHTML = 'Numéro d\'autorisation';
        }
    }

    roleSelect.addEventListener('change', toggleAuthorizationField);
    toggleAuthorizationField(); // Initialiser l'état
});
</script>
@endpush