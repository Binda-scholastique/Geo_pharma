@extends('layouts.app')

@section('title', 'Localisation - Profil Pharmacien')

@section('content')
<div class="container-fluid" style="background-color: #f8f9fa; min-height: 100vh;">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center py-4 px-4 mb-4 bg-white shadow-sm">
        <div>
            <h1 class="h3 mb-0" style="color: #495057;">
                <i class="fas fa-map-marker-alt me-2" style="color: #10b981;"></i>
                Ma Localisation
            </h1>
            <p class="text-muted mb-0">Définissez votre position pour améliorer la recherche de pharmacies</p>
        </div>
        <a href="{{ route('pharmacist.dashboard') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            Retour au Dashboard
        </a>
    </div>

    <div class="row px-4">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 fw-bold text-primary">
                        <i class="fas fa-map me-2"></i>
                        Carte de Localisation
                    </h6>
                </div>
                <div class="card-body">
                    <div id="map" style="height: 400px; border-radius: 0.5rem;"></div>
                    <div class="mt-3">
                        <button type="button" class="btn btn-success" onclick="getCurrentLocation()">
                            <i class="fas fa-crosshairs me-2"></i>
                            Utiliser ma position actuelle
                        </button>
                        <button type="button" class="btn btn-outline-primary ms-2" onclick="clearLocation()">
                            <i class="fas fa-trash me-2"></i>
                            Effacer la position
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 fw-bold text-primary">
                        <i class="fas fa-info-circle me-2"></i>
                        Informations de Localisation
                    </h6>
                </div>
                <div class="card-body">
                    <form id="locationForm" method="POST" action="{{ route('pharmacist.update-location') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="address" class="form-label">Adresse</label>
                            <input type="text" class="form-control" id="address" name="address" 
                                   value="{{ old('address', auth()->user()->address) }}" 
                                   placeholder="Entrez votre adresse complète">
                            @error('address')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="city" class="form-label">Ville</label>
                                <input type="text" class="form-control" id="city" name="city" 
                                       value="{{ old('city', auth()->user()->city) }}" 
                                       placeholder="Ville">
                                @error('city')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="postal_code" class="form-label">Code Postal</label>
                                <input type="text" class="form-control" id="postal_code" name="postal_code" 
                                       value="{{ old('postal_code', auth()->user()->postal_code) }}" 
                                       placeholder="Code postal">
                                @error('postal_code')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="latitude" class="form-label">Latitude</label>
                                <input type="number" step="any" class="form-control" id="latitude" name="latitude" 
                                       value="{{ old('latitude', auth()->user()->latitude) }}" 
                                       placeholder="Latitude" readonly>
                                @error('latitude')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="longitude" class="form-label">Longitude</label>
                                <input type="number" step="any" class="form-control" id="longitude" name="longitude" 
                                       value="{{ old('longitude', auth()->user()->longitude) }}" 
                                       placeholder="Longitude" readonly>
                                @error('longitude')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-2"></i>
                                Sauvegarder la Localisation
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 fw-bold text-primary">
                        <i class="fas fa-lightbulb me-2"></i>
                        Conseils
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Cliquez sur la carte pour définir votre position
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Utilisez le bouton "Utiliser ma position" pour la géolocalisation automatique
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Votre localisation aide les utilisateurs à vous trouver plus facilement
                        </li>
                        <li>
                            <i class="fas fa-check text-success me-2"></i>
                            Les coordonnées sont automatiquement mises à jour
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
let map;
let marker;
let userLocation = null;

// Initialiser la carte
function initMap() {
    // Position par défaut (Paris)
    const defaultLat = {{ auth()->user()->latitude ?? 48.8566 }};
    const defaultLng = {{ auth()->user()->longitude ?? 2.3522 }};
    
    map = L.map('map').setView([defaultLat, defaultLng], 13);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    
    // Ajouter un marqueur si l'utilisateur a déjà une position
    if (defaultLat && defaultLng) {
        marker = L.marker([defaultLat, defaultLng]).addTo(map);
        marker.bindPopup('Votre position actuelle').openPopup();
    }
    
    // Gérer les clics sur la carte
    map.on('click', function(e) {
        const lat = e.latlng.lat;
        const lng = e.latlng.lng;
        
        // Supprimer l'ancien marqueur
        if (marker) {
            map.removeLayer(marker);
        }
        
        // Ajouter un nouveau marqueur
        marker = L.marker([lat, lng]).addTo(map);
        marker.bindPopup('Nouvelle position sélectionnée').openPopup();
        
        // Mettre à jour les champs du formulaire
        document.getElementById('latitude').value = lat.toFixed(8);
        document.getElementById('longitude').value = lng.toFixed(8);
        
        // Géocodage inverse pour obtenir l'adresse
        reverseGeocode(lat, lng);
    });
}

// Géolocalisation automatique
function getCurrentLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                
                // Centrer la carte sur la position
                map.setView([lat, lng], 15);
                
                // Supprimer l'ancien marqueur
                if (marker) {
                    map.removeLayer(marker);
                }
                
                // Ajouter un nouveau marqueur
                marker = L.marker([lat, lng]).addTo(map);
                marker.bindPopup('Votre position actuelle').openPopup();
                
                // Mettre à jour les champs du formulaire
                document.getElementById('latitude').value = lat.toFixed(8);
                document.getElementById('longitude').value = lng.toFixed(8);
                
                // Géocodage inverse
                reverseGeocode(lat, lng);
            },
            function(error) {
                alert('Erreur de géolocalisation: ' + error.message);
            }
        );
    } else {
        alert('La géolocalisation n\'est pas supportée par ce navigateur.');
    }
}

// Effacer la localisation
function clearLocation() {
    if (marker) {
        map.removeLayer(marker);
        marker = null;
    }
    document.getElementById('latitude').value = '';
    document.getElementById('longitude').value = '';
    document.getElementById('address').value = '';
    document.getElementById('city').value = '';
    document.getElementById('postal_code').value = '';
}

// Géocodage inverse
function reverseGeocode(lat, lng) {
    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
        .then(response => response.json())
        .then(data => {
            if (data.display_name) {
                document.getElementById('address').value = data.display_name;
            }
            if (data.address) {
                if (data.address.city || data.address.town || data.address.village) {
                    document.getElementById('city').value = data.address.city || data.address.town || data.address.village;
                }
                if (data.address.postcode) {
                    document.getElementById('postal_code').value = data.address.postcode;
                }
            }
        })
        .catch(error => {
            console.log('Erreur de géocodage:', error);
        });
}

// Initialiser la carte au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    initMap();
});
</script>
@endpush

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush
@endsection
