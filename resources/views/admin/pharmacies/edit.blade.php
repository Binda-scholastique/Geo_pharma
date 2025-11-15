@extends('layouts.app')

@section('title', 'Modifier Pharmacie - Administration')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-store-edit text-green-600 mr-2"></i>
                Modifier la pharmacie
            </h1>
            <p class="text-muted mb-0">Modifier les informations de {{ $pharmacy->name }}</p>
        </div>
        <div>
            <a href="{{ route('admin.pharmacies.show', $pharmacy->id) }}" class="btn btn-info me-2">
                <i class="fas fa-eye mr-2"></i>
                Voir les détails
            </a>
            <a href="{{ route('admin.pharmacies') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour à la liste
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Formulaire -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-edit mr-2"></i>
                        Modifier les informations
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.pharmacies.update', $pharmacy->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <!-- Informations générales -->
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-info-circle mr-2"></i>
                            Informations générales
                        </h6>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nom de la pharmacie <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $pharmacy->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="pharmacist_id" class="form-label">Pharmacien responsable</label>
                                <select class="form-control @error('pharmacist_id') is-invalid @enderror" 
                                        id="pharmacist_id" name="pharmacist_id">
                                    <option value="">Sélectionner un pharmacien</option>
                                    @foreach(\App\Models\User::where('role', 'pharmacist')->get() as $pharmacist)
                                        <option value="{{ $pharmacist->id }}" 
                                                {{ old('pharmacist_id', $pharmacy->pharmacist_id) == $pharmacist->id ? 'selected' : '' }}>
                                            {{ $pharmacist->name }} ({{ $pharmacist->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('pharmacist_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_active" 
                                           name="is_active" value="1" 
                                           {{ old('is_active', $pharmacy->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Pharmacie active
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_verified" 
                                           name="is_verified" value="1" 
                                           {{ old('is_verified', $pharmacy->is_verified) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_verified">
                                        Pharmacie vérifiée
                                    </label>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Adresse et contact -->
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            Adresse et contact
                        </h6>

                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="address" class="form-label">Adresse <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('address') is-invalid @enderror" 
                                       id="address" name="address" value="{{ old('address', $pharmacy->address) }}" required>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="postal_code" class="form-label">Code postal <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('postal_code') is-invalid @enderror" 
                                       id="postal_code" name="postal_code" value="{{ old('postal_code', $pharmacy->postal_code) }}" required>
                                @error('postal_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="city" class="form-label">Ville <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                       id="city" name="city" value="{{ old('city', $pharmacy->city) }}" required>
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Téléphone</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone', $pharmacy->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $pharmacy->email) }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="website" class="form-label">Site web</label>
                                <input type="url" class="form-control @error('website') is-invalid @enderror" 
                                       id="website" name="website" value="{{ old('website', $pharmacy->website) }}">
                                @error('website')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="latitude" class="form-label">Latitude</label>
                                <input type="number" step="any" class="form-control @error('latitude') is-invalid @enderror" 
                                       id="latitude" name="latitude" value="{{ old('latitude', $pharmacy->latitude) }}">
                                @error('latitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="longitude" class="form-label">Longitude</label>
                                <input type="number" step="any" class="form-control @error('longitude') is-invalid @enderror" 
                                       id="longitude" name="longitude" value="{{ old('longitude', $pharmacy->longitude) }}">
                                @error('longitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr>

                        <!-- Description -->
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-align-left mr-2"></i>
                            Description
                        </h6>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4">{{ old('description', $pharmacy->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('admin.pharmacies.show', $pharmacy->id) }}" class="btn btn-secondary me-2">
                                <i class="fas fa-times mr-2"></i>
                                Annuler
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save mr-2"></i>
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
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle mr-2"></i>
                        Informations actuelles
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Nom</label>
                        <p class="form-control-plaintext">{{ $pharmacy->name }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Pharmacien</label>
                        <p class="form-control-plaintext">
                            @if($pharmacy->pharmacist)
                                {{ $pharmacy->pharmacist->name }}
                            @else
                                <span class="text-muted">Non assigné</span>
                            @endif
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Statut</label>
                        <p class="form-control-plaintext">
                            @if($pharmacy->is_active)
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-danger">Inactive</span>
                            @endif
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Vérification</label>
                        <p class="form-control-plaintext">
                            @if($pharmacy->is_verified)
                                <span class="badge badge-success">Vérifiée</span>
                            @else
                                <span class="badge badge-warning">En attente</span>
                            @endif
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Créée le</label>
                        <p class="form-control-plaintext">{{ $pharmacy->created_at ? $pharmacy->created_at->format('d/m/Y à H:i') : '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bolt mr-2"></i>
                        Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.pharmacies.show', $pharmacy->id) }}" class="btn btn-info">
                            <i class="fas fa-eye mr-2"></i>
                            Voir les détails
                        </a>
                        
                        <form action="{{ route('admin.pharmacies.toggle-verification', $pharmacy->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn {{ $pharmacy->is_verified ? 'btn-warning' : 'btn-success' }} w-100">
                                <i class="fas {{ $pharmacy->is_verified ? 'fa-times' : 'fa-check' }} mr-2"></i>
                                {{ $pharmacy->is_verified ? 'Désactiver la vérification' : 'Vérifier la pharmacie' }}
                            </button>
                        </form>

                        <form action="{{ route('admin.pharmacies.toggle-status', $pharmacy->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn {{ $pharmacy->is_active ? 'btn-secondary' : 'btn-primary' }} w-100">
                                <i class="fas {{ $pharmacy->is_active ? 'fa-pause' : 'fa-play' }} mr-2"></i>
                                {{ $pharmacy->is_active ? 'Désactiver' : 'Activer' }}
                            </button>
                        </form>

                        <button type="button" class="btn btn-danger" 
                                onclick="confirmDelete('{{ route('admin.pharmacies.destroy', $pharmacy->id) }}', '{{ $pharmacy->name }}')">
                            <i class="fas fa-trash mr-2"></i>
                            Supprimer la pharmacie
                        </button>
                    </div>
                </div>
            </div>

            <!-- Aide -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-question-circle mr-2"></i>
                        Aide
                    </h6>
                </div>
                <div class="card-body">
                    <h6>Champs obligatoires :</h6>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-check text-success mr-1"></i> Nom de la pharmacie</li>
                        <li><i class="fas fa-check text-success mr-1"></i> Adresse</li>
                        <li><i class="fas fa-check text-success mr-1"></i> Code postal</li>
                        <li><i class="fas fa-check text-success mr-1"></i> Ville</li>
                    </ul>

                    <hr>

                    <h6>Notes :</h6>
                    <p class="small text-muted">
                        Les coordonnées GPS (latitude/longitude) sont utilisées pour la géolocalisation. 
                        Vous pouvez les laisser vides si elles ne sont pas connues.
                    </p>
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

@push('scripts')
<script>
function confirmDelete(url, pharmacyName) {
    document.getElementById('pharmacyName').textContent = pharmacyName;
    document.getElementById('deleteForm').action = url;
    $('#deleteModal').modal('show');
}
</script>
@endpush