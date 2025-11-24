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
                                   class="w-full px-6 py-4 rounded-lg text-gray-800 search-input focus:outline-none focus:ring-2 focus:ring-yellow-300"
                                   onkeypress="if(event.key === 'Enter') searchByCity()">
                        </div>
                        <button onclick="searchByCity()" 
                                id="search-btn"
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
                            <span class="text-gray-600">Pharmacies trouvées</span>
                            <span class="font-bold text-blue-600 text-lg" id="found-pharmacies-count">0</span>
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

<!-- Notification Toast -->
<div id="toast-notification" class="fixed top-4 right-4 z-50 hidden" style="z-index: 10000;">
    <div id="toast-content" class="bg-white rounded-lg shadow-2xl p-4 min-w-80 max-w-md border-l-4 flex items-start space-x-3 animate-slide-in">
        <div id="toast-icon" class="flex-shrink-0 mt-1">
            <i class="fas fa-info-circle text-xl"></i>
        </div>
        <div class="flex-1">
            <h4 id="toast-title" class="font-semibold text-gray-800 mb-1"></h4>
            <p id="toast-message" class="text-sm text-gray-600"></p>
        </div>
        <button onclick="hideToast()" class="flex-shrink-0 text-gray-400 hover:text-gray-600 transition-colors">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>

<!-- Loading Modal -->
<div id="loading-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden" style="z-index: 9999;">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl max-w-md w-full p-8">
            <div class="text-center">
                <div class="inline-block animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-green-500 mb-4"></div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Recherche en cours...</h3>
                <p class="text-gray-600" id="loading-message">Recherche des pharmacies dans la ville sélectionnée</p>
            </div>
        </div>
    </div>
</div>

<!-- Pharmacy Details Modal -->
<div id="pharmacy-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden" style="z-index: 9998;">
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

@push('styles')
<style>
    @keyframes slide-in {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    .animate-slide-in {
        animation: slide-in 0.3s ease-out;
    }
</style>
@endpush

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
            const displayedCount = displayPharmacies(data.pharmacies);
            updateStats(data.pharmacies.length);
            if (displayedCount < data.pharmacies.length) {
                console.warn(`${data.pharmacies.length - displayedCount} pharmacie(s) sans coordonnées valides`);
            }
        }
    } catch (error) {
        console.error('Erreur lors du chargement des pharmacies:', error);
    }
}

// Fonction helper pour extraire et valider les coordonnées
function extractCoordinates(pharmacy) {
    let lat = null;
    let lng = null;
    
    // Essayer différents formats de coordonnées
    if (pharmacy.latitude !== null && pharmacy.latitude !== undefined) {
        // Si c'est un nombre
        if (typeof pharmacy.latitude === 'number') {
            lat = pharmacy.latitude;
        }
        // Si c'est une chaîne
        else if (typeof pharmacy.latitude === 'string') {
            lat = parseFloat(pharmacy.latitude);
        }
        // Si c'est un objet (cas Firestore)
        else if (typeof pharmacy.latitude === 'object' && pharmacy.latitude.doubleValue !== undefined) {
            lat = parseFloat(pharmacy.latitude.doubleValue);
        } else if (typeof pharmacy.latitude === 'object' && pharmacy.latitude.stringValue !== undefined) {
            lat = parseFloat(pharmacy.latitude.stringValue);
        }
    }
    
    if (pharmacy.longitude !== null && pharmacy.longitude !== undefined) {
        // Si c'est un nombre
        if (typeof pharmacy.longitude === 'number') {
            lng = pharmacy.longitude;
        }
        // Si c'est une chaîne
        else if (typeof pharmacy.longitude === 'string') {
            lng = parseFloat(pharmacy.longitude);
        }
        // Si c'est un objet (cas Firestore)
        else if (typeof pharmacy.longitude === 'object' && pharmacy.longitude.doubleValue !== undefined) {
            lng = parseFloat(pharmacy.longitude.doubleValue);
        } else if (typeof pharmacy.longitude === 'object' && pharmacy.longitude.stringValue !== undefined) {
            lng = parseFloat(pharmacy.longitude.stringValue);
        }
    }
    
    // Valider les coordonnées - vérifier que lat et lng ne sont pas null
    if (lat === null || lng === null || isNaN(lat) || isNaN(lng)) {
        return null;
    }
    
    // Vérifier les limites géographiques
    if (lat < -90 || lat > 90 || lng < -180 || lng > 180) {
        return null;
    }
    
    // Vérification finale que les valeurs sont des nombres valides
    const finalLat = parseFloat(lat);
    const finalLng = parseFloat(lng);
    
    if (isNaN(finalLat) || isNaN(finalLng)) {
        return null;
    }
    
    return { lat: finalLat, lng: finalLng };
}

// Afficher les pharmacies sur la carte
function displayPharmacies(pharmacies) {
    // Supprimer les anciens marqueurs
    markers.forEach(marker => map.removeLayer(marker));
    markers = [];
    
    if (!pharmacies || pharmacies.length === 0) {
        console.log('Aucune pharmacie à afficher');
        return 0;
    }
    
    // Filtrer et valider les pharmacies avec des coordonnées valides
    const validPharmacies = [];
    const invalidPharmacies = [];
    
    pharmacies.forEach(pharmacy => {
        const coords = extractCoordinates(pharmacy);
        
        // Vérifier que coords n'est pas null et que les valeurs sont valides
        if (coords && coords.lat !== null && coords.lng !== null && 
            !isNaN(coords.lat) && !isNaN(coords.lng) &&
            coords.lat >= -90 && coords.lat <= 90 &&
            coords.lng >= -180 && coords.lng <= 180) {
            pharmacy._validLat = coords.lat;
            pharmacy._validLng = coords.lng;
            validPharmacies.push(pharmacy);
        } else {
            // Debug pour voir pourquoi les coordonnées sont invalides
            console.log('Pharmacie sans coordonnées valides:', {
                id: pharmacy.id,
                name: pharmacy.name,
                latitude: pharmacy.latitude,
                longitude: pharmacy.longitude,
                latType: typeof pharmacy.latitude,
                lngType: typeof pharmacy.longitude,
                extractedCoords: coords
            });
            invalidPharmacies.push(pharmacy);
        }
    });
    
    // Afficher un avertissement si certaines pharmacies ont été filtrées
    if (invalidPharmacies.length > 0) {
        console.warn(`${invalidPharmacies.length} pharmacie(s) sans coordonnées valides ont été exclues de la carte`);
        console.log('Pharmacies exclues:', invalidPharmacies.map(p => ({ id: p.id, name: p.name, lat: p.latitude, lng: p.longitude })));
    }
    
    // Ajouter les nouveaux marqueurs uniquement pour les pharmacies valides
    validPharmacies.forEach(pharmacy => {
        // Vérification supplémentaire avant de créer le marqueur
        if (pharmacy._validLat === null || pharmacy._validLng === null || 
            isNaN(pharmacy._validLat) || isNaN(pharmacy._validLng)) {
            console.warn('Pharmacie avec coordonnées invalides ignorée:', {
                id: pharmacy.id,
                name: pharmacy.name,
                _validLat: pharmacy._validLat,
                _validLng: pharmacy._validLng
            });
            return; // Ignorer cette pharmacie
        }
        
        try {
            const lat = parseFloat(pharmacy._validLat);
            const lng = parseFloat(pharmacy._validLng);
            
            // Vérification finale des coordonnées
            if (isNaN(lat) || isNaN(lng) || lat < -90 || lat > 90 || lng < -180 || lng > 180) {
                console.warn('Coordonnées hors limites pour la pharmacie:', {
                    id: pharmacy.id,
                    name: pharmacy.name,
                    lat: lat,
                    lng: lng
                });
                return; // Ignorer cette pharmacie
            }
            
            const marker = L.marker([lat, lng])
                .addTo(map)
                .bindPopup(`
                    <div class="p-2">
                        <h4 class="font-bold text-lg">${pharmacy.name || 'Pharmacie'}</h4>
                        <p class="text-gray-600">${pharmacy.address || ''}, ${pharmacy.city || ''}</p>
                        ${pharmacy.phone ? `<p class="text-sm text-blue-600">${pharmacy.phone}</p>` : ''}
                        <button onclick="showPharmacyDetails('${pharmacy.id}')" 
                                class="mt-2 bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600">
                            Voir détails
                        </button>
                    </div>
                `);
            
            markers.push(marker);
        } catch (error) {
            console.error('Erreur lors de la création du marqueur pour la pharmacie:', pharmacy.id, error, {
                name: pharmacy.name,
                _validLat: pharmacy._validLat,
                _validLng: pharmacy._validLng,
                latitude: pharmacy.latitude,
                longitude: pharmacy.longitude
            });
        }
    });
    
    // Ajuster la vue pour afficher tous les marqueurs valides
    if (markers.length > 0) {
        try {
        const group = new L.featureGroup(markers);
        map.fitBounds(group.getBounds().pad(0.1));
        } catch (error) {
            console.error('Erreur lors de l\'ajustement de la vue:', error);
            // Si l'ajustement échoue, centrer sur le premier marqueur
            if (markers.length > 0) {
                const firstMarker = markers[0];
                map.setView(firstMarker.getLatLng(), 13);
            }
        }
    } else if (validPharmacies.length > 0 && markers.length === 0) {
        // Si aucune pharmacie n'a pu être affichée mais qu'il y en a, centrer sur une position par défaut
        console.warn('Aucun marqueur n\'a pu être créé malgré des pharmacies valides. Vérifiez les coordonnées.');
    }
    
    // Mettre à jour le nombre de pharmacies affichées (seulement celles avec marqueurs créés)
    return markers.length;
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
    
    // Afficher le modal de chargement
    showLoadingModal(`Recherche des pharmacies dans un rayon de ${radius} km...`);
    
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
        
        // Vérifier si la réponse est OK
        if (!response.ok) {
            const errorText = await response.text();
            console.error('Erreur HTTP:', response.status, errorText);
            throw new Error(`Erreur HTTP: ${response.status}`);
        }
        
        const data = await response.json();
        
        if (data.success) {
            // Afficher les pharmacies et obtenir le nombre de pharmacies affichées sur la carte
            const displayedCount = displayPharmacies(data.pharmacies);
            updateNearbyStats(displayedCount);
            updateFoundPharmaciesCount(data.pharmacies.length);
            
            if (data.pharmacies.length === 0) {
                showToast(`Aucune pharmacie trouvée dans un rayon de ${radius} km`, 'warning', 'Recherche terminée');
            } else if (displayedCount === 0 && data.pharmacies.length > 0) {
                showToast(`${data.pharmacies.length} pharmacie(s) trouvée(s), mais aucune n'a de coordonnées valides pour l'affichage sur la carte.`, 'warning', 'Attention');
            } else {
                showToast(`${data.pharmacies.length} pharmacie(s) trouvée(s) dans un rayon de ${radius} km`, 'success', 'Recherche réussie');
            }
        } else {
            const errorMessage = data.message || 'Erreur lors de la recherche';
            console.error('Erreur de recherche:', errorMessage);
            showToast(errorMessage, 'error', 'Erreur');
        }
    } catch (error) {
        console.error('Erreur lors de la recherche:', error);
        showToast('Une erreur est survenue lors de la recherche. Veuillez réessayer.', 'error', 'Erreur');
    } finally {
        hideLoadingModal();
    }
}

// Afficher le modal de chargement
function showLoadingModal(message = 'Recherche en cours...') {
    document.getElementById('loading-message').textContent = message;
    document.getElementById('loading-modal').classList.remove('hidden');
}

// Masquer le modal de chargement
function hideLoadingModal() {
    document.getElementById('loading-modal').classList.add('hidden');
}

// Afficher une notification toast
function showToast(message, type = 'info', title = '') {
    const toast = document.getElementById('toast-notification');
    const toastContent = document.getElementById('toast-content');
    const toastIcon = document.getElementById('toast-icon');
    const toastTitle = document.getElementById('toast-title');
    const toastMessage = document.getElementById('toast-message');
    
    // Définir les styles selon le type
    const styles = {
        success: {
            borderColor: 'border-green-500',
            icon: 'fas fa-check-circle text-green-500',
            bgIcon: 'bg-green-100'
        },
        error: {
            borderColor: 'border-red-500',
            icon: 'fas fa-exclamation-circle text-red-500',
            bgIcon: 'bg-red-100'
        },
        warning: {
            borderColor: 'border-yellow-500',
            icon: 'fas fa-exclamation-triangle text-yellow-500',
            bgIcon: 'bg-yellow-100'
        },
        info: {
            borderColor: 'border-blue-500',
            icon: 'fas fa-info-circle text-blue-500',
            bgIcon: 'bg-blue-100'
        }
    };
    
    const style = styles[type] || styles.info;
    
    // Appliquer les styles
    toastContent.className = `bg-white rounded-lg shadow-2xl p-4 min-w-80 max-w-md border-l-4 flex items-start space-x-3 animate-slide-in ${style.borderColor}`;
    toastIcon.innerHTML = `<i class="${style.icon} text-xl"></i>`;
    toastTitle.textContent = title || (type === 'success' ? 'Succès' : type === 'error' ? 'Erreur' : type === 'warning' ? 'Attention' : 'Information');
    toastMessage.textContent = message;
    
    // Afficher la notification
    toast.classList.remove('hidden');
    
    // Masquer automatiquement après 5 secondes
    setTimeout(() => {
        hideToast();
    }, 5000);
}

// Masquer la notification toast
function hideToast() {
    const toast = document.getElementById('toast-notification');
    toast.classList.add('hidden');
}

// Rechercher par ville
async function searchByCity() {
    const city = document.getElementById('city-search').value.trim();
    
    if (!city) {
        showToast('Veuillez entrer une ville', 'warning', 'Champ requis');
        return;
    }
    
    // Désactiver le bouton de recherche
    const searchBtn = document.getElementById('search-btn');
    const originalText = searchBtn.innerHTML;
    searchBtn.disabled = true;
    searchBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Recherche...';
    
    // Afficher le modal de chargement
    showLoadingModal(`Recherche des pharmacies à ${city}...`);
    
    try {
        // Préparer les données à envoyer
        const requestData = { city: city };
        
        // Si la position de l'utilisateur est disponible, l'inclure pour améliorer la recherche
        if (currentLocation) {
            requestData.latitude = currentLocation.lat;
            requestData.longitude = currentLocation.lng;
            const radius = document.getElementById('radius-select').value;
            requestData.radius = parseInt(radius);
        }
        
        const response = await fetch('/pharmacies/search-by-city', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(requestData)
        });
        
        // Vérifier si la réponse est OK
        if (!response.ok) {
            const errorText = await response.text();
            console.error('Erreur HTTP:', response.status, errorText);
            throw new Error(`Erreur HTTP: ${response.status}`);
        }
        
        const data = await response.json();
        
        if (data.success) {
            // Afficher les pharmacies et obtenir le nombre de pharmacies affichées sur la carte
            const displayedCount = displayPharmacies(data.pharmacies);
            updateNearbyStats(displayedCount);
            updateFoundPharmaciesCount(data.pharmacies.length);
            
            // Afficher un message si aucune pharmacie n'est trouvée
            if (data.pharmacies.length === 0) {
                showToast(`Aucune pharmacie trouvée à ${city}`, 'warning', 'Recherche terminée');
            } else if (displayedCount === 0 && data.pharmacies.length > 0) {
                // Si des pharmacies sont trouvées mais aucune n'a de coordonnées valides
                showToast(`${data.pharmacies.length} pharmacie(s) trouvée(s) à ${city}, mais aucune n'a de coordonnées valides pour l'affichage sur la carte.`, 'warning', 'Attention');
            } else {
                // Afficher un message de succès discret
                showToast(`${data.pharmacies.length} pharmacie(s) trouvée(s) à ${city}, ${displayedCount} affichée(s) sur la carte`, 'success', 'Recherche réussie');
            }
        } else {
            const errorMessage = data.message || 'Erreur lors de la recherche';
            console.error('Erreur de recherche:', errorMessage);
            showToast(errorMessage, 'error', 'Erreur');
        }
    } catch (error) {
        console.error('Erreur lors de la recherche par ville:', error);
        console.error('Détails de l\'erreur:', error.message);
        showToast('Une erreur est survenue lors de la recherche. Veuillez vérifier la console pour plus de détails.', 'error', 'Erreur');
    } finally {
        // Réactiver le bouton et masquer le modal
        searchBtn.disabled = false;
        searchBtn.innerHTML = originalText;
        hideLoadingModal();
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

function updateFoundPharmaciesCount(count) {
    const countElement = document.getElementById('found-pharmacies-count');
    if (countElement) {
        countElement.textContent = count;
        // Ajouter une animation pour attirer l'attention
        countElement.classList.add('animate-pulse');
        setTimeout(() => {
            countElement.classList.remove('animate-pulse');
        }, 1000);
    }
}

// Événements
document.getElementById('radius-select').addEventListener('change', function() {
    if (currentLocation) {
        // Rechercher automatiquement les pharmacies à proximité avec le nouveau rayon
        searchNearbyPharmacies();
    } else {
        // Si on a une recherche par ville active, relancer la recherche avec le nouveau rayon
        const city = document.getElementById('city-search').value.trim();
        if (city) {
            // Si on a une position, l'utiliser pour améliorer la recherche
            if (currentLocation) {
                searchByCity();
            }
        }
    }
});

// Initialiser la carte au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    initMap();
});
</script>
@endpush
