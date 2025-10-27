@extends('layouts.app')

@section('title', 'Créer un Numéro d\'Autorisation - GeoPharma')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">
            <i class="fas fa-key text-success me-2"></i>
            Créer un Numéro d'Autorisation
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
                        <i class="fas fa-key me-2"></i>Informations du numéro d'autorisation
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.authorization-numbers.store') }}">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="number" class="form-label">Numéro d'autorisation *</label>
                                <input type="text" class="form-control @error('number') is-invalid @enderror" 
                                       id="number" name="number" value="{{ old('number') }}" required>
                                @error('number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Le numéro doit être unique</div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="expires_at" class="form-label">Date d'expiration</label>
                                <input type="date" class="form-control @error('expires_at') is-invalid @enderror" 
                                       id="expires_at" name="expires_at" value="{{ old('expires_at') }}">
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
                                       id="pharmacist_name" name="pharmacist_name" value="{{ old('pharmacist_name') }}">
                                @error('pharmacist_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="pharmacy_name" class="form-label">Nom de la pharmacie</label>
                                <input type="text" class="form-control @error('pharmacy_name') is-invalid @enderror" 
                                       id="pharmacy_name" name="pharmacy_name" value="{{ old('pharmacy_name') }}">
                                @error('pharmacy_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('admin.authorization-numbers') }}" class="btn btn-secondary me-2">Annuler</a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-1"></i>Créer le numéro
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
                        <i class="fas fa-info-circle me-2"></i>Informations
                    </h6>
                </div>
                <div class="card-body">
                    <h6>À propos des numéros d'autorisation :</h6>
                    <ul class="list-unstyled">
                        <li>• Chaque numéro doit être unique</li>
                        <li>• Ils permettent aux pharmaciens de s'inscrire</li>
                        <li>• Ils peuvent avoir une date d'expiration</li>
                        <li>• Ils peuvent être associés à un pharmacien spécifique</li>
                    </ul>
                    
                    <hr>
                    
                    <h6>Exemples de numéros :</h6>
                    <ul class="list-unstyled text-muted">
                        <li>• PH123456789</li>
                        <li>• AUTH2024001</li>
                        <li>• PHARM2024ABC</li>
                    </ul>
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
                        <strong>Numéros uniques :</strong> Assurez-vous que le numéro d'autorisation n'existe pas déjà dans le système.
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Expiration :</strong> Les numéros expirés ne peuvent plus être utilisés pour l'inscription.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
