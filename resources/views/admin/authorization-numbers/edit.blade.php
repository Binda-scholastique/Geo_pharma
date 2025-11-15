@extends('layouts.app')

@section('title', 'Modifier Numéro d\'Autorisation - GeoPharma')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">
            <i class="fas fa-key text-success me-2"></i>
            Modifier le numéro d'autorisation
        </h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('admin.authorization-numbers') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Retour
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-key me-2"></i>Modifier les informations
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.authorization-numbers.update', $authorizationNumber) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="number" class="form-label">Numéro d'autorisation *</label>
                                <input type="text" class="form-control @error('number') is-invalid @enderror" 
                                       id="number" name="number" value="{{ old('number', $authorizationNumber->number) }}" required>
                                @error('number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Le numéro doit être unique</div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="expires_at" class="form-label">Date d'expiration</label>
                                <input type="date" class="form-control @error('expires_at') is-invalid @enderror" 
                                       id="expires_at" name="expires_at" 
                                       value="{{ old('expires_at', $authorizationNumber->expires_at?->format('Y-m-d')) }}">
                                @error('expires_at')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Laisser vide pour un numéro permanent</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="pharmacist_name" class="form-label">Nom du pharmacien</label>
                                <input type="text" class="form-control @error('pharmacist_name') is-invalid @enderror" 
                                       id="pharmacist_name" name="pharmacist_name" 
                                       value="{{ old('pharmacist_name', $authorizationNumber->pharmacist_name) }}">
                                @error('pharmacist_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="pharmacy_name" class="form-label">Nom de la pharmacie</label>
                                <input type="text" class="form-control @error('pharmacy_name') is-invalid @enderror" 
                                       id="pharmacy_name" name="pharmacy_name" 
                                       value="{{ old('pharmacy_name', $authorizationNumber->pharmacy_name) }}">
                                @error('pharmacy_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_valid" 
                                       name="is_valid" value="1" 
                                       {{ old('is_valid', $authorizationNumber->is_valid) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_valid">
                                    Numéro valide
                                </label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('admin.authorization-numbers') }}" class="btn btn-secondary me-2">Annuler</a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-1"></i>Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle me-2"></i>Informations actuelles
                    </h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td><strong>ID :</strong></td>
                            <td>{{ $authorizationNumber->id }}</td>
                        </tr>
                        <tr>
                            <td><strong>Statut :</strong></td>
                            <td>
                                @if($authorizationNumber->is_valid)
                                    <span class="badge bg-success">Valide</span>
                                @else
                                    <span class="badge bg-danger">Invalide</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Expiration :</strong></td>
                            <td>
                                @if($authorizationNumber->expires_at)
                                    @if($authorizationNumber->expires_at->isFuture())
                                        <span class="badge bg-success">{{ $authorizationNumber->expires_at->format('d/m/Y') }}</span>
                                    @else
                                        <span class="badge bg-danger">Expiré</span>
                                    @endif
                                @else
                                    <span class="badge bg-info">Permanent</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Création :</strong></td>
                            <td>{{ $authorizationNumber->created_at ? $authorizationNumber->created_at->format('d/m/Y') : '-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Modification :</strong></td>
                            <td>{{ $authorizationNumber->updated_at ? $authorizationNumber->updated_at->format('d/m/Y H:i') : '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-exclamation-triangle me-2"></i>Attention
                    </h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Modification du numéro :</strong> Changer le numéro peut affecter les pharmaciens qui l'utilisent.
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Statut invalide :</strong> Les numéros invalides ne peuvent plus être utilisés pour l'inscription.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
