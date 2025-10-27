@extends('layouts.app')

@section('title', 'Détails Pharmacie - Administration')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-store text-green-600 mr-2"></i>
                Détails de la pharmacie
            </h1>
            <p class="text-muted mb-0">Informations complètes sur {{ $pharmacy->name }}</p>
        </div>
        <div>
            <a href="{{ route('admin.pharmacies.edit', $pharmacy) }}" class="btn btn-warning me-2">
                <i class="fas fa-edit mr-2"></i>
                Modifier
            </a>
            <a href="{{ route('admin.pharmacies') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour à la liste
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Informations principales -->
        <div class="col-lg-8">
            <!-- Informations générales -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle mr-2"></i>
                        Informations générales
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Nom de la pharmacie</label>
                            <p class="form-control-plaintext">{{ $pharmacy->name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Statut</label>
                            <p class="form-control-plaintext">
                                @if($pharmacy->is_active)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-danger">Inactive</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Vérification</label>
                            <p class="form-control-plaintext">
                                @if($pharmacy->is_verified)
                                    <span class="badge badge-success">Vérifiée</span>
                                @else
                                    <span class="badge badge-warning">En attente de vérification</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Date de création</label>
                            <p class="form-control-plaintext">{{ $pharmacy->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Adresse et contact -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-map-marker-alt mr-2"></i>
                        Adresse et contact
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Adresse</label>
                            <p class="form-control-plaintext">{{ $pharmacy->address }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label font-weight-bold">Ville</label>
                            <p class="form-control-plaintext">{{ $pharmacy->city }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label font-weight-bold">Code postal</label>
                            <p class="form-control-plaintext">{{ $pharmacy->postal_code }}</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Téléphone</label>
                            <p class="form-control-plaintext">
                                @if($pharmacy->phone)
                                    <a href="tel:{{ $pharmacy->phone }}" class="text-decoration-none">
                                        {{ $pharmacy->phone }}
                                    </a>
                                @else
                                    <span class="text-muted">Non renseigné</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Email</label>
                            <p class="form-control-plaintext">
                                @if($pharmacy->email)
                                    <a href="mailto:{{ $pharmacy->email }}" class="text-decoration-none">
                                        {{ $pharmacy->email }}
                                    </a>
                                @else
                                    <span class="text-muted">Non renseigné</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    @if($pharmacy->latitude && $pharmacy->longitude)
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="form-label font-weight-bold">Coordonnées GPS</label>
                            <p class="form-control-plaintext">
                                Latitude: {{ $pharmacy->latitude }}, Longitude: {{ $pharmacy->longitude }}
                            </p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Horaires d'ouverture -->
            @if($pharmacy->opening_hours)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-clock mr-2"></i>
                        Horaires d'ouverture
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Jour</th>
                                    <th>Matin</th>
                                    <th>Après-midi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pharmacy->opening_hours as $day => $hours)
                                <tr>
                                    <td class="font-weight-bold">{{ ucfirst($day) }}</td>
                                    <td>
                                        @if(isset($hours['morning']) && $hours['morning'])
                                            {{ $hours['morning']['start'] }} - {{ $hours['morning']['end'] }}
                                        @else
                                            <span class="text-muted">Fermé</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($hours['afternoon']) && $hours['afternoon'])
                                            {{ $hours['afternoon']['start'] }} - {{ $hours['afternoon']['end'] }}
                                        @else
                                            <span class="text-muted">Fermé</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Services -->
            @if($pharmacy->services && count($pharmacy->services) > 0)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-concierge-bell mr-2"></i>
                        Services proposés
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($pharmacy->services as $service)
                        <div class="col-md-6 mb-2">
                            <span class="badge badge-info">{{ $service }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Description -->
            @if($pharmacy->description)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-align-left mr-2"></i>
                        Description
                    </h6>
                </div>
                <div class="card-body">
                    <p class="form-control-plaintext">{{ $pharmacy->description }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Pharmacien responsable -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user-md mr-2"></i>
                        Pharmacien responsable
                    </h6>
                </div>
                <div class="card-body">
                    @if($pharmacy->pharmacist)
                        <div class="text-center">
                            <div class="avatar-lg bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3">
                                {{ substr($pharmacy->pharmacist->name, 0, 1) }}
                            </div>
                            <h5 class="font-weight-bold">{{ $pharmacy->pharmacist->name }}</h5>
                            <p class="text-muted">{{ $pharmacy->pharmacist->email }}</p>
                            <a href="{{ route('admin.users.show', $pharmacy->pharmacist) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye mr-1"></i>
                                Voir le profil
                            </a>
                        </div>
                    @else
                        <div class="text-center">
                            <i class="fas fa-user-slash fa-3x text-gray-300 mb-3"></i>
                            <p class="text-muted">Aucun pharmacien assigné</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bolt mr-2"></i>
                        Actions rapides
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.pharmacies.edit', $pharmacy) }}" class="btn btn-warning">
                            <i class="fas fa-edit mr-2"></i>
                            Modifier la pharmacie
                        </a>
                        
                        <form action="{{ route('admin.pharmacies.toggle-verification', $pharmacy) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn {{ $pharmacy->is_verified ? 'btn-warning' : 'btn-success' }} w-100">
                                <i class="fas {{ $pharmacy->is_verified ? 'fa-times' : 'fa-check' }} mr-2"></i>
                                {{ $pharmacy->is_verified ? 'Désactiver la vérification' : 'Vérifier la pharmacie' }}
                            </button>
                        </form>

                        <form action="{{ route('admin.pharmacies.toggle-status', $pharmacy) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn {{ $pharmacy->is_active ? 'btn-secondary' : 'btn-primary' }} w-100">
                                <i class="fas {{ $pharmacy->is_active ? 'fa-pause' : 'fa-play' }} mr-2"></i>
                                {{ $pharmacy->is_active ? 'Désactiver' : 'Activer' }}
                            </button>
                        </form>

                        <button type="button" class="btn btn-danger" 
                                onclick="confirmDelete('{{ route('admin.pharmacies.destroy', $pharmacy) }}', '{{ $pharmacy->name }}')">
                            <i class="fas fa-trash mr-2"></i>
                            Supprimer la pharmacie
                        </button>
                    </div>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-bar mr-2"></i>
                        Informations
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="h4 mb-0 text-primary">{{ $pharmacy->id }}</div>
                            <div class="small text-muted">ID Pharmacie</div>
                        </div>
                        <div class="col-6">
                            <div class="h4 mb-0 text-success">
                                {{ $pharmacy->created_at->diffInDays(now()) }}
                            </div>
                            <div class="small text-muted">Jours d'existence</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informations système -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-cog mr-2"></i>
                        Informations système
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">ID</label>
                        <p class="form-control-plaintext">{{ $pharmacy->id }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Créée le</label>
                        <p class="form-control-plaintext">{{ $pharmacy->created_at->format('d/m/Y à H:i') }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Dernière mise à jour</label>
                        <p class="form-control-plaintext">{{ $pharmacy->updated_at->format('d/m/Y à H:i') }}</p>
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
                <p>Êtes-vous sûr de vouloir supprimer la pharmacie <strong id="pharmacyName"></strong> ?</p>
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

@push('styles')
<style>
.avatar-lg {
    width: 80px;
    height: 80px;
    font-size: 32px;
}

.card {
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
}
</style>
@endpush

@push('scripts')
<script>
function confirmDelete(url, pharmacyName) {
    document.getElementById('pharmacyName').textContent = pharmacyName;
    document.getElementById('deleteForm').action = url;
    $('#deleteModal').modal('show');
}
</script>
@endpush