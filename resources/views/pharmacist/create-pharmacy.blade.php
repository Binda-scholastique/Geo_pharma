@extends('layouts.app')

@section('title', 'Ajouter une pharmacie - GeoPharma')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">
                        <i class="fas fa-plus-circle text-green-500 mr-3"></i>
                        Ajouter une pharmacie
                    </h1>
                    <p class="mt-2 text-gray-600">
                        Remplissez les informations de votre pharmacie pour qu'elle apparaisse sur la carte.
                    </p>
                </div>
                
                <a href="{{ route('pharmacist.dashboard') }}" 
                   class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>Retour
                </a>
            </div>
        </div>
    </div>
    
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <form method="POST" action="{{ route('pharmacist.store-pharmacy') }}" class="space-y-8">
            @csrf
            
            <!-- Informations de base -->
            <div class="bg-white rounded-xl shadow-lg p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">
                    <i class="fas fa-info-circle text-green-500 mr-2"></i>
                    Informations de base
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nom de la pharmacie -->
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nom de la pharmacie *
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}"
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('name') border-red-500 @enderror"
                               placeholder="Ex: Pharmacie du Centre">
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Description
                        </label>
                        <textarea id="description" 
                                  name="description" 
                                  rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('description') border-red-500 @enderror"
                                  placeholder="Décrivez votre pharmacie, ses spécialités, etc.">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Adresse -->
            <div class="bg-white rounded-xl shadow-lg p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">
                    <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>
                    Adresse
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Adresse complète -->
                    <div class="md:col-span-2">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                            Adresse complète *
                        </label>
                        <input type="text" 
                               id="address" 
                               name="address" 
                               value="{{ old('address') }}"
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('address') border-red-500 @enderror"
                               placeholder="Ex: 123 Rue de la Paix">
                        @error('address')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Ville -->
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700 mb-2">
                            Ville *
                        </label>
                        <input type="text" 
                               id="city" 
                               name="city" 
                               value="{{ old('city') }}"
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('city') border-red-500 @enderror"
                               placeholder="Ex: Paris">
                        @error('city')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Code postal -->
                    <div>
                        <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-2">
                            Code postal *
                        </label>
                        <input type="text" 
                               id="postal_code" 
                               name="postal_code" 
                               value="{{ old('postal_code') }}"
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('postal_code') border-red-500 @enderror"
                               placeholder="Ex: 75001">
                        @error('postal_code')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Pays -->
                    <div class="md:col-span-2">
                        <label for="country" class="block text-sm font-medium text-gray-700 mb-2">
                            Pays *
                        </label>
                        <input type="text" 
                               id="country" 
                               name="country" 
                               value="{{ old('country', 'France') }}"
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('country') border-red-500 @enderror"
                               placeholder="Ex: France">
                        @error('country')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Géolocalisation -->
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Géolocalisation *
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="latitude" class="block text-xs text-gray-500 mb-1">Latitude</label>
                            <input type="number" 
                                   id="latitude" 
                                   name="latitude" 
                                   step="any"
                                   value="{{ old('latitude') }}"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('latitude') border-red-500 @enderror"
                                   placeholder="Ex: 48.8566">
                        </div>
                        <div>
                            <label for="longitude" class="block text-xs text-gray-500 mb-1">Longitude</label>
                            <input type="number" 
                                   id="longitude" 
                                   name="longitude" 
                                   step="any"
                                   value="{{ old('longitude') }}"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('longitude') border-red-500 @enderror"
                                   placeholder="Ex: 2.3522">
                        </div>
                    </div>
                    <p class="mt-2 text-sm text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>
                        Utilisez le bouton "Obtenir les coordonnées" pour remplir automatiquement ces champs.
                    </p>
                    <button type="button" 
                            onclick="getCoordinatesFromAddress()"
                            class="mt-2 bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors duration-200">
                        <i class="fas fa-crosshairs mr-2"></i>Obtenir les coordonnées
                    </button>
                    @error('latitude')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @error('longitude')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Contact -->
            <div class="bg-white rounded-xl shadow-lg p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">
                    <i class="fas fa-phone text-green-500 mr-2"></i>
                    Informations de contact
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Téléphone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                            Téléphone *
                        </label>
                        <input type="tel" 
                               id="phone" 
                               name="phone" 
                               value="{{ old('phone') }}"
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('phone') border-red-500 @enderror"
                               placeholder="Ex: 01 23 45 67 89">
                        @error('phone')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('email') border-red-500 @enderror"
                               placeholder="Ex: contact@pharmacie.fr">
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- WhatsApp -->
                    <div class="md:col-span-2">
                        <label for="whatsapp_number" class="block text-sm font-medium text-gray-700 mb-2">
                            Numéro WhatsApp
                        </label>
                        <input type="tel" 
                               id="whatsapp_number" 
                               name="whatsapp_number" 
                               value="{{ old('whatsapp_number') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('whatsapp_number') border-red-500 @enderror"
                               placeholder="Ex: +33 1 23 45 67 89">
                        <p class="mt-2 text-sm text-gray-500">
                            <i class="fab fa-whatsapp mr-1"></i>
                            Les utilisateurs pourront vous contacter directement via WhatsApp
                        </p>
                        @error('whatsapp_number')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Services -->
            <div class="bg-white rounded-xl shadow-lg p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">
                    <i class="fas fa-concierge-bell text-purple-500 mr-2"></i>
                    Services proposés
                </h2>
                
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                        <input type="checkbox" name="services[]" value="Livraison à domicile" class="mr-3">
                        <span class="text-sm font-medium">Livraison à domicile</span>
                    </label>
                    
                    <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                        <input type="checkbox" name="services[]" value="Pharmacie de garde" class="mr-3">
                        <span class="text-sm font-medium">Pharmacie de garde</span>
                    </label>
                    
                    <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                        <input type="checkbox" name="services[]" value="Conseil pharmaceutique" class="mr-3">
                        <span class="text-sm font-medium">Conseil pharmaceutique</span>
                    </label>
                    
                    <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                        <input type="checkbox" name="services[]" value="Préparation magistrale" class="mr-3">
                        <span class="text-sm font-medium">Préparation magistrale</span>
                    </label>
                    
                    <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                        <input type="checkbox" name="services[]" value="Vaccination" class="mr-3">
                        <span class="text-sm font-medium">Vaccination</span>
                    </label>
                    
                    <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                        <input type="checkbox" name="services[]" value="Mesure tension" class="mr-3">
                        <span class="text-sm font-medium">Mesure tension</span>
                    </label>
                </div>
            </div>
            
            <!-- Submit Button -->
            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('pharmacist.dashboard') }}" 
                   class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 transition-colors duration-200">
                    Annuler
                </a>
                <button type="submit" 
                        class="btn-primary text-white px-8 py-3 rounded-lg font-medium">
                    <i class="fas fa-save mr-2"></i>Créer la pharmacie
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Fonction pour obtenir les coordonnées à partir de l'adresse
async function getCoordinatesFromAddress() {
    const address = document.getElementById('address').value;
    const city = document.getElementById('city').value;
    const postalCode = document.getElementById('postal_code').value;
    const country = document.getElementById('country').value;
    
    if (!address || !city || !postalCode) {
        alert('Veuillez remplir au moins l\'adresse, la ville et le code postal.');
        return;
    }
    
    const fullAddress = `${address}, ${postalCode} ${city}, ${country}`;
    
    try {
        // Utiliser l'API de géocodage (ici on simule avec une API gratuite)
        const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(fullAddress)}&limit=1`);
        const data = await response.json();
        
        if (data && data.length > 0) {
            const result = data[0];
            document.getElementById('latitude').value = parseFloat(result.lat).toFixed(6);
            document.getElementById('longitude').value = parseFloat(result.lon).toFixed(6);
            
            // Afficher un message de succès
            const button = event.target;
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-check mr-2"></i>Coordonnées trouvées !';
            button.classList.add('bg-green-500');
            button.classList.remove('bg-green-500');
            
            setTimeout(() => {
                button.innerHTML = originalText;
                button.classList.remove('bg-green-500');
                button.classList.add('bg-green-500');
            }, 2000);
        } else {
            alert('Adresse non trouvée. Veuillez vérifier les informations saisies.');
        }
    } catch (error) {
        console.error('Erreur lors de la géocodage:', error);
        alert('Erreur lors de la recherche de l\'adresse. Veuillez saisir manuellement les coordonnées.');
    }
}

// Validation du formulaire
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    
    form.addEventListener('submit', function(e) {
        const latitude = document.getElementById('latitude').value;
        const longitude = document.getElementById('longitude').value;
        
        if (!latitude || !longitude) {
            e.preventDefault();
            alert('Veuillez obtenir les coordonnées géographiques avant de soumettre le formulaire.');
            return false;
        }
        
        // Vérifier que les coordonnées sont dans des plages valides
        if (latitude < -90 || latitude > 90) {
            e.preventDefault();
            alert('La latitude doit être comprise entre -90 et 90.');
            return false;
        }
        
        if (longitude < -180 || longitude > 180) {
            e.preventDefault();
            alert('La longitude doit être comprise entre -180 et 180.');
            return false;
        }
    });
});
</script>
@endpush
