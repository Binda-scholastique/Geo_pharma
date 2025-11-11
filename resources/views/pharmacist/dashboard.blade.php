@extends('layouts.app')

@section('title', 'Dashboard Pharmacien - GeoPharma')

@section('content')
<div class="container-fluid" style="background-color: #f8f9fa; min-height: 100vh;">
    <!-- Header avec gradient -->
    <div class="py-4 px-4 mb-4" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 0.5rem; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h2 text-white mb-1">
                    <i class="fas fa-user-md me-2"></i>
                    Dashboard Pharmacien
                </h1>
                <p class="text-white-50 mb-0">
                    @if($pharmacies->count() > 0)
                        Vous gérez {{ $pharmacies->count() }} pharmacie{{ $pharmacies->count() > 1 ? 's' : '' }}
                    @else
                        Gérez vos pharmacies et votre profil
                    @endif
                </p>
            </div>
            <div class="text-end">
                <div class="text-white-50 small">
                    @php
                        $hour = date('H');
                        if ($hour < 12) {
                            $greeting = 'Bonjour';
                        } elseif ($hour < 18) {
                            $greeting = 'Bon après-midi';
                        } else {
                            $greeting = 'Bonsoir';
                        }
                    @endphp
                    {{ $greeting }},
                </div>
                <div class="text-white h5 mb-0">{{ Auth::user()->name }}</div>
                <div class="text-white-50 small">{{ Auth::user()->email }}</div>
            </div>
        </div>
    </div>

    <!-- Alerte profil incomplet -->
    @if(!Auth::user()->profile_completed || !Auth::user()->latitude || !Auth::user()->longitude)
        <div class="alert alert-warning alert-dismissible fade show mx-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                <div class="flex-grow-1">
                    <h5 class="alert-heading mb-1">Profil incomplet</h5>
                    <p class="mb-2">Votre profil n'est pas encore complet. Complétez-le pour améliorer votre visibilité.</p>
                    <div class="d-flex gap-2">
                        @if(!Auth::user()->profile_completed)
                            <a href="{{ route('pharmacist.complete-profile') }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-user-edit me-1"></i>Compléter le profil
                            </a>
                        @endif
                        @if(!Auth::user()->latitude || !Auth::user()->longitude)
                            <a href="{{ route('pharmacist.location') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-map-marker-alt me-1"></i>Ajouter ma localisation
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Statistiques -->
    <div class="row mb-4 px-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-primary border-3 shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col me-2">
                            <div class="small fw-bold text-primary text-uppercase mb-1">
                                Mes Pharmacies
                            </div>
                            <div class="h5 mb-0 fw-bold text-dark">{{ $pharmacies->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-store fa-2x text-muted"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-success border-3 shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col me-2">
                            <div class="small fw-bold text-success text-uppercase mb-1">
                                Pharmacies Actives
                            </div>
                            <div class="h5 mb-0 fw-bold text-dark">{{ $pharmacies->where('is_active', true)->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-muted"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-info border-3 shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col me-2">
                            <div class="small fw-bold text-info text-uppercase mb-1">
                                Pharmacies Vérifiées
                            </div>
                            <div class="h5 mb-0 fw-bold text-dark">{{ $pharmacies->where('is_verified', true)->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shield-alt fa-2x text-muted"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-warning border-3 shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col me-2">
                            <div class="small fw-bold text-warning text-uppercase mb-1">
                                En Attente
                            </div>
                            <div class="h5 mb-0 fw-bold text-dark">{{ $pharmacies->where('is_verified', false)->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-muted"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="card shadow mb-4 mx-4">
        <div class="card-header py-3">
            <h6 class="m-0 fw-bold text-primary">
                <i class="fas fa-bolt me-2"></i>
                Actions Rapides
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-3 mb-3">
                    <a href="{{ route('pharmacist.create-pharmacy') }}" class="btn btn-success btn-block h-100 d-flex flex-column justify-content-center align-items-center p-4">
                        <i class="fas fa-plus fa-2x mb-2"></i>
                        <span class="fw-bold">Ajouter une pharmacie</span>
                        <small class="text-muted">Nouvelle pharmacie</small>
                    </a>
                </div>
                <div class="col-lg-3 mb-3">
                    <a href="{{ route('pharmacist.profile') }}" class="btn btn-info btn-block h-100 d-flex flex-column justify-content-center align-items-center p-4">
                        <i class="fas fa-user-edit fa-2x mb-2"></i>
                        <span class="fw-bold">Modifier mon profil</span>
                        <small class="text-muted">Informations personnelles</small>
                    </a>
                </div>
                <div class="col-lg-3 mb-3">
                    <a href="{{ route('pharmacist.location') }}" class="btn btn-primary btn-block h-100 d-flex flex-column justify-content-center align-items-center p-4">
                        <i class="fas fa-map-marker-alt fa-2x mb-2"></i>
                        <span class="fw-bold">Ma localisation</span>
                        <small class="text-muted">Position GPS</small>
                    </a>
                </div>
                <div class="col-lg-3 mb-3">
                    <a href="{{ route('pharmacist.settings') }}" class="btn btn-warning btn-block h-100 d-flex flex-column justify-content-center align-items-center p-4">
                        <i class="fas fa-cog fa-2x mb-2"></i>
                        <span class="fw-bold">Paramètres</span>
                        <small class="text-muted">Configuration du compte</small>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Informations du profil -->
    <div class="row px-4 mb-4">
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 fw-bold text-primary">
                        <i class="fas fa-user-md me-2"></i>
                        Informations du Profil
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6 mb-3">
                            <strong>Nom :</strong><br>
                            {{ Auth::user()->name }}
                        </div>
                        <div class="col-sm-6 mb-3">
                            <strong>Email :</strong><br>
                            {{ Auth::user()->email }}
                        </div>
                        <div class="col-sm-6 mb-3">
                            <strong>Numéro d'autorisation :</strong><br>
                            {{ Auth::user()->authorization_number ?? 'Non renseigné' }}
                        </div>
                        <div class="col-sm-6 mb-3">
                            <strong>Profil complété :</strong><br>
                            @if(Auth::user()->profile_completed)
                                <span class="badge bg-success">
                                    <i class="fas fa-check me-1"></i>Oui
                                </span>
                            @else
                                <span class="badge bg-warning text-dark">
                                    <i class="fas fa-exclamation-triangle me-1"></i>Non
                                </span>
                            @endif
                        </div>
                        @if(Auth::user()->latitude && Auth::user()->longitude)
                            <div class="col-sm-6 mb-3">
                                <strong>Localisation :</strong><br>
                                <span class="badge bg-success">
                                    <i class="fas fa-map-marker-alt me-1"></i>Définie
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 fw-bold text-primary">
                        <i class="fas fa-chart-pie me-2"></i>
                        Statistiques Rapides
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4 mb-3">
                            <div class="border-end">
                                <h4 class="text-primary mb-1">{{ $pharmacies->count() }}</h4>
                                <small class="text-muted">Total</small>
                            </div>
                        </div>
                        <div class="col-4 mb-3">
                            <div class="border-end">
                                <h4 class="text-success mb-1">{{ $pharmacies->where('is_verified', true)->count() }}</h4>
                                <small class="text-muted">Vérifiées</small>
                            </div>
                        </div>
                        <div class="col-4 mb-3">
                            <h4 class="text-warning mb-1">{{ $pharmacies->where('is_verified', false)->count() }}</h4>
                            <small class="text-muted">En attente</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mes pharmacies -->
    <div class="card shadow mb-4 mx-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 fw-bold text-primary">
                <i class="fas fa-store me-2"></i>
                Mes Pharmacies
                @if($pharmacies->count() > 0)
                    <span class="badge bg-primary ms-2">{{ $pharmacies->count() }}</span>
                @endif
            </h6>
            <a href="{{ route('pharmacist.create-pharmacy') }}" class="btn btn-success btn-sm">
                <i class="fas fa-plus me-1"></i>
                Ajouter
            </a>
        </div>
        <div class="card-body">
            @if($pharmacies->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Adresse</th>
                                <th>Ville</th>
                                <th>Statut</th>
                                <th>Vérification</th>
                                <th>Date création</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pharmacies as $pharmacy)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                            <i class="fas fa-store"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $pharmacy->name }}</div>
                                            @if($pharmacy->phone)
                                                <small class="text-muted">{{ $pharmacy->phone }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $pharmacy->address }}</td>
                                <td>{{ $pharmacy->city }}</td>
                                <td>
                                    @if($pharmacy->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    @if($pharmacy->is_verified)
                                        <span class="badge bg-success">Vérifiée</span>
                                    @else
                                        <span class="badge bg-warning text-dark">En attente</span>
                                    @endif
                                </td>
                                <td>{{ $pharmacy->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('pharmacist.edit-pharmacy', $pharmacy) }}" 
                                           class="btn btn-sm btn-warning" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('pharmacies.show', $pharmacy) }}" 
                                           class="btn btn-sm btn-info" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-store fa-3x text-muted mb-3"></i>
                    <h5 class="text-gray-600">Aucune pharmacie enregistrée</h5>
                    <p class="text-muted">Commencez par ajouter votre première pharmacie</p>
                    <a href="{{ route('pharmacist.create-pharmacy') }}" class="btn btn-success">
                        <i class="fas fa-plus me-2"></i>
                        Ajouter ma première pharmacie
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Activité récente -->
    @if($pharmacies->count() > 0)
    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 fw-bold text-primary">
                        <i class="fas fa-chart-line me-2"></i>
                        Statistiques détaillées
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="h4 mb-0 text-primary">{{ $pharmacies->count() }}</div>
                            <div class="small text-muted">Total pharmacies</div>
                        </div>
                        <div class="col-6">
                            <div class="h4 mb-0 text-success">
                                {{ $pharmacies->where('is_verified', true)->count() }}
                            </div>
                            <div class="small text-muted">Vérifiées</div>
                        </div>
                    </div>
                    <hr>
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="h4 mb-0 text-info">
                                {{ $pharmacies->where('is_active', true)->count() }}
                            </div>
                            <div class="small text-muted">Actives</div>
                        </div>
                        <div class="col-6">
                            <div class="h4 mb-0 text-warning">
                                {{ $pharmacies->where('is_verified', false)->count() }}
                            </div>
                            <div class="small text-muted">En attente</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 fw-bold text-primary">
                        <i class="fas fa-info-circle me-2"></i>
                        Informations du compte
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nom complet</label>
                        <p class="form-control-plaintext">{{ Auth::user()->name }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <p class="form-control-plaintext">{{ Auth::user()->email }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Statut du profil</label>
                        <p class="form-control-plaintext">
                            @if(Auth::user()->profile_completed)
                                <span class="badge bg-success">Complet</span>
                            @else
                                <span class="badge bg-warning text-dark">Incomplet</span>
                            @endif
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Membre depuis</label>
                        <p class="form-control-plaintext">{{ Auth::user()->created_at->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
.avatar-sm {
    width: 32px;
    height: 32px;
    font-size: 14px;
}

.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-start border-success border-3 {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-start border-info border-3 {
    border-left: 0.25rem solid #36b9cc !important;
}

.border-start border-warning border-3 {
    border-left: 0.25rem solid #f6c23e !important;
}

.card {
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
}

.btn-group .btn {
    margin-right: 2px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}

.bg-gradient-to-r {
    background: linear-gradient(to right, #059669, #047857);
}

.text-green-100 {
    color: #dcfce7;
}

.text-green-200 {
    color: #bbf7d0;
}
</style>
@endpush