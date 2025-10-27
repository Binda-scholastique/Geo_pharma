@extends('layouts.app')

@section('title', 'Détails Utilisateur - Administration')

@section('content')
<div class="container-fluid" style="background-color: #f8f9fa; min-height: 100vh;">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center py-4 px-4 mb-4 bg-white shadow-sm">
        <div>
            <h1 class="h3 mb-0 text-dark">
                <i class="fas fa-user text-success me-2"></i>
                Détails de l'utilisateur
            </h1>
            <p class="text-muted mb-0">Informations complètes sur {{ $user->name }}</p>
        </div>
        <div>
            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning me-2">
                <i class="fas fa-edit me-2"></i>
                Modifier
            </a>
            <a href="{{ route('admin.users') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>
                Retour à la liste
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Informations principales -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 fw-bold text-primary">
                        <i class="fas fa-info-circle me-2"></i>
                        Informations personnelles
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Nom complet</label>
                            <p class="form-control-plaintext">{{ $user->name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Adresse email</label>
                            <p class="form-control-plaintext">
                                {{ $user->email }}
                                @if($user->email_verified_at)
                                    <span class="badge badge-success ml-2">Vérifié</span>
                                @else
                                    <span class="badge badge-warning ml-2">Non vérifié</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Rôle</label>
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
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Statut du profil</label>
                            <p class="form-control-plaintext">
                                @if($user->profile_completed)
                                    <span class="badge badge-success">Complet</span>
                                @else
                                    <span class="badge badge-warning">Incomplet</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    @if($user->authorization_number)
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Numéro d'autorisation</label>
                            <p class="form-control-plaintext">{{ $user->authorization_number }}</p>
                        </div>
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Date d'inscription</label>
                            <p class="form-control-plaintext">{{ $user->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Dernière connexion</label>
                            <p class="form-control-plaintext">
                                @if($user->last_login_at)
                                    {{ $user->last_login_at->format('d/m/Y à H:i') }}
                                @else
                                    <span class="text-muted">Jamais connecté</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pharmacies (si pharmacien) -->
            @if($user->role === 'pharmacist' && $user->pharmacies->count() > 0)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 fw-bold text-primary">
                        <i class="fas fa-store me-2"></i>
                        Pharmacies gérées ({{ $user->pharmacies->count() }})
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Adresse</th>
                                    <th>Statut</th>
                                    <th>Vérifiée</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->pharmacies as $pharmacy)
                                <tr>
                                    <td>{{ $pharmacy->name }}</td>
                                    <td>{{ $pharmacy->address }}, {{ $pharmacy->city }}</td>
                                    <td>
                                        @if($pharmacy->is_active)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($pharmacy->is_verified)
                                            <span class="badge badge-success">Vérifiée</span>
                                        @else
                                            <span class="badge badge-warning">En attente</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.pharmacies.show', $pharmacy) }}" 
                                           class="btn btn-sm btn-info" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Actions rapides -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 fw-bold text-primary">
                        <i class="fas fa-bolt me-2"></i>
                        Actions rapides
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>
                            Modifier l'utilisateur
                        </a>
                        
                        @if($user->role === 'pharmacist')
                        <a href="{{ route('admin.pharmacies') }}?pharmacist={{ $user->id }}" class="btn btn-info">
                            <i class="fas fa-store me-2"></i>
                            Voir ses pharmacies
                        </a>
                        @endif

                        <button type="button" class="btn btn-danger" 
                                onclick="confirmDelete('{{ route('admin.users.destroy', $user) }}', '{{ $user->name }}')">
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

            <!-- Informations système -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 fw-bold text-primary">
                        <i class="fas fa-cog me-2"></i>
                        Informations système
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">ID utilisateur</label>
                        <p class="form-control-plaintext">{{ $user->id }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email vérifié le</label>
                        <p class="form-control-plaintext">
                            @if($user->email_verified_at)
                                {{ $user->email_verified_at->format('d/m/Y à H:i') }}
                            @else
                                <span class="text-muted">Non vérifié</span>
                            @endif
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Dernière mise à jour</label>
                        <p class="form-control-plaintext">{{ $user->updated_at->format('d/m/Y à H:i') }}</p>
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
function confirmDelete(url, userName) {
    document.getElementById('userName').textContent = userName;
    document.getElementById('deleteForm').action = url;
    $('#deleteModal').modal('show');
}
</script>
@endpush