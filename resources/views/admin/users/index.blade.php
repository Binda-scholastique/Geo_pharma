@extends('layouts.app')

@section('title', 'Gestion des Utilisateurs - Administration')

@section('content')
<div class="container-fluid" style="background-color: #f8f9fa; min-height: 100vh;">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center py-4 px-4 mb-4 bg-white shadow-sm">
        <div>
            <h1 class="h3 mb-0" style="color: #495057;">
                <i class="fas fa-users me-2" style="color: #10b981;"></i>
                Gestion des Utilisateurs
            </h1>
            <p class="text-muted mb-0">Gérez tous les utilisateurs de la plateforme</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn btn-success">
            <i class="fas fa-plus me-2"></i>
            Nouvel Utilisateur
        </a>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4 px-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-primary border-3 shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col me-2">
                            <div class="small fw-bold text-primary text-uppercase mb-1">
                                Total Utilisateurs
                            </div>
                            <div class="h5 mb-0 fw-bold text-dark">{{ $users->total() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-muted"></i>
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
                                Pharmaciens
                            </div>
                            <div class="h5 mb-0 fw-bold text-dark">{{ $users->where('role', 'pharmacist')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-md fa-2x text-muted"></i>
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
                                Utilisateurs Normaux
                            </div>
                            <div class="h5 mb-0 fw-bold text-dark">{{ $users->where('role', 'user')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user fa-2x text-muted"></i>
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
                                Profils Incomplets
                            </div>
                            <div class="h5 mb-0 fw-bold text-dark">{{ $users->where('profile_completed', false)->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-muted"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et Recherche -->
    <div class="card shadow mb-4 mx-4">
        <div class="card-header py-3">
            <h6 class="m-0 fw-bold text-primary">
                <i class="fas fa-filter me-2"></i>
                Filtres et Recherche
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.users') }}" class="row">
                <div class="col-md-4 mb-3">
                    <label for="search" class="form-label">Rechercher</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="Nom, email...">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="role" class="form-label">Rôle</label>
                    <select class="form-control" id="role" name="role">
                        <option value="">Tous les rôles</option>
                        <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Utilisateur</option>
                        <option value="pharmacist" {{ request('role') == 'pharmacist' ? 'selected' : '' }}>Pharmacien</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Administrateur</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="status" class="form-label">Statut</label>
                    <select class="form-control" id="status" name="status">
                        <option value="">Tous les statuts</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Profil complet</option>
                        <option value="incomplete" {{ request('status') == 'incomplete' ? 'selected' : '' }}>Profil incomplet</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search mr-1"></i>
                        Filtrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des Utilisateurs -->
    <div class="card shadow mb-4 mx-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 fw-bold text-primary">
                <i class="fas fa-list me-2"></i>
                Liste des Utilisateurs
            </h6>
            <span class="badge badge-primary">{{ $users->total() }} utilisateur(s)</span>
        </div>
        <div class="card-body">
            @if($users->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Rôle</th>
                                <th>Statut</th>
                                <th>Date d'inscription</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $user->name }}</div>
                                            @if($user->authorization_number)
                                                <small class="text-muted">Auth: {{ $user->authorization_number }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->role === 'admin')
                                        <span class="badge badge-danger">Administrateur</span>
                                    @elseif($user->role === 'pharmacist')
                                        <span class="badge badge-success">Pharmacien</span>
                                    @else
                                        <span class="badge badge-info">Utilisateur</span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->profile_completed)
                                        <span class="badge badge-success">Complet</span>
                                    @else
                                        <span class="badge badge-warning">Incomplet</span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.users.show', $user) }}" 
                                           class="btn btn-sm btn-info" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.users.edit', $user) }}" 
                                           class="btn btn-sm btn-warning" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.users.destroy', $user) }}" 
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
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
                    {{ $users->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <h5 class="text-secondary">Aucun utilisateur trouvé</h5>
                    <p class="text-muted">Aucun utilisateur ne correspond à vos critères de recherche.</p>
                    <a href="{{ route('admin.users.create') }}" class="btn btn-success">
                        <i class="fas fa-plus me-2"></i>
                        Créer le premier utilisateur
                    </a>
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
</style>
@endpush