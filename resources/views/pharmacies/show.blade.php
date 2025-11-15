@extends('layouts.app')

@section('title', $pharmacy->name . ' - GeoPharma')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Breadcrumb -->
    <div class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <nav class="flex items-center space-x-2 text-sm">
                <a href="{{ route('home') }}" class="text-green-600 hover:text-green-800">Accueil</a>
                <i class="fas fa-chevron-right text-gray-400"></i>
                <a href="{{ route('pharmacies.index') }}" class="text-green-600 hover:text-green-800">Pharmacies</a>
                <i class="fas fa-chevron-right text-gray-400"></i>
                <span class="text-gray-600">{{ $pharmacy->name }}</span>
            </nav>
        </div>
    </div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <!-- Pharmacy Header -->
                <div class="bg-white rounded-xl shadow-lg p-8 mb-6">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $pharmacy->name }}</h1>
                            <div class="flex items-center text-gray-600 mb-4">
                                <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>
                                <span>{{ $pharmacy->address }}, {{ $pharmacy->city }} {{ $pharmacy->postal_code }}</span>
                            </div>
                            
                            @if($pharmacy->description)
                                <p class="text-gray-700 mb-6">{{ $pharmacy->description }}</p>
                            @endif
                            
                            <!-- Status Badges -->
                            <div class="flex items-center space-x-4">
                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                                    <i class="fas fa-check-circle mr-1"></i>Vérifiée
                                </span>
                                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                                    <i class="fas fa-clock mr-1"></i>Ouverte
                                </span>
                            </div>
                        </div>
                        
                        <!-- Pharmacy Image Placeholder -->
                        <div class="w-32 h-32 bg-gradient-to-br from-green-400 to-green-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-pills text-white text-4xl"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Contact Information -->
                <div class="bg-white rounded-xl shadow-lg p-8 mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">
                        <i class="fas fa-phone text-green-500 mr-2"></i>Informations de contact
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                                    <i class="fas fa-phone text-green-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Téléphone</p>
                                    <p class="font-semibold text-gray-800">{{ $pharmacy->phone }}</p>
                                </div>
                            </div>
                            
                            @if($pharmacy->email)
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                                        <i class="fas fa-envelope text-green-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Email</p>
                                        <p class="font-semibold text-gray-800">{{ $pharmacy->email }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        <div class="space-y-4">
                            @if($pharmacy->whatsapp_number)
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                                        <i class="fab fa-whatsapp text-green-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">WhatsApp</p>
                                        <p class="font-semibold text-gray-800">{{ $pharmacy->whatsapp_number }}</p>
                                    </div>
                                </div>
                            @endif
                            
                            @if($pharmacy->pharmacist)
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                                    <i class="fas fa-user-md text-purple-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Pharmacien</p>
                                    <p class="font-semibold text-gray-800">{{ $pharmacy->pharmacist->name }}</p>
                                </div>
                            </div>
                            @else
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                                    <i class="fas fa-user-md text-purple-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Pharmacien</p>
                                    <p class="font-semibold text-gray-400">Non assigné</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Opening Hours -->
                @if($pharmacy->opening_hours && is_array($pharmacy->opening_hours))
                    <div class="bg-white rounded-xl shadow-lg p-8 mb-6">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6">
                            <i class="fas fa-clock text-orange-500 mr-2"></i>Horaires d'ouverture
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @php
                                $dayNames = [
                                    'lundi' => 'Lundi',
                                    'mardi' => 'Mardi',
                                    'mercredi' => 'Mercredi',
                                    'jeudi' => 'Jeudi',
                                    'vendredi' => 'Vendredi',
                                    'samedi' => 'Samedi',
                                    'dimanche' => 'Dimanche'
                                ];
                            @endphp
                            @foreach($dayNames as $dayKey => $dayName)
                                @php
                                    $hours = $pharmacy->opening_hours[$dayKey] ?? null;
                                    $formattedHours = '';
                                    
                                    if ($hours) {
                                        // Vérifier si c'est un mode séparé (avec morning/afternoon)
                                        if (isset($hours['morning']) && isset($hours['afternoon'])) {
                                            $morning = $hours['morning'];
                                            $afternoon = $hours['afternoon'];
                                            if (isset($morning['start']) && isset($morning['end']) && 
                                                isset($afternoon['start']) && isset($afternoon['end'])) {
                                                $formattedHours = $morning['start'] . ' - ' . $morning['end'] . ' / ' . 
                                                                  $afternoon['start'] . ' - ' . $afternoon['end'];
                                            }
                                        } 
                                        // Mode simple (avec start/end)
                                        elseif (isset($hours['start']) && isset($hours['end'])) {
                                            $formattedHours = $hours['start'] . ' - ' . $hours['end'];
                                        }
                                        // Si c'est fermé ou format inconnu
                                        elseif (isset($hours['closed']) && $hours['closed']) {
                                            $formattedHours = 'Fermé';
                                        }
                                    } else {
                                        $formattedHours = 'Non renseigné';
                                    }
                                @endphp
                                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                    <span class="font-medium text-gray-700">{{ $dayName }}</span>
                                    <span class="text-gray-600">{{ $formattedHours }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                
                <!-- Services -->
                @if($pharmacy->services && count($pharmacy->services) > 0)
                    <div class="bg-white rounded-xl shadow-lg p-8">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6">
                            <i class="fas fa-concierge-bell text-purple-500 mr-2"></i>Services proposés
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($pharmacy->services as $service)
                                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                    <i class="fas fa-check-circle text-green-500 mr-3"></i>
                                    <span class="text-gray-700">{{ $service }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Map -->
                <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>Localisation
                    </h3>
                    <div id="pharmacy-map" class="w-full h-64 rounded-lg border border-gray-200"></div>
                </div>
                
                <!-- Contact Actions -->
                <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-phone-alt text-green-500 mr-2"></i>Contacter
                    </h3>
                    
                    <div class="space-y-3">
                        <a href="tel:{{ $pharmacy->phone }}" 
                           class="w-full bg-green-500 text-white py-3 px-4 rounded-lg flex items-center justify-center hover:bg-green-600 transition-colors duration-200">
                            <i class="fas fa-phone mr-2"></i>Appeler
                        </a>
                        
                        @if($pharmacy->whatsapp_number)
                            <a href="{{ $pharmacy->whatsapp_url }}" 
                               target="_blank"
                               class="w-full whatsapp-btn text-white py-3 px-4 rounded-lg flex items-center justify-center">
                                <i class="fab fa-whatsapp mr-2"></i>WhatsApp
                            </a>
                        @endif
                        
                        @if($pharmacy->email)
                            <a href="{{ $pharmacy->email_url }}" 
                               class="w-full bg-gray-500 text-white py-3 px-4 rounded-lg flex items-center justify-center hover:bg-gray-600 transition-colors duration-200">
                                <i class="fas fa-envelope mr-2"></i>Email
                            </a>
                        @endif
                    </div>
                </div>
                
                <!-- Quick Info -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-info-circle text-green-500 mr-2"></i>Informations rapides
                    </h3>
                    
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Ville</span>
                            <span class="font-semibold">{{ $pharmacy->city }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Code postal</span>
                            <span class="font-semibold">{{ $pharmacy->postal_code }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Pays</span>
                            <span class="font-semibold">{{ $pharmacy->country }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Statut</span>
                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-sm">Active</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Initialiser la carte pour cette pharmacie
function initPharmacyMap() {
    // Passer les coordonnées explicitement pour éviter les problèmes de sérialisation
    const latitude = {{ $pharmacy->latitude ?? 'null' }};
    const longitude = {{ $pharmacy->longitude ?? 'null' }};
    const pharmacyName = @json($pharmacy->name ?? '');
    const pharmacyAddress = @json($pharmacy->address ?? '');
    
    // Debug: afficher les valeurs dans la console
    console.log('Latitude:', latitude, 'Type:', typeof latitude);
    console.log('Longitude:', longitude, 'Type:', typeof longitude);
    
    // Si les coordonnées ne sont pas valides, utiliser Kinshasa par défaut
    const defaultLat = -4.3276;
    const defaultLng = 15.3136;
    
    // Convertir en nombres si ce sont des chaînes
    const latNum = (latitude !== null && latitude !== undefined) ? parseFloat(latitude) : null;
    const lngNum = (longitude !== null && longitude !== undefined) ? parseFloat(longitude) : null;
    
    const mapLat = (!isNaN(latNum) && latNum !== null) ? latNum : defaultLat;
    const mapLng = (!isNaN(lngNum) && lngNum !== null) ? lngNum : defaultLng;
    
    // Initialiser la carte
    const map = L.map('pharmacy-map').setView([mapLat, mapLng], 15);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    
    // Ajouter un marqueur seulement si les coordonnées sont valides
    if (!isNaN(latNum) && !isNaN(lngNum) && latNum !== null && lngNum !== null) {
        L.marker([latNum, lngNum])
            .addTo(map)
            .bindPopup(`
                <div class="p-2">
                    <h4 class="font-bold">${pharmacyName}</h4>
                    <p class="text-sm text-gray-600">${pharmacyAddress}</p>
                </div>
            `)
            .openPopup();
    } else {
        // Afficher un message si les coordonnées sont manquantes
        L.marker([mapLat, mapLng])
            .addTo(map)
            .bindPopup(`
                <div class="p-2">
                    <h4 class="font-bold">${pharmacyName}</h4>
                    <p class="text-sm text-gray-600">${pharmacyAddress}</p>
                    <p class="text-xs text-yellow-600 mt-1">⚠️ Coordonnées non disponibles (Lat: ${latitude}, Lng: ${longitude})</p>
                </div>
            `)
            .openPopup();
    }
}

// Initialiser la carte au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    // Vérifier que Leaflet est chargé
    if (typeof L !== 'undefined') {
        initPharmacyMap();
    } else {
        console.error('Leaflet n\'est pas chargé');
    }
});
</script>
@endpush
