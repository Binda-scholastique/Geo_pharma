@extends('layouts.app')

@section('title', 'Gestion des Pharmacies - Administration')

@section('content')
<div class="container-fluid" style="background-color: #f8f9fa; min-height: 100vh;">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center py-4 px-4 mb-4 bg-white shadow-sm">
        <div>
            <h1 class="h3 mb-0 text-dark">
                <i class="fas fa-store text-success me-2"></i>
                Gestion des Pharmacies
            </h1>
            <p class="text-muted mb-0">Gérez toutes les pharmacies de la plateforme</p>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-primary border-3 shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col me-2">
                            <div class="small fw-bold text-primary text-uppercase mb-1">
                                Total Pharmacies
                            </div>
                            <div class="h5 mb-0 fw-bold text-dark">{{ $pharmacies->total() }}</div>
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
                                Pharmacies Vérifiées
                            </div>
                            <div class="h5 mb-0 fw-bold text-dark">{{ $pharmacies->where('is_verified', true)->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-muted"></i>
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

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-info border-3 shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col me-2">
                            <div class="small fw-bold text-info text-uppercase mb-1">
                                Pharmacies Actives
                            </div>
                            <div class="h5 mb-0 fw-bold text-dark">{{ $pharmacies->where('is_active', true)->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-power-off fa-2x text-muted"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et Recherche -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 fw-bold text-primary">
                <i class="fas fa-filter me-2"></i>
                Filtres et Recherche
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.pharmacies') }}" class="row">
                <div class="col-md-3 mb-3">
                    <label for="search" class="form-label">Rechercher</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="Nom, adresse, ville...">
                </div>
                <div class="col-md-2 mb-3">
                    <label for="status" class="form-label">Statut</label>
                    <select class="form-control" id="status" name="status">
                        <option value="">Tous</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actives</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactives</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label for="verification" class="form-label">Vérification</label>
                    <select class="form-control" id="verification" name="verification">
                        <option value="">Toutes</option>
                        <option value="verified" {{ request('verification') == 'verified' ? 'selected' : '' }}>Vérifiées</option>
                        <option value="pending" {{ request('verification') == 'pending' ? 'selected' : '' }}>En attente</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label for="city" class="form-label">Ville</label>
                    <input type="text" class="form-control" id="city" name="city" 
                           value="{{ request('city') }}" placeholder="Ville...">
                </div>
                <div class="col-md-2 mb-3">
                    <label for="pharmacist" class="form-label">Pharmacien</label>
                    <select class="form-control" id="pharmacist" name="pharmacist">
                        <option value="">Tous</option>
                        @foreach(\App\Models\User::where('role', 'pharmacist')->get() as $pharmacist)
                            <option value="{{ $pharmacist->id }}" {{ request('pharmacist') == $pharmacist->id ? 'selected' : '' }}>
                                {{ $pharmacist->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1 mb-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des Pharmacies -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 fw-bold text-primary">
                <i class="fas fa-list me-2"></i>
                Liste des Pharmacies
            </h6>
            <span class="badge bg-primary">{{ $pharmacies->total() }} pharmacie(s)</span>
        </div>
        <div class="card-body">
            @if($pharmacies->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Adresse</th>
                                <th>Pharmacien</th>
                                <th>Statut</th>
                                <th>Vérifiée</th>
                                <th>Date création</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pharmacies as $pharmacy)
                            <tr>
                                <td>{{ $pharmacy->id }}</td>
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
                                <td>
                                    <div>
                                        <div>{{ $pharmacy->address }}</div>
                                        <small class="text-muted">{{ $pharmacy->city }}, {{ $pharmacy->postal_code }}</small>
                                    </div>
                                </td>
                                <td>
                                    @if($pharmacy->pharmacist)
                                        <div>
                                            <div class="fw-bold">{{ $pharmacy->pharmacist->name }}</div>
                                            <small class="text-muted">{{ $pharmacy->pharmacist->email }}</small>
                                        </div>
                                    @else
                                        <span class="text-muted">Non assigné</span>
                                    @endif
                                </td>
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
                                        <a href="{{ route('admin.pharmacies.show', $pharmacy) }}" 
                                           class="btn btn-sm btn-info" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.pharmacies.edit', $pharmacy) }}" 
                                           class="btn btn-sm btn-warning" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.pharmacies.toggle-verification', $pharmacy) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="btn btn-sm {{ $pharmacy->is_verified ? 'btn-warning' : 'btn-success' }}" 
                                                    title="{{ $pharmacy->is_verified ? 'Désactiver' : 'Vérifier' }}">
                                                <i class="fas {{ $pharmacy->is_verified ? 'fa-times' : 'fa-check' }}"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.pharmacies.toggle-status', $pharmacy) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="btn btn-sm {{ $pharmacy->is_active ? 'btn-secondary' : 'btn-primary' }}" 
                                                    title="{{ $pharmacy->is_active ? 'Désactiver' : 'Activer' }}">
                                                <i class="fas {{ $pharmacy->is_active ? 'fa-pause' : 'fa-play' }}"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.pharmacies.destroy', $pharmacy) }}" 
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette pharmacie ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $pharmacies->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-store fa-3x text-muted mb-3"></i>
                    <h5 class="text-secondary">Aucune pharmacie trouvée</h5>
                    <p class="text-muted">Aucune pharmacie ne correspond à vos critères de recherche.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.avatar-sm {
    width: 32px;
    height: 32px;
    font-size: 14px;
}

.border-start border-primary border-3 {
    border-left: 0.25rem solid #4e73df !important;
}

.border-start border-success border-3 {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-start border-warning border-3 {
    border-left: 0.25rem solid #f6c23e !important;
}

.border-start border-info border-3 {
    border-left: 0.25rem solid #36b9cc !important;
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
</style>
@endpush