@extends('layouts.app')

@section('title', 'Localisation - Profil Pharmacien')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-green-50">
    <!-- Header avec gradient -->
    <div class="bg-gradient-to-r from-green-600 to-green-700 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-white">
                        <i class="fas fa-map-marker-alt mr-3"></i>
                        Ma Localisation
                    </h1>
                    <p class="text-green-100 mt-2 text-lg">Définissez votre position pour améliorer la recherche de pharmacies</p>
                </div>
                <a href="{{ route('pharmacist.dashboard') }}" class="bg-white text-green-700 px-4 py-2 rounded-lg hover:bg-green-50 transition-colors duration-200 font-medium shadow-md">
                    <i class="fas fa-arrow-left mr-2"></i>Retour
                </a>
            </div>
        </div>
    </div>

    <!-- Breadcrumb -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <nav class="flex items-center space-x-2 text-sm">
                <a href="{{ route('home') }}" class="text-green-600 hover:text-green-800 transition-colors">
                    <i class="fas fa-home mr-1"></i>Accueil
                </a>
                <i class="fas fa-chevron-right text-gray-400"></i>
                <a href="{{ route('pharmacist.dashboard') }}" class="text-green-600 hover:text-green-800 transition-colors">
                    Dashboard
                </a>
                <i class="fas fa-chevron-right text-gray-400"></i>
                <span class="text-gray-600 font-medium">Localisation</span>
            </nav>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Carte de Localisation -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">
                            <i class="fas fa-map text-green-500 mr-2"></i>Carte de Localisation
                        </h2>
                    </div>
                    
                    <div class="mb-4">
                        <div id="map" class="w-full h-96 rounded-lg border-2 border-gray-200" style="z-index: 1;"></div>
                    </div>
                    
                    <div class="flex flex-wrap gap-3">
                        <button type="button" 
                                onclick="getCurrentLocation()"
                                class="bg-green-500 text-white px-6 py-3 rounded-lg hover:bg-green-600 transition-colors duration-200 flex items-center">
                            <i class="fas fa-crosshairs mr-2"></i>
                            Utiliser ma position actuelle
                        </button>
                        <button type="button" 
                                onclick="centerMapOnKinshasa()"
                                class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition-colors duration-200 flex items-center">
                            <i class="fas fa-map mr-2"></i>
                            Centrer sur Kinshasa
                        </button>
                        <button type="button" 
                                onclick="clearLocation()"
                                class="bg-red-500 text-white px-6 py-3 rounded-lg hover:bg-red-600 transition-colors duration-200 flex items-center">
                            <i class="fas fa-trash mr-2"></i>
                            Effacer la position
                        </button>
                    </div>
                    
                    <p class="mt-4 text-sm text-gray-600">
                        <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                        <strong>Cliquez sur la carte</strong> pour sélectionner votre position, ou utilisez le bouton "Utiliser ma position actuelle" pour la géolocalisation automatique.
                    </p>
                </div>
            </div>
            
            <!-- Formulaire et Conseils -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Informations de Localisation -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-6">
                        <i class="fas fa-info-circle text-green-500 mr-2"></i>Informations de Localisation
                    </h3>
                    
                    <form id="locationForm" method="POST" action="{{ route('pharmacist.update-location') }}" class="space-y-4">
                        @csrf
                        @method('PUT')
                        
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Adresse</label>
                            <input type="text" 
                                   id="address" 
                                   name="address" 
                                   value="{{ old('address', auth()->user()->address) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('address') border-red-500 @enderror"
                                   placeholder="Ex: Avenue Kasa-Vubu, Gombe">
                            @error('address')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700 mb-2">Ville</label>
                                <input type="text" 
                                       id="city" 
                                       name="city" 
                                       value="{{ old('city', auth()->user()->city) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('city') border-red-500 @enderror"
                                       placeholder="Ex: Kinshasa">
                                @error('city')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-2">Code Postal</label>
                                <input type="text" 
                                       id="postal_code" 
                                       name="postal_code" 
                                       value="{{ old('postal_code', auth()->user()->postal_code) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('postal_code') border-red-500 @enderror"
                                       placeholder="Ex: 001">
                                @error('postal_code')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="latitude" class="block text-xs text-gray-500 mb-1">Latitude</label>
                                <input type="number" 
                                       step="any" 
                                       id="latitude" 
                                       name="latitude" 
                                       value="{{ old('latitude', auth()->user()->latitude) }}"
                                       readonly
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('latitude') border-red-500 @enderror"
                                       placeholder="Sélectionnez sur la carte">
                                <p class="mt-1 text-xs text-gray-500">Rempli automatiquement</p>
                                @error('latitude')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="longitude" class="block text-xs text-gray-500 mb-1">Longitude</label>
                                <input type="number" 
                                       step="any" 
                                       id="longitude" 
                                       name="longitude" 
                                       value="{{ old('longitude', auth()->user()->longitude) }}"
                                       readonly
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('longitude') border-red-500 @enderror"
                                       placeholder="Sélectionnez sur la carte">
                                <p class="mt-1 text-xs text-gray-500">Rempli automatiquement</p>
                                @error('longitude')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <button type="submit" class="w-full bg-green-500 text-white px-6 py-3 rounded-lg hover:bg-green-600 transition-colors duration-200 flex items-center justify-center font-medium">
                            <i class="fas fa-save mr-2"></i>
                            Sauvegarder la Localisation
                        </button>
                    </form>
                </div>
                
                <!-- Conseils -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-6">
                        <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>Conseils
                    </h3>
                    <div class="space-y-3">
                        <div class="flex items-start p-3 bg-green-50 rounded-lg">
                            <i class="fas fa-check-circle text-green-500 mr-3 mt-1"></i>
                            <p class="text-sm text-gray-700">Cliquez sur la carte pour définir votre position</p>
                        </div>
                        <div class="flex items-start p-3 bg-blue-50 rounded-lg">
                            <i class="fas fa-crosshairs text-blue-500 mr-3 mt-1"></i>
                            <p class="text-sm text-gray-700">Utilisez le bouton "Utiliser ma position" pour la géolocalisation automatique</p>
                        </div>
                        <div class="flex items-start p-3 bg-purple-50 rounded-lg">
                            <i class="fas fa-users text-purple-500 mr-3 mt-1"></i>
                            <p class="text-sm text-gray-700">Votre localisation aide les utilisateurs à vous trouver plus facilement</p>
                        </div>
                        <div class="flex items-start p-3 bg-yellow-50 rounded-lg">
                            <i class="fas fa-sync text-yellow-500 mr-3 mt-1"></i>
                            <p class="text-sm text-gray-700">Les coordonnées sont automatiquement mises à jour</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour les messages -->
<div id="modal-overlay" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div id="modal-content" class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4 transform transition-all">
        <div class="p-6">
            <div id="modal-header" class="flex items-center justify-between mb-4">
                <div id="modal-icon" class="flex items-center">
                    <i id="modal-icon-class" class="fas fa-info-circle text-3xl mr-3"></i>
                    <h3 id="modal-title" class="text-xl font-bold text-gray-900"></h3>
                </div>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div id="modal-body" class="mb-6">
                <p id="modal-message" class="text-gray-700"></p>
            </div>
            <div id="modal-footer" class="flex justify-end space-x-3">
                <button id="modal-cancel-btn" onclick="closeModal()" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors hidden">
                    Annuler
                </button>
                <button id="modal-ok-btn" onclick="closeModal()" class="px-6 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                    OK
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let map;
let marker;
let userLocation = null;

// Initialiser la carte
function initMap() {
    // Position par défaut (Kinshasa) ou position de l'utilisateur
    const userLat = {{ auth()->user()->latitude ?? 'null' }};
    const userLng = {{ auth()->user()->longitude ?? 'null' }};
    const defaultLat = userLat || -4.3276;
    const defaultLng = userLng || 15.3136;
    
    map = L.map('map').setView([defaultLat, defaultLng], userLat ? 15 : 13);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);
    
    // Ajouter un marqueur si l'utilisateur a déjà une position
    if (userLat && userLng) {
        marker = L.marker([userLat, userLng], {
            draggable: true
        }).addTo(map);
        marker.bindPopup('<strong>Votre position actuelle</strong><br>Déplacez ce marqueur pour changer votre position').openPopup();
        
        // Mettre à jour les coordonnées quand on déplace le marqueur
        marker.on('dragend', function(e) {
            const position = marker.getLatLng();
            updateCoordinates(position.lat, position.lng);
            reverseGeocode(position.lat, position.lng);
        });
    } else {
        // Ajouter un marqueur par défaut au centre de Kinshasa
        marker = L.marker([defaultLat, defaultLng], {
            draggable: true
        }).addTo(map);
        marker.bindPopup('<strong>Cliquez sur la carte ou déplacez ce marqueur</strong><br>pour sélectionner votre position').openPopup();
        
        marker.on('dragend', function(e) {
            const position = marker.getLatLng();
            updateCoordinates(position.lat, position.lng);
            reverseGeocode(position.lat, position.lng);
        });
    }
    
    // Gérer les clics sur la carte
    map.on('click', function(e) {
        setMapLocation(e.latlng.lat, e.latlng.lng);
    });
}

// Définir l'emplacement sur la carte
function setMapLocation(lat, lng) {
    // Mettre à jour ou créer le marqueur
    if (marker) {
        marker.setLatLng([lat, lng]);
    } else {
        marker = L.marker([lat, lng], {
            draggable: true
        }).addTo(map);
        
        marker.on('dragend', function(e) {
            const position = marker.getLatLng();
            updateCoordinates(position.lat, position.lng);
            reverseGeocode(position.lat, position.lng);
        });
    }
    
    // Centrer la carte sur la position
    map.setView([lat, lng], 15);
    
    // Mettre à jour les coordonnées dans les champs
    updateCoordinates(lat, lng);
    
    // Géocodage inverse pour obtenir l'adresse
    reverseGeocode(lat, lng);
    
    // Mettre à jour le popup
    marker.bindPopup(`<strong>Position sélectionnée</strong><br>Lat: ${lat.toFixed(6)}<br>Lng: ${lng.toFixed(6)}`).openPopup();
}

// Mettre à jour les champs latitude et longitude
function updateCoordinates(lat, lng) {
    document.getElementById('latitude').value = lat.toFixed(8);
    document.getElementById('longitude').value = lng.toFixed(8);
    userLocation = { lat, lng };
}

// Géolocalisation automatique
function getCurrentLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                setMapLocation(lat, lng);
                showMessage('Position actuelle récupérée avec succès !', 'success');
            },
            function(error) {
                showModal('Géolocalisation impossible', 'Impossible d\'obtenir votre position. Veuillez autoriser la géolocalisation dans les paramètres de votre navigateur ou sélectionner manuellement votre position sur la carte.', 'error');
            }
        );
    } else {
        showModal('Géolocalisation non supportée', 'La géolocalisation n\'est pas supportée par votre navigateur. Veuillez sélectionner manuellement votre position sur la carte.', 'error');
    }
}

// Centrer la carte sur Kinshasa
function centerMapOnKinshasa() {
    setMapLocation(-4.3276, 15.3136);
    showMessage('Carte centrée sur Kinshasa', 'info');
}

// Effacer la localisation
function clearLocation() {
    showConfirmModal(
        'Effacer la localisation',
        'Êtes-vous sûr de vouloir effacer votre localisation ? Cette action supprimera toutes les informations de localisation.',
        function() {
            if (marker) {
                map.removeLayer(marker);
                marker = null;
            }
            document.getElementById('latitude').value = '';
            document.getElementById('longitude').value = '';
            document.getElementById('address').value = '';
            document.getElementById('city').value = '';
            document.getElementById('postal_code').value = '';
            
            // Recentrer sur Kinshasa
            map.setView([-4.3276, 15.3136], 13);
            
            showMessage('Localisation effacée', 'info');
            closeModal();
        }
    );
}

// Géocodage inverse
function reverseGeocode(lat, lng) {
    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`)
        .then(response => response.json())
        .then(data => {
            if (data.address) {
                // Construire l'adresse complète
                const addressParts = [];
                if (data.address.road) addressParts.push(data.address.road);
                if (data.address.house_number) addressParts.unshift(data.address.house_number);
                
                if (addressParts.length > 0) {
                    document.getElementById('address').value = addressParts.join(' ');
                } else if (data.display_name) {
                    document.getElementById('address').value = data.display_name.split(',')[0];
                }
                
                if (data.address.city || data.address.town || data.address.village || data.address.municipality) {
                    document.getElementById('city').value = data.address.city || data.address.town || data.address.village || data.address.municipality;
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

// Afficher un modal
function showModal(title, message, type = 'info') {
    const overlay = document.getElementById('modal-overlay');
    const modalTitle = document.getElementById('modal-title');
    const modalMessage = document.getElementById('modal-message');
    const modalIcon = document.getElementById('modal-icon-class');
    const cancelBtn = document.getElementById('modal-cancel-btn');
    const okBtn = document.getElementById('modal-ok-btn');
    
    modalTitle.textContent = title;
    modalMessage.textContent = message;
    
    // Réinitialiser les boutons
    cancelBtn.classList.add('hidden');
    okBtn.textContent = 'OK';
    okBtn.onclick = closeModal;
    
    let iconClass, iconColor;
    switch(type) {
        case 'success':
            iconClass = 'fa-check-circle';
            iconColor = 'text-green-500';
            break;
        case 'error':
            iconClass = 'fa-exclamation-circle';
            iconColor = 'text-red-500';
            break;
        case 'warning':
            iconClass = 'fa-exclamation-triangle';
            iconColor = 'text-yellow-500';
            break;
        default:
            iconClass = 'fa-info-circle';
            iconColor = 'text-blue-500';
    }
    
    modalIcon.className = `fas ${iconClass} text-3xl mr-3 ${iconColor}`;
    overlay.classList.remove('hidden');
}

// Afficher un modal de confirmation
function showConfirmModal(title, message, onConfirm) {
    const overlay = document.getElementById('modal-overlay');
    const modalTitle = document.getElementById('modal-title');
    const modalMessage = document.getElementById('modal-message');
    const modalIcon = document.getElementById('modal-icon-class');
    const cancelBtn = document.getElementById('modal-cancel-btn');
    const okBtn = document.getElementById('modal-ok-btn');
    
    modalTitle.textContent = title;
    modalMessage.textContent = message;
    modalIcon.className = 'fas fa-question-circle text-3xl mr-3 text-yellow-500';
    
    // Afficher le bouton Annuler
    cancelBtn.classList.remove('hidden');
    okBtn.textContent = 'Confirmer';
    
    // Gérer les actions
    okBtn.onclick = function() {
        if (onConfirm) onConfirm();
    };
    cancelBtn.onclick = closeModal;
    
    overlay.classList.remove('hidden');
}

// Fermer le modal
function closeModal() {
    document.getElementById('modal-overlay').classList.add('hidden');
}

// Afficher un message temporaire (toast)
function showMessage(message, type = 'info') {
    const messageDiv = document.createElement('div');
    messageDiv.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg text-white transform transition-all ${
        type === 'success' ? 'bg-green-500' : 
        type === 'error' ? 'bg-red-500' : 
        'bg-blue-500'
    }`;
    messageDiv.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'} mr-2"></i>${message}`;
    
    document.body.appendChild(messageDiv);
    
    setTimeout(() => {
        messageDiv.style.transform = 'translateX(0)';
    }, 10);
    
    setTimeout(() => {
        messageDiv.style.transition = 'opacity 0.5s, transform 0.5s';
        messageDiv.style.opacity = '0';
        messageDiv.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (messageDiv.parentNode) {
                document.body.removeChild(messageDiv);
            }
        }, 500);
    }, 3000);
}

// Fermer le modal en cliquant sur l'overlay
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('modal-overlay').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
    
    // Fermer avec la touche Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });
    
    // Initialiser la carte
    initMap();
    
    // Validation du formulaire
    document.getElementById('locationForm').addEventListener('submit', function(e) {
        const latitude = document.getElementById('latitude').value;
        const longitude = document.getElementById('longitude').value;
        
        if (!latitude || !longitude) {
            e.preventDefault();
            showModal('Localisation requise', 'Veuillez sélectionner une position sur la carte avant de sauvegarder.', 'error');
            return false;
        }
    });
    
    // Afficher les messages flash avec le système de toast
    @if(session('success'))
        showMessage('{{ session('success') }}', 'success');
    @endif
    
    @if(session('error'))
        showMessage('{{ session('error') }}', 'error');
    @endif
});
</script>

<style>
#map {
    cursor: crosshair;
}

.leaflet-container {
    font-family: 'Inter', sans-serif;
}
</style>
@endpush
@endsection
