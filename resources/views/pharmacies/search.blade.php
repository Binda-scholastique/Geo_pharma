@extends('layouts.app')

@section('title', 'Recherche de Pharmacies - GeoPharma')

@section('content')
<div class="container-fluid" style="background-color: #f8f9fa; min-height: 100vh;">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center py-4 px-4 mb-4 bg-white shadow-sm">
        <div>
            <h1 class="h3 mb-0" style="color: #495057;">
                <i class="fas fa-search me-2" style="color: #10b981;"></i>
                Recherche de Pharmacies
            </h1>
            <p class="text-muted mb-0">Trouvez les pharmacies près de vous</p>
        </div>
        <a href="{{ route('pharmacies.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            Retour à la carte
        </a>
    </div>

    <div class="row px-4">
        <!-- Filtres de recherche -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 fw-bold text-primary">
                        <i class="fas fa-filter me-2"></i>
                        Filtres de Recherche
                    </h6>
                </div>
                <div class="card-body">
                    <form id="searchForm" method="GET" action="{{ route('pharmacies.search') }}">
                        <!-- Type de recherche -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Type de recherche</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="search_type" id="proximity" value="proximity" {{ request('search_type') == 'proximity' ? 'checked' : '' }}>
                                <label class="form-check-label" for="proximity">
                                    <i class="fas fa-map-marker-alt me-1"></i>
                                    Par proximité
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="search_type" id="city" value="city" {{ request('search_type') == 'city' ? 'checked' : '' }}>
                                <label class="form-check-label" for="city">
                                    <i class="fas fa-city me-1"></i>
                                    Par ville
                                </label>
                            </div>
                        </div>

                        <!-- Recherche par proximité -->
                        <div id="proximitySearch" class="search-type-content">
                            <div class="mb-3">
                                <label for="radius" class="form-label">Rayon de recherche</label>
                                <select class="form-select" id="radius" name="radius">
                                    <option value="1" {{ request('radius') == '1' ? 'selected' : '' }}>1 km</option>
                                    <option value="2" {{ request('radius') == '2' ? 'selected' : '' }}>2 km</option>
                                    <option value="5" {{ request('radius') == '5' ? 'selected' : '' }}>5 km</option>
                                    <option value="10" {{ request('radius') == '10' ? 'selected' : '' }}>10 km</option>
                                    <option value="20" {{ request('radius') == '20' ? 'selected' : '' }}>20 km</option>
                                    <option value="50" {{ request('radius') == '50' ? 'selected' : '' }}>50 km</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <button type="button" class="btn btn-success w-100" onclick="getCurrentLocation()">
                                    <i class="fas fa-crosshairs me-2"></i>
                                    Utiliser ma position
                                </button>
                            </div>
                            
                            <div class="mb-3">
                                <label for="custom_address" class="form-label">Ou saisir une adresse</label>
                                <input type="text" class="form-control" id="custom_address" name="custom_address" 
                                       value="{{ request('custom_address') }}" placeholder="Entrez une adresse">
                            </div>
                            
                            <input type="hidden" id="latitude" name="latitude" value="{{ request('latitude') }}">
                            <input type="hidden" id="longitude" name="longitude" value="{{ request('longitude') }}">
                        </div>

                        <!-- Recherche par ville -->
                        <div id="citySearch" class="search-type-content" style="display: none;">
                            <div class="mb-3">
                                <label for="search_city" class="form-label">Ville</label>
                                <input type="text" class="form-control" id="search_city" name="search_city" 
                                       value="{{ request('search_city') }}" placeholder="Entrez le nom de la ville">
                            </div>
                        </div>

                        <!-- Filtres supplémentaires -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Services</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="services[]" value="delivery" id="delivery" {{ in_array('delivery', request('services', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="delivery">
                                    <i class="fas fa-truck me-1"></i>
                                    Livraison à domicile
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="services[]" value="emergency" id="emergency" {{ in_array('emergency', request('services', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="emergency">
                                    <i class="fas fa-ambulance me-1"></i>
                                    Service d'urgence
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="services[]" value="vaccination" id="vaccination" {{ in_array('vaccination', request('services', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="vaccination">
                                    <i class="fas fa-syringe me-1"></i>
                                    Vaccination
                                </label>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-search me-2"></i>
                                Rechercher
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Résultats -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-primary">
                        <i class="fas fa-list me-2"></i>
                        Résultats de la recherche
                        @if(isset($pharmacies) && $pharmacies->count() > 0)
                            <span class="badge bg-success ms-2">{{ $pharmacies->count() }} pharmacie(s) trouvée(s)</span>
                        @endif
                    </h6>
                </div>
                <div class="card-body">
                    @if(isset($pharmacies) && $pharmacies->count() > 0)
                        <div class="row">
                            @foreach($pharmacies as $pharmacy)
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100 border-0 shadow-sm">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h5 class="card-title mb-0">{{ $pharmacy->name }}</h5>
                                                @if(isset($pharmacy->distance))
                                                    <span class="badge bg-primary">{{ number_format($pharmacy->distance, 1) }} km</span>
                                                @endif
                                            </div>
                                            
                                            <p class="card-text text-muted small mb-2">
                                                <i class="fas fa-map-marker-alt me-1"></i>
                                                {{ $pharmacy->address }}, {{ $pharmacy->city }}
                                            </p>
                                            
                                            @if($pharmacy->phone)
                                                <p class="card-text small mb-2">
                                                    <i class="fas fa-phone me-1"></i>
                                                    {{ $pharmacy->phone }}
                                                </p>
                                            @endif
                                            
                                            @if($pharmacy->services && count($pharmacy->services) > 0)
                                                <div class="mb-2">
                                                    @foreach($pharmacy->services as $service)
                                                        <span class="badge bg-light text-dark me-1">{{ $service }}</span>
                                                    @endforeach
                                                </div>
                                            @endif
                                            
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    @if($pharmacy->is_verified)
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-check me-1"></i>Vérifiée
                                                        </span>
                                                    @endif
                                                </div>
                                                <div>
                                                    <a href="{{ route('pharmacies.show', $pharmacy) }}" class="btn btn-outline-primary btn-sm">
                                                        <i class="fas fa-eye me-1"></i>Voir
                                                    </a>
                                                    @if($pharmacy->whatsapp_number)
                                                        <a href="{{ $pharmacy->whatsapp_url }}" target="_blank" class="btn btn-success btn-sm">
                                                            <i class="fab fa-whatsapp me-1"></i>WhatsApp
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @elseif(request()->hasAny(['latitude', 'longitude', 'search_city']))
                        <div class="text-center py-5">
                            <i class="fas fa-search fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucune pharmacie trouvée</h5>
                            <p class="text-muted">Essayez d'élargir votre recherche ou de modifier les filtres.</p>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-map-marker-alt fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Recherchez des pharmacies</h5>
                            <p class="text-muted">Utilisez les filtres à gauche pour commencer votre recherche.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Gérer l'affichage des types de recherche
document.addEventListener('DOMContentLoaded', function() {
    const proximityRadio = document.getElementById('proximity');
    const cityRadio = document.getElementById('city');
    const proximitySearch = document.getElementById('proximitySearch');
    const citySearch = document.getElementById('citySearch');
    
    function toggleSearchType() {
        if (proximityRadio.checked) {
            proximitySearch.style.display = 'block';
            citySearch.style.display = 'none';
        } else {
            proximitySearch.style.display = 'none';
            citySearch.style.display = 'block';
        }
    }
    
    proximityRadio.addEventListener('change', toggleSearchType);
    cityRadio.addEventListener('change', toggleSearchType);
    
    // Initialiser l'affichage
    toggleSearchType();
});

// Géolocalisation automatique
function getCurrentLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                
                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;
                
                // Soumettre automatiquement le formulaire
                document.getElementById('searchForm').submit();
            },
            function(error) {
                alert('Erreur de géolocalisation: ' + error.message);
            }
        );
    } else {
        alert('La géolocalisation n\'est pas supportée par ce navigateur.');
    }
}

// Géocodage pour l'adresse personnalisée
document.getElementById('custom_address').addEventListener('blur', function() {
    const address = this.value;
    if (address) {
        // Utiliser l'API de géocodage (ici on utilise Nominatim d'OpenStreetMap)
        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}&limit=1`)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    document.getElementById('latitude').value = data[0].lat;
                    document.getElementById('longitude').value = data[0].lon;
                }
            })
            .catch(error => {
                console.log('Erreur de géocodage:', error);
            });
    }
});
</script>
@endpush
@endsection
