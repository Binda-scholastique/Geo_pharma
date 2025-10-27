@extends('layouts.app')

@section('title', 'Gestion des Numéros d\'Autorisation - GeoPharma')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">
            <i class="fas fa-key text-success me-2"></i>
            Gestion des Numéros d'Autorisation
        </h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('admin.authorization-numbers.create') }}" class="btn btn-success">
                <i class="fas fa-plus me-1"></i>Nouveau Numéro
            </a>
        </div>
    </div>

    <!-- Filtres -->
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.authorization-numbers') }}" class="row g-3">
                        <div class="col-md-3">
                            <label for="status" class="form-label">Statut</label>
                            <select name="status" id="status" class="form-select">
                                <option value="">Tous les statuts</option>
                                <option value="valid" {{ request('status') == 'valid' ? 'selected' : '' }}>Valides</option>
                                <option value="invalid" {{ request('status') == 'invalid' ? 'selected' : '' }}>Invalides</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="expired" class="form-label">Expiration</label>
                            <select name="expired" id="expired" class="form-select">
                                <option value="">Tous</option>
                                <option value="expired" {{ request('expired') == 'expired' ? 'selected' : '' }}>Expirés</option>
                                <option value="not_expired" {{ request('expired') == 'not_expired' ? 'selected' : '' }}>Non expirés</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="search" class="form-label">Recherche</label>
                            <input type="text" name="search" id="search" class="form-control" 
                                   placeholder="Numéro, nom..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-1"></i>Filtrer
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des numéros d'autorisation -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list me-2"></i>Liste des Numéros d'Autorisation ({{ $authorizationNumbers->total() }})
            </h6>
        </div>
        <div class="card-body">
            @if($authorizationNumbers->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Numéro</th>
                                <th>Pharmacien</th>
                                <th>Pharmacie</th>
                                <th>Statut</th>
                                <th>Expiration</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($authorizationNumbers as $authNumber)
                            <tr>
                                <td>{{ $authNumber->id }}</td>
                                <td>
                                    <code>{{ $authNumber->number }}</code>
                                </td>
                                <td>{{ $authNumber->pharmacist_name ?? '-' }}</td>
                                <td>{{ $authNumber->pharmacy_name ?? '-' }}</td>
                                <td>
                                    @if($authNumber->is_valid)
                                        <span class="badge bg-success">Valide</span>
                                    @else
                                        <span class="badge bg-danger">Invalide</span>
                                    @endif
                                </td>
                                <td>
                                    @if($authNumber->expires_at)
                                        @if($authNumber->expires_at->isFuture())
                                            <span class="badge bg-success">{{ $authNumber->expires_at->format('d/m/Y') }}</span>
                                        @else
                                            <span class="badge bg-danger">Expiré</span>
                                        @endif
                                    @else
                                        <span class="badge bg-info">Permanent</span>
                                    @endif
                                </td>
                                <td>{{ $authNumber->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.authorization-numbers.edit', $authNumber) }}" 
                                           class="btn btn-sm btn-outline-warning" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <!-- Toggle validité -->
                                        <form method="POST" action="{{ route('admin.authorization-numbers.toggle-validity', $authNumber) }}" 
                                              class="d-inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="btn btn-sm {{ $authNumber->is_valid ? 'btn-outline-danger' : 'btn-outline-success' }}" 
                                                    title="{{ $authNumber->is_valid ? 'Invalider' : 'Valider' }}">
                                                <i class="fas {{ $authNumber->is_valid ? 'fa-times' : 'fa-check' }}"></i>
                                            </button>
                                        </form>
                                        
                                        <!-- Supprimer -->
                                        <form method="POST" action="{{ route('admin.authorization-numbers.destroy', $authNumber) }}" 
                                              class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce numéro d\'autorisation ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
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
                    {{ $authorizationNumbers->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-key fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Aucun numéro d'autorisation trouvé</h5>
                    <p class="text-muted">Aucun numéro d'autorisation ne correspond à vos critères de recherche.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
