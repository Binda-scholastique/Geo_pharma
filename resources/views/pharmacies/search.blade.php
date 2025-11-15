@extends('layouts.app')

@section('title', 'Recherche de Pharmacies - GeoPharma')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-green-50">
    <!-- Header avec gradient -->
    <div class="bg-gradient-to-r from-green-600 to-green-700 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-white">
                        <i class="fas fa-search mr-3"></i>
                        Recherche de Pharmacies
                    </h1>
                    <p class="text-green-100 mt-2 text-lg">Trouvez les pharmacies près de vous</p>
                </div>
                <a href="{{ route('pharmacies.index') }}" class="bg-white text-green-700 px-4 py-2 rounded-lg hover:bg-green-50 transition-colors duration-200 font-medium shadow-md">
                    <i class="fas fa-arrow-left mr-2"></i>Retour à la carte
                </a>
            </div>
        </div>
    </div>

    <!-- Breadcrumb -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <nav class="flex items-center space-x-2 text-sm">
                <a href="{{ route('pharmacies.index') }}" class="text-green-600 hover:text-green-800 transition-colors">
                    <i class="fas fa-home mr-1"></i>Accueil
                </a>
                <i class="fas fa-chevron-right text-gray-400"></i>
                <span class="text-gray-600 font-medium">Recherche</span>
            </nav>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Filtres de recherche -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-lg p-6 sticky top-4">
                    <h3 class="text-xl font-bold text-gray-800 mb-6">
                        <i class="fas fa-filter text-green-500 mr-2"></i>Filtres de Recherche
                    </h3>
                    <form id="searchForm" method="GET" action="{{ route('pharmacies.search') }}" class="space-y-6">
                        <!-- Type de recherche -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">Type de recherche</label>
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <input type="radio" name="search_type" id="proximity" value="proximity" 
                                           class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300" 
                                           {{ request('search_type') == 'proximity' || !request('search_type') ? 'checked' : '' }}>
                                    <label for="proximity" class="ml-2 block text-sm text-gray-700 cursor-pointer">
                                        <i class="fas fa-map-marker-alt mr-1 text-green-500"></i>Par proximité
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio" name="search_type" id="city" value="city" 
                                           class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300" 
                                           {{ request('search_type') == 'city' ? 'checked' : '' }}>
                                    <label for="city" class="ml-2 block text-sm text-gray-700 cursor-pointer">
                                        <i class="fas fa-city mr-1 text-blue-500"></i>Par ville
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio" name="search_type" id="name" value="name" 
                                           class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300" 
                                           {{ request('search_type') == 'name' ? 'checked' : '' }}>
                                    <label for="name" class="ml-2 block text-sm text-gray-700 cursor-pointer">
                                        <i class="fas fa-store mr-1 text-purple-500"></i>Par nom
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Recherche par proximité -->
                        <div id="proximitySearch" class="search-type-content space-y-4">
                            <div>
                                <label for="radius" class="block text-sm font-medium text-gray-700 mb-2">Rayon de recherche</label>
                                <select id="radius" name="radius" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                    <option value="1" {{ request('radius') == '1' ? 'selected' : '' }}>1 km</option>
                                    <option value="2" {{ request('radius') == '2' ? 'selected' : '' }}>2 km</option>
                                    <option value="5" {{ request('radius') == '5' ? 'selected' : '' }}>5 km</option>
                                    <option value="10" {{ request('radius') == '10' || !request('radius') ? 'selected' : '' }}>10 km</option>
                                    <option value="20" {{ request('radius') == '20' ? 'selected' : '' }}>20 km</option>
                                    <option value="50" {{ request('radius') == '50' ? 'selected' : '' }}>50 km</option>
                                </select>
                            </div>
                            
                            <div>
                                <button type="button" onclick="getCurrentLocation()" class="w-full bg-green-500 text-white px-4 py-3 rounded-lg hover:bg-green-600 transition-colors duration-200 flex items-center justify-center">
                                    <i class="fas fa-crosshairs mr-2"></i>Utiliser ma position
                                </button>
                            </div>
                            
                            <div>
                                <label for="custom_address" class="block text-sm font-medium text-gray-700 mb-2">Ou saisir une adresse</label>
                                <input type="text" id="custom_address" name="custom_address" 
                                       value="{{ request('custom_address') }}" 
                                       placeholder="Ex: Avenue Kasa-Vubu, Kinshasa"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            </div>
                            
                            <input type="hidden" id="latitude" name="latitude" value="{{ request('latitude') }}">
                            <input type="hidden" id="longitude" name="longitude" value="{{ request('longitude') }}">
                        </div>

                        <!-- Recherche par ville -->
                        <div id="citySearch" class="search-type-content" style="display: none;">
                            <div>
                                <label for="search_city" class="block text-sm font-medium text-gray-700 mb-2">Ville</label>
                                <input type="text" id="search_city" name="search_city" 
                                       value="{{ request('search_city') }}" 
                                       placeholder="Ex: Kinshasa, Lubumbashi..."
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            </div>
                        </div>

                        <!-- Recherche par nom -->
                        <div id="nameSearch" class="search-type-content" style="display: none;">
                            <div>
                                <label for="search_name" class="block text-sm font-medium text-gray-700 mb-2">Nom de la pharmacie</label>
                                <input type="text" id="search_name" name="search_name" 
                                       value="{{ request('search_name') }}" 
                                       placeholder="Ex: Pharmacie du Centre"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            </div>
                        </div>

                        <!-- Filtres supplémentaires -->
                        <div class="pt-4 border-t border-gray-200">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Services</label>
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <input type="checkbox" name="services[]" value="delivery" id="delivery" 
                                           class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded" 
                                           {{ in_array('delivery', request('services', [])) ? 'checked' : '' }}>
                                    <label for="delivery" class="ml-2 block text-sm text-gray-700 cursor-pointer">
                                        <i class="fas fa-truck mr-1 text-blue-500"></i>Livraison à domicile
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" name="services[]" value="emergency" id="emergency" 
                                           class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded" 
                                           {{ in_array('emergency', request('services', [])) ? 'checked' : '' }}>
                                    <label for="emergency" class="ml-2 block text-sm text-gray-700 cursor-pointer">
                                        <i class="fas fa-ambulance mr-1 text-red-500"></i>Service d'urgence
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" name="services[]" value="vaccination" id="vaccination" 
                                           class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded" 
                                           {{ in_array('vaccination', request('services', [])) ? 'checked' : '' }}>
                                    <label for="vaccination" class="ml-2 block text-sm text-gray-700 cursor-pointer">
                                        <i class="fas fa-syringe mr-1 text-green-500"></i>Vaccination
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-gray-200">
                            <button type="submit" class="w-full bg-green-500 text-white px-6 py-3 rounded-lg hover:bg-green-600 transition-colors duration-200 flex items-center justify-center">
                                <i class="fas fa-search mr-2"></i>Rechercher
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Résultats -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                        <h3 class="text-xl font-bold text-gray-800">
                            <i class="fas fa-list text-green-500 mr-2"></i>Résultats de la recherche
                        </h3>
                        @if(isset($pharmacies) && $pharmacies->count() > 0)
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">{{ $pharmacies->count() }} pharmacie(s) trouvée(s)</span>
                        @endif
                    </div>
                    <div class="p-6">
                        @if(isset($pharmacies) && $pharmacies->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @foreach($pharmacies as $pharmacy)
                                    <div class="bg-white border border-gray-200 rounded-xl p-6 hover:shadow-lg transition-shadow duration-300">
                                        <div class="flex items-start justify-between mb-4">
                                            <div class="flex-1">
                                                <h4 class="text-lg font-bold text-gray-900 mb-1">{{ $pharmacy->name }}</h4>
                                                @if(isset($pharmacy->distance))
                                                    <span class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-medium">
                                                        <i class="fas fa-map-marker-alt mr-1"></i>{{ number_format($pharmacy->distance, 1) }} km
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="space-y-2 mb-4">
                                            <p class="text-sm text-gray-600 flex items-center">
                                                <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>
                                                {{ $pharmacy->address }}, {{ $pharmacy->city }}
                                            </p>
                                            
                                            @if($pharmacy->phone)
                                                <p class="text-sm text-gray-600 flex items-center">
                                                    <i class="fas fa-phone text-green-500 mr-2"></i>
                                                    {{ $pharmacy->phone }}
                                                </p>
                                            @endif
                                            
                                            @if($pharmacy->services && count($pharmacy->services) > 0)
                                                <div class="flex flex-wrap gap-1 mt-2">
                                                    @foreach($pharmacy->services as $service)
                                                        <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs">{{ $service }}</span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                                            <div>
                                                @if($pharmacy->is_verified)
                                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-medium">
                                                        <i class="fas fa-check mr-1"></i>Vérifiée
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <a href="{{ route('pharmacies.show', $pharmacy->id) }}" 
                                                   class="px-3 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors text-sm">
                                                    <i class="fas fa-eye mr-1"></i>Voir
                                                </a>
                                                @if($pharmacy->whatsapp_number)
                                                    <a href="{{ $pharmacy->whatsapp_url }}" 
                                                       target="_blank" 
                                                       class="px-3 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors text-sm">
                                                        <i class="fab fa-whatsapp mr-1"></i>WhatsApp
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @elseif(request()->hasAny(['latitude', 'longitude', 'search_city', 'search_name']))
                            <div class="text-center py-12">
                                <i class="fas fa-search text-gray-300 text-6xl mb-4"></i>
                                <h5 class="text-gray-500 text-lg font-medium mb-2">Aucune pharmacie trouvée</h5>
                                <p class="text-gray-400">Essayez d'élargir votre recherche ou de modifier les filtres.</p>
                            </div>
                        @else
                            <div class="text-center py-12">
                                <i class="fas fa-map-marker-alt text-gray-300 text-6xl mb-4"></i>
                                <h5 class="text-gray-500 text-lg font-medium mb-2">Recherchez des pharmacies</h5>
                                <p class="text-gray-400">Utilisez les filtres à gauche pour commencer votre recherche.</p>
                            </div>
                        @endif
                    </div>
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
    const nameRadio = document.getElementById('name');
    const proximitySearch = document.getElementById('proximitySearch');
    const citySearch = document.getElementById('citySearch');
    const nameSearch = document.getElementById('nameSearch');
    
    function toggleSearchType() {
        // Masquer tous les contenus
        proximitySearch.style.display = 'none';
        citySearch.style.display = 'none';
        nameSearch.style.display = 'none';
        
        // Afficher le contenu correspondant
        if (proximityRadio.checked) {
            proximitySearch.style.display = 'block';
        } else if (cityRadio.checked) {
            citySearch.style.display = 'block';
        } else if (nameRadio.checked) {
            nameSearch.style.display = 'block';
        }
    }
    
    proximityRadio.addEventListener('change', toggleSearchType);
    cityRadio.addEventListener('change', toggleSearchType);
    nameRadio.addEventListener('change', toggleSearchType);
    
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
const customAddressInput = document.getElementById('custom_address');
if (customAddressInput) {
    customAddressInput.addEventListener('blur', function() {
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
}
</script>
@endpush
@endsection
