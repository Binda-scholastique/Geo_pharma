@extends('layouts.app')

@section('title', 'Modifier Utilisateur - Administration')

@section('content')
<div class="container-fluid" style="background-color: #f8f9fa; min-height: 100vh;">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center py-4 px-4 mb-4 bg-white shadow-sm">
        <div>
            <h1 class="h3 mb-0 text-dark">
                <i class="fas fa-user-edit text-success me-2"></i>
                Modifier l'utilisateur
            </h1>
            <p class="text-muted mb-0">Modifier les informations de {{ $user->name }}</p>
        </div>
        <div>
            <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-info me-2">
                <i class="fas fa-eye me-2"></i>
                Voir les détails
            </a>
            <a href="{{ route('admin.users') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>
                Retour à la liste
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Formulaire -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 fw-bold text-primary">
                        <i class="fas fa-edit me-2"></i>
                        Modifier les informations
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nom complet <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Adresse email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="role" class="form-label">Rôle <span class="text-danger">*</span></label>
                                <select class="form-control @error('role') is-invalid @enderror" 
                                        id="role" name="role" required>
                                    <option value="">Sélectionner un rôle</option>
                                    <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>Utilisateur</option>
                                    <option value="pharmacist" {{ old('role', $user->role) == 'pharmacist' ? 'selected' : '' }}>Pharmacien</option>
                                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Administrateur</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="authorization_number" class="form-label">Numéro d'autorisation</label>
                                <input type="text" class="form-control @error('authorization_number') is-invalid @enderror" 
                                       id="authorization_number" name="authorization_number" 
                                       value="{{ old('authorization_number', $user->authorization_number) }}">
                                @error('authorization_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Requis pour les pharmaciens</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="profile_completed" 
                                           name="profile_completed" value="1" 
                                           {{ old('profile_completed', $user->profile_completed) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="profile_completed">
                                        Profil complété
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="email_verified" 
                                           name="email_verified" value="1" 
                                           {{ old('email_verified', $user->email_verified_at) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="email_verified">
                                        Email vérifié
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_active" 
                                           name="is_active" value="1" 
                                           {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Compte actif
                                    </label>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <h6 class="text-primary mb-3">
                            <i class="fas fa-key me-2"></i>
                            Changer le mot de passe (optionnel)
                        </h6>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Nouveau mot de passe</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Laissez vide pour conserver le mot de passe actuel</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Confirmer le nouveau mot de passe</label>
                                <input type="password" class="form-control" 
                                       id="password_confirmation" name="password_confirmation">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-secondary me-2">
                                <i class="fas fa-times me-2"></i>
                                Annuler
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-2"></i>
                                Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Informations actuelles -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 fw-bold text-primary">
                        <i class="fas fa-info-circle me-2"></i>
                        Informations actuelles
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nom</label>
                        <p class="form-control-plaintext">{{ $user->name }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <p class="form-control-plaintext">{{ $user->email }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Rôle actuel</label>
                        <p class="form-control-plaintext">
                            @if($user->role === 'admin')
                                <span class="badge badge-danger">Administrateur</span>
                            @elseif($user->role === 'pharmacist')
                                <span class="badge badge-success">Pharmacien</span>
                            @else
                                <span class="badge badge-info">Utilisateur</span>
                            @endif
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Inscrit le</label>
                        <p class="form-control-plaintext">{{ $user->created_at ? $user->created_at->format('d/m/Y à H:i') : '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 fw-bold text-primary">
                        <i class="fas fa-bolt me-2"></i>
                        Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-info">
                            <i class="fas fa-eye me-2"></i>
                            Voir les détails
                        </a>
                        
                        @if($user->role === 'pharmacist')
                        <a href="{{ route('admin.pharmacies') }}?pharmacist={{ $user->id }}" class="btn btn-success">
                            <i class="fas fa-store me-2"></i>
                            Voir ses pharmacies
                        </a>
                        @endif

                        <button type="button" class="btn btn-danger" 
                                onclick="confirmDelete('{{ route('admin.users.destroy', $user->id) }}', '{{ $user->name }}')">
                            <i class="fas fa-trash me-2"></i>
                            Supprimer l'utilisateur
                        </button>
                    </div>
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
                        <div class="col-6">
                            <div class="h4 mb-0 text-primary">{{ $user->pharmacies->count() }}</div>
                            <div class="small text-muted">Pharmacies</div>
                        </div>
                        <div class="col-6">
                            <div class="h4 mb-0 text-success">
                                {{ $user->pharmacies->where('is_verified', true)->count() }}
                            </div>
                            <div class="small text-muted">Vérifiées</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmer la suppression</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer l'utilisateur <strong id="userName"></strong> ?</p>
                <p class="text-danger"><strong>Cette action est irréversible !</strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
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

function confirmDelete(url, userName) {
    document.getElementById('userName').textContent = userName;
    document.getElementById('deleteForm').action = url;
    $('#deleteModal').modal('show');
}
</script>
@endpush