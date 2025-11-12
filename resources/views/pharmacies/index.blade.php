@extends('layouts.app')

@section('title', 'GeoPharma - Trouvez votre pharmacie')

@section('content')
<div class="min-h-screen">
    <!-- Hero Section -->
    <div class="gradient-bg text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold mb-6">
                    Trouvez la <span class="text-green-200">pharmacie</span>
                </h1>
                <p class="text-xl md:text-2xl mb-8 text-green-100">
                    Géolocalisation en temps réel • Contact direct • Services de proximité
                </p>
                
                <!-- Search Bar -->
                <div class="max-w-2xl mx-auto">
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1">
                            <input type="text" 
                                   id="city-search" 
                                   placeholder="Rechercher par ville (ex: Kinshasa, Lubumbashi...)" 
                                   class="w-full px-6 py-4 rounded-lg text-gray-800 search-input focus:outline-none focus:ring-2 focus:ring-yellow-300">
                        </div>
                        <button onclick="searchByCity()" 
                                class="btn-primary px-8 py-4 rounded-lg font-semibold text-white hover:shadow-lg transition-all duration-300">
                            <i class="fas fa-search mr-2"></i>Rechercher
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Map Section -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">
                            <i class="fas fa-map-marker-alt text-green-500 mr-2"></i>
                            Carte des pharmacies
                        </h2>
                        <div class="flex items-center space-x-4">
                            <button onclick="getCurrentLocation()" 
                                    class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors duration-200">
                                <i class="fas fa-crosshairs mr-2"></i>Ma position
                            </button>
                            <select id="radius-select" 
                                    class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                                <option value="5">5 km</option>
                                <option value="10" selected>10 km</option>
                                <option value="20">20 km</option>
                                <option value="50">50 km</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Map Container -->
                    <div id="map" class="w-full h-96 rounded-lg border border-gray-200"></div>
                    
                    <!-- Map Info -->
                    <div class="mt-4 text-sm text-gray-600">
                        <i class="fas fa-info-circle mr-2"></i>
                        Cliquez sur un marqueur pour voir les détails de la pharmacie
                    </div>
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Search Results -->
                <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-list mr-2"></i>Pharmacies trouvées
                    </h3>
                    <div id="pharmacies-list" class="space-y-4">
                        <!-- Les pharmacies seront chargées ici via JavaScript -->
                    </div>
                </div>
                
                <!-- Quick Stats -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-chart-bar mr-2"></i>Statistiques
                    </h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Pharmacies actives</span>
                            <span class="font-bold text-green-600" id="total-pharmacies">{{ $pharmacies->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Dans votre zone</span>
                            <span class="font-bold text-green-500" id="nearby-pharmacies">0</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Services disponibles</span>
                            <span class="font-bold text-green-700">Livraison, Garde</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pharmacy Details Modal -->
<div id="pharmacy-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl max-w-2xl w-full max-h-96 overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-2xl font-bold text-gray-800" id="modal-pharmacy-name"></h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <div id="modal-pharmacy-content">
                    <!-- Le contenu sera chargé ici -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let map;
let markers = [];
let currentLocation = null;

// Initialiser la carte
function initMap() {
    // Centrer sur Paris par défaut
    map = L.map('map').setView([48.8566, 2.3522], 10);
    
    // Ajouter la couche de tuiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    
    // Charger les pharmacies
    loadPharmacies();
}

// Charger les pharmacies depuis l'API
async function loadPharmacies() {
    try {
        const response = await fetch('/pharmacies-api/map');
        const data = await response.json();
        
        if (data.success) {
            displayPharmacies(data.pharmacies);
            updateStats(data.pharmacies.length);
        }
    } catch (error) {
        console.error('Erreur lors du chargement des pharmacies:', error);
    }
}

// Afficher les pharmacies sur la carte
function displayPharmacies(pharmacies) {
    // Supprimer les anciens marqueurs
    markers.forEach(marker => map.removeLayer(marker));
    markers = [];
    
    // Ajouter les nouveaux marqueurs
    pharmacies.forEach(pharmacy => {
        const marker = L.marker([pharmacy.latitude, pharmacy.longitude])
            .addTo(map)
            .bindPopup(`
                <div class="p-2">
                    <h4 class="font-bold text-lg">${pharmacy.name}</h4>
                    <p class="text-gray-600">${pharmacy.address}, ${pharmacy.city}</p>
                    <p class="text-sm text-blue-600">${pharmacy.phone}</p>
                    <button onclick="showPharmacyDetails(${pharmacy.id})" 
                            class="mt-2 bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600">
                        Voir détails
                    </button>
                </div>
            `);
        
        markers.push(marker);
    });
    
    // Ajuster la vue pour afficher tous les marqueurs
    if (pharmacies.length > 0) {
        const group = new L.featureGroup(markers);
        map.fitBounds(group.getBounds().pad(0.1));
    }
}

// Obtenir la position actuelle de l'utilisateur
function getCurrentLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                currentLocation = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
                
                // Centrer la carte sur la position actuelle
                map.setView([currentLocation.lat, currentLocation.lng], 15);
                
                // Ajouter un marqueur pour la position actuelle
                L.marker([currentLocation.lat, currentLocation.lng])
                    .addTo(map)
                    .bindPopup('Votre position actuelle')
                    .openPopup();
                
                // Rechercher les pharmacies à proximité
                searchNearbyPharmacies();
            },
            function(error) {
                alert('Impossible d\'obtenir votre position. Veuillez autoriser la géolocalisation.');
            }
        );
    } else {
        alert('La géolocalisation n\'est pas supportée par votre navigateur.');
    }
}

// Rechercher les pharmacies à proximité
async function searchNearbyPharmacies() {
    if (!currentLocation) return;
    
    const radius = document.getElementById('radius-select').value;
    
    try {
        const response = await fetch('/pharmacies/search', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                latitude: currentLocation.lat,
                longitude: currentLocation.lng,
                radius: radius
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            displayPharmacies(data.pharmacies);
            updateNearbyStats(data.pharmacies.length);
        }
    } catch (error) {
        console.error('Erreur lors de la recherche:', error);
    }
}

// Rechercher par ville
async function searchByCity() {
    const city = document.getElementById('city-search').value.trim();
    
    if (!city) {
        alert('Veuillez entrer une ville');
        return;
    }
    
    try {
        const response = await fetch('/pharmacies/search-by-city', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ city: city })
        });
        
        const data = await response.json();
        
        if (data.success) {
            displayPharmacies(data.pharmacies);
            updateNearbyStats(data.pharmacies.length);
        }
    } catch (error) {
        console.error('Erreur lors de la recherche par ville:', error);
    }
}

// Afficher les détails d'une pharmacie
function showPharmacyDetails(pharmacyId) {
    // Rediriger vers la page de détails (nécessite une connexion)
    window.location.href = `/pharmacies/${pharmacyId}`;
}

// Fermer le modal
function closeModal() {
    document.getElementById('pharmacy-modal').classList.add('hidden');
}

// Mettre à jour les statistiques
function updateStats(total) {
    document.getElementById('total-pharmacies').textContent = total;
}

function updateNearbyStats(nearby) {
    document.getElementById('nearby-pharmacies').textContent = nearby;
}

// Événements
document.getElementById('radius-select').addEventListener('change', function() {
    if (currentLocation) {
        searchNearbyPharmacies();
    }
});

// Initialiser la carte au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    initMap();
});
</script>
@endpush
