@extends('layouts.app')

@section('title', 'Modifier une pharmacie - GeoPharma')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-green-50">
    <!-- Header avec gradient -->
    <div class="bg-gradient-to-r from-green-600 to-green-700 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-white">
                        <i class="fas fa-edit mr-3"></i>
                        Modifier une pharmacie
                    </h1>
                    <p class="text-green-100 mt-2 text-lg">
                        Mettez à jour les informations de votre pharmacie
                    </p>
                </div>
                
                <a href="{{ route('pharmacist.dashboard') }}" 
                   class="bg-white text-green-700 px-4 py-2 rounded-lg hover:bg-green-50 transition-colors duration-200 font-medium shadow-md">
                    <i class="fas fa-arrow-left mr-2"></i>Retour
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
                <a href="{{ route('pharmacist.dashboard') }}" class="text-green-600 hover:text-green-800 transition-colors">
                    Dashboard
                </a>
                <i class="fas fa-chevron-right text-gray-400"></i>
                <span class="text-gray-600 font-medium">Modifier pharmacie</span>
            </nav>
        </div>
    </div>
    
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <form method="POST" action="{{ route('pharmacist.update-pharmacy', $pharmacy->id) }}" class="space-y-8" id="pharmacyForm">
            @csrf
            @method('PUT')
            
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
                               value="{{ old('name', $pharmacy->name) }}"
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('name') border-red-500 @enderror"
                               placeholder="Ex: Pharmacie du Centre-Ville">
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
                                  placeholder="Décrivez votre pharmacie, ses spécialités, etc.">{{ old('description', $pharmacy->description) }}</textarea>
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
                               value="{{ old('address', $pharmacy->address) }}"
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('address') border-red-500 @enderror"
                               placeholder="Ex: Avenue Kasa-Vubu, Gombe">
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
                               value="{{ old('city', $pharmacy->city) }}"
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('city') border-red-500 @enderror"
                               placeholder="Ex: Kinshasa">
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
                               value="{{ old('postal_code', $pharmacy->postal_code) }}"
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('postal_code') border-red-500 @enderror"
                               placeholder="Ex: 001">
                        @error('postal_code')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Pays -->
                    <div class="md:col-span-2">
                        <label for="country" class="block text-sm font-medium text-gray-700 mb-2">
                            Pays *
                        </label>
                        <select id="country" 
                                name="country" 
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('country') border-red-500 @enderror">
                            <option value="">Sélectionnez un pays</option>
                            <option value="RD Congo" {{ old('country', $pharmacy->country) == 'RD Congo' ? 'selected' : '' }}>RD Congo</option>
                        </select>
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
                    
                    <!-- Carte interactive pour sélectionner l'emplacement -->
                    <div class="mb-4">
                        <div class="bg-gray-100 rounded-lg p-2 mb-2">
                            <p class="text-sm text-gray-600 mb-2">
                                <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>
                                <strong>Cliquez sur la carte</strong> pour modifier l'emplacement de votre pharmacie
                            </p>
                            <div id="location-map" class="w-full h-96 rounded-lg border-2 border-gray-300" style="z-index: 1;"></div>
                        </div>
                        
                        <!-- Boutons d'aide -->
                        <div class="flex flex-wrap gap-2 mt-2">
                            <button type="button" 
                                    onclick="getCurrentLocation()"
                                    class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors duration-200 text-sm">
                                <i class="fas fa-crosshairs mr-2"></i>Ma position actuelle
                            </button>
                            <button type="button" 
                                    onclick="searchAddressOnMap()"
                                    class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors duration-200 text-sm">
                                <i class="fas fa-search mr-2"></i>Rechercher une adresse
                            </button>
                            <button type="button" 
                                    onclick="centerMapOnKinshasa()"
                                    class="bg-purple-500 text-white px-4 py-2 rounded-lg hover:bg-purple-600 transition-colors duration-200 text-sm">
                                <i class="fas fa-map mr-2"></i>Centrer sur Kinshasa
                            </button>
                        </div>
                    </div>
                    
                    <!-- Champs latitude et longitude -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="latitude" class="block text-xs text-gray-500 mb-1">
                                Latitude <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                   id="latitude" 
                                   name="latitude" 
                                   step="any"
                                   value="{{ old('latitude', $pharmacy->latitude) }}"
                                   required
                                   readonly
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('latitude') border-red-500 @enderror"
                                   placeholder="Sélectionnez sur la carte">
                            <p class="mt-1 text-xs text-gray-500">Rempli automatiquement depuis la carte</p>
                        </div>
                        <div>
                            <label for="longitude" class="block text-xs text-gray-500 mb-1">
                                Longitude <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                   id="longitude" 
                                   name="longitude" 
                                   step="any"
                                   value="{{ old('longitude', $pharmacy->longitude) }}"
                                   required
                                   readonly
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('longitude') border-red-500 @enderror"
                                   placeholder="Sélectionnez sur la carte">
                            <p class="mt-1 text-xs text-gray-500">Rempli automatiquement depuis la carte</p>
                        </div>
                    </div>
                    
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
                               value="{{ old('phone', $pharmacy->phone) }}"
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('phone') border-red-500 @enderror"
                               placeholder="Ex: +243 999 123 456">
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
                               value="{{ old('email', $pharmacy->email) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('email') border-red-500 @enderror"
                               placeholder="Ex: contact@pharmacie.cd">
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
                               value="{{ old('whatsapp_number', $pharmacy->whatsapp_number) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('whatsapp_number') border-red-500 @enderror"
                               placeholder="Ex: +243 999 123 456">
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
            
            <!-- Horaires d'ouverture -->
            <div class="bg-white rounded-xl shadow-lg p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">
                    <i class="fas fa-clock text-orange-500 mr-2"></i>
                    Horaires d'ouverture
                </h2>
                
                <p class="text-gray-600 mb-6 text-sm">
                    Définissez les horaires d'ouverture de votre pharmacie pour chaque jour de la semaine. Vous pouvez définir des horaires séparés pour le matin et l'après-midi, ou marquer un jour comme fermé.
                </p>
                
                <div class="space-y-4" id="opening-hours-container">
                    @php
                        $days = [
                            'lundi' => 'Lundi',
                            'mardi' => 'Mardi',
                            'mercredi' => 'Mercredi',
                            'jeudi' => 'Jeudi',
                            'vendredi' => 'Vendredi',
                            'samedi' => 'Samedi',
                            'dimanche' => 'Dimanche'
                        ];
                        $existingHours = is_array($pharmacy->opening_hours) ? $pharmacy->opening_hours : [];
                    @endphp
                    
                    @foreach($days as $dayKey => $dayName)
                        @php
                            $dayData = $existingHours[$dayKey] ?? null;
                            $isOpen = $dayData !== null;
                            $isSeparated = $isOpen && (isset($dayData['morning']) || isset($dayData['afternoon']));
                            $simpleStart = $isOpen && !$isSeparated ? ($dayData['start'] ?? '08:00') : '08:00';
                            $simpleEnd = $isOpen && !$isSeparated ? ($dayData['end'] ?? '18:00') : '18:00';
                            $morningStart = $isSeparated ? ($dayData['morning']['start'] ?? '08:00') : '08:00';
                            $morningEnd = $isSeparated ? ($dayData['morning']['end'] ?? '12:00') : '12:00';
                            $afternoonStart = $isSeparated ? ($dayData['afternoon']['start'] ?? '14:00') : '14:00';
                            $afternoonEnd = $isSeparated ? ($dayData['afternoon']['end'] ?? '18:00') : '18:00';
                        @endphp
                        <div class="border border-gray-200 rounded-lg p-4 day-hours-row {{ !$isOpen ? 'opacity-50' : '' }}" data-day="{{ $dayKey }}">
                            <div class="flex items-center justify-between mb-3">
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" 
                                           class="day-open-checkbox mr-2" 
                                           data-day="{{ $dayKey }}"
                                           {{ $isOpen ? 'checked' : '' }}>
                                    <span class="font-semibold text-gray-700">{{ $dayName }}</span>
                                </label>
                                <button type="button" 
                                        class="text-sm text-green-600 hover:text-green-800 toggle-hours-type"
                                        data-day="{{ $dayKey }}">
                                    <i class="fas fa-exchange-alt mr-1"></i>{{ $isSeparated ? 'Horaires simples' : 'Horaires séparés' }}
                                </button>
                            </div>
                            
                            <!-- Mode simple (un seul créneau) -->
                            <div class="simple-hours {{ $isSeparated ? 'hidden' : '' }}" data-day="{{ $dayKey }}">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs text-gray-500 mb-1">Heure d'ouverture</label>
                                        <input type="time" 
                                               name="opening_hours[{{ $dayKey }}][start]" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                               value="{{ old("opening_hours.{$dayKey}.start", $simpleStart) }}"
                                               {{ !$isOpen ? 'disabled' : '' }}>
                                    </div>
                                    <div>
                                        <label class="block text-xs text-gray-500 mb-1">Heure de fermeture</label>
                                        <input type="time" 
                                               name="opening_hours[{{ $dayKey }}][end]" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                               value="{{ old("opening_hours.{$dayKey}.end", $simpleEnd) }}"
                                               {{ !$isOpen ? 'disabled' : '' }}>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Mode séparé (matin et après-midi) -->
                            <div class="separated-hours {{ !$isSeparated ? 'hidden' : '' }}" data-day="{{ $dayKey }}">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs text-gray-500 mb-2 font-medium">Matin</label>
                                        <div class="grid grid-cols-2 gap-2">
                                            <div>
                                                <label class="block text-xs text-gray-400 mb-1">Ouverture</label>
                                                <input type="time" 
                                                       name="opening_hours[{{ $dayKey }}][morning][start]" 
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                                       value="{{ old("opening_hours.{$dayKey}.morning.start", $morningStart) }}"
                                                       {{ !$isOpen ? 'disabled' : '' }}>
                                            </div>
                                            <div>
                                                <label class="block text-xs text-gray-400 mb-1">Fermeture</label>
                                                <input type="time" 
                                                       name="opening_hours[{{ $dayKey }}][morning][end]" 
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                                       value="{{ old("opening_hours.{$dayKey}.morning.end", $morningEnd) }}"
                                                       {{ !$isOpen ? 'disabled' : '' }}>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs text-gray-500 mb-2 font-medium">Après-midi</label>
                                        <div class="grid grid-cols-2 gap-2">
                                            <div>
                                                <label class="block text-xs text-gray-400 mb-1">Ouverture</label>
                                                <input type="time" 
                                                       name="opening_hours[{{ $dayKey }}][afternoon][start]" 
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                                       value="{{ old("opening_hours.{$dayKey}.afternoon.start", $afternoonStart) }}"
                                                       {{ !$isOpen ? 'disabled' : '' }}>
                                            </div>
                                            <div>
                                                <label class="block text-xs text-gray-400 mb-1">Fermeture</label>
                                                <input type="time" 
                                                       name="opening_hours[{{ $dayKey }}][afternoon][end]" 
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                                       value="{{ old("opening_hours.{$dayKey}.afternoon.end", $afternoonEnd) }}"
                                                       {{ !$isOpen ? 'disabled' : '' }}>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Services -->
            <div class="bg-white rounded-xl shadow-lg p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">
                    <i class="fas fa-concierge-bell text-purple-500 mr-2"></i>
                    Services proposés
                </h2>
                
                @php
                    $existingServices = is_array($pharmacy->services) ? $pharmacy->services : [];
                @endphp
                
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 {{ in_array('Livraison à domicile', $existingServices) ? 'bg-green-50 border-green-300' : '' }}">
                        <input type="checkbox" name="services[]" value="Livraison à domicile" class="mr-3" {{ in_array('Livraison à domicile', old('services', $existingServices)) ? 'checked' : '' }}>
                        <span class="text-sm font-medium">Livraison à domicile</span>
                    </label>
                    
                    <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 {{ in_array('Pharmacie de garde', $existingServices) ? 'bg-green-50 border-green-300' : '' }}">
                        <input type="checkbox" name="services[]" value="Pharmacie de garde" class="mr-3" {{ in_array('Pharmacie de garde', old('services', $existingServices)) ? 'checked' : '' }}>
                        <span class="text-sm font-medium">Pharmacie de garde</span>
                    </label>
                    
                    <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 {{ in_array('Conseil pharmaceutique', $existingServices) ? 'bg-green-50 border-green-300' : '' }}">
                        <input type="checkbox" name="services[]" value="Conseil pharmaceutique" class="mr-3" {{ in_array('Conseil pharmaceutique', old('services', $existingServices)) ? 'checked' : '' }}>
                        <span class="text-sm font-medium">Conseil pharmaceutique</span>
                    </label>
                    
                    <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 {{ in_array('Préparation magistrale', $existingServices) ? 'bg-green-50 border-green-300' : '' }}">
                        <input type="checkbox" name="services[]" value="Préparation magistrale" class="mr-3" {{ in_array('Préparation magistrale', old('services', $existingServices)) ? 'checked' : '' }}>
                        <span class="text-sm font-medium">Préparation magistrale</span>
                    </label>
                    
                    <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 {{ in_array('Vaccination', $existingServices) ? 'bg-green-50 border-green-300' : '' }}">
                        <input type="checkbox" name="services[]" value="Vaccination" class="mr-3" {{ in_array('Vaccination', old('services', $existingServices)) ? 'checked' : '' }}>
                        <span class="text-sm font-medium">Vaccination</span>
                    </label>
                    
                    <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 {{ in_array('Mesure tension', $existingServices) ? 'bg-green-50 border-green-300' : '' }}">
                        <input type="checkbox" name="services[]" value="Mesure tension" class="mr-3" {{ in_array('Mesure tension', old('services', $existingServices)) ? 'checked' : '' }}>
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
                        class="bg-green-500 text-white px-8 py-3 rounded-lg hover:bg-green-600 transition-colors duration-200 font-medium">
                    <i class="fas fa-save mr-2"></i>Mettre à jour la pharmacie
                </button>
            </div>
        </form>
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
                <button id="modal-cancel-btn" onclick="closeModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors hidden">
                    Annuler
                </button>
                <button id="modal-confirm-btn" onclick="confirmModal()" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors hidden">
                    Confirmer
                </button>
                <button id="modal-ok-btn" onclick="closeModal()" class="px-6 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                    OK
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour la recherche d'adresse -->
<div id="search-modal-overlay" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-900">
                    <i class="fas fa-search text-green-500 mr-2"></i>
                    Rechercher une adresse
                </h3>
                <button onclick="closeSearchModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="mb-6">
                <label for="search-address-input" class="block text-sm font-medium text-gray-700 mb-2">
                    Entrez l'adresse à rechercher
                </label>
                <input type="text" 
                       id="search-address-input" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                       placeholder="Ex: Avenue Kasa-Vubu, Gombe, Kinshasa">
            </div>
            <div class="flex justify-end space-x-3">
                <button onclick="closeSearchModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    Annuler
                </button>
                <button onclick="performAddressSearch()" class="px-6 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                    <i class="fas fa-search mr-2"></i>Rechercher
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let map;
let marker;
let selectedLocation = null;

// Initialiser la carte au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    initMap();
    
    // Utiliser les coordonnées existantes de la pharmacie
    const lat = parseFloat(document.getElementById('latitude').value) || -4.3276;
    const lng = parseFloat(document.getElementById('longitude').value) || 15.3136;
    setMapLocation(lat, lng);
});

// Initialiser la carte Leaflet
function initMap() {
    // Centrer sur les coordonnées de la pharmacie ou Kinshasa par défaut
    const lat = parseFloat(document.getElementById('latitude').value) || -4.3276;
    const lng = parseFloat(document.getElementById('longitude').value) || 15.3136;
    
    map = L.map('location-map').setView([lat, lng], 13);
    
    // Ajouter les tuiles OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);
    
    // Gérer le clic sur la carte
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
        });
    }
    
    // Centrer la carte sur la position
    map.setView([lat, lng], 15);
    
    // Mettre à jour les coordonnées dans les champs
    updateCoordinates(lat, lng);
    
    // Mettre à jour le popup
    marker.bindPopup(`<strong>Emplacement sélectionné</strong><br>Lat: ${lat.toFixed(6)}<br>Lng: ${lng.toFixed(6)}`).openPopup();
}

// Mettre à jour les champs latitude et longitude
function updateCoordinates(lat, lng) {
    document.getElementById('latitude').value = lat.toFixed(8);
    document.getElementById('longitude').value = lng.toFixed(8);
    selectedLocation = { lat, lng };
}

// Obtenir la position actuelle de l'utilisateur
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
                showModal('Géolocalisation impossible', 'Impossible d\'obtenir votre position. Veuillez autoriser la géolocalisation dans les paramètres de votre navigateur ou sélectionner manuellement l\'emplacement sur la carte.', 'error');
            }
        );
    } else {
        showModal('Géolocalisation non supportée', 'La géolocalisation n\'est pas supportée par votre navigateur. Veuillez sélectionner manuellement l\'emplacement sur la carte.', 'error');
    }
}

// Rechercher une adresse et centrer la carte dessus
async function searchAddressOnMap() {
    const address = document.getElementById('address').value;
    const city = document.getElementById('city').value;
    const postalCode = document.getElementById('postal_code').value;
    const country = document.getElementById('country').value || 'RD Congo';
    
    if (!address || !city) {
        openSearchModal();
        return;
    }
    
    const fullAddress = `${address}, ${postalCode} ${city}, ${country}`;
    await performSearch(fullAddress);
}

// Ouvrir le modal de recherche
function openSearchModal() {
    document.getElementById('search-modal-overlay').classList.remove('hidden');
    document.getElementById('search-address-input').focus();
}

// Fermer le modal de recherche
function closeSearchModal() {
    document.getElementById('search-modal-overlay').classList.add('hidden');
    document.getElementById('search-address-input').value = '';
}

// Effectuer la recherche depuis le modal
async function performAddressSearch() {
    const searchQuery = document.getElementById('search-address-input').value.trim();
    if (!searchQuery) {
        showModal('Erreur', 'Veuillez entrer une adresse à rechercher.', 'error');
        return;
    }
    
    closeSearchModal();
    const fullQuery = searchQuery + ', Kinshasa, RD Congo';
    await performSearch(fullQuery);
}

// Effectuer la recherche d'adresse
async function performSearch(query) {
    try {
        showModal('Recherche en cours...', 'Recherche de l\'adresse en cours, veuillez patienter.', 'info', false);
        
        const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=1`);
        const data = await response.json();
        
        closeModal();
        
        if (data && data.length > 0) {
            const result = data[0];
            const lat = parseFloat(result.lat);
            const lng = parseFloat(result.lon);
            setMapLocation(lat, lng);
            showMessage('Adresse trouvée sur la carte !', 'success');
        } else {
            showModal('Adresse non trouvée', 'L\'adresse recherchée n\'a pas été trouvée. Veuillez essayer avec d\'autres mots-clés ou sélectionner manuellement sur la carte.', 'error');
        }
    } catch (error) {
        console.error('Erreur lors de la recherche:', error);
        closeModal();
        showModal('Erreur', 'Une erreur est survenue lors de la recherche de l\'adresse. Veuillez réessayer.', 'error');
    }
}

// Centrer la carte sur Kinshasa
function centerMapOnKinshasa() {
    setMapLocation(-4.3276, 15.3136);
    showMessage('Carte centrée sur Kinshasa', 'info');
}

// Variables pour le modal
let modalCallback = null;

// Afficher un modal
function showModal(title, message, type = 'info', showOk = true) {
    const overlay = document.getElementById('modal-overlay');
    const modalTitle = document.getElementById('modal-title');
    const modalMessage = document.getElementById('modal-message');
    const modalIcon = document.getElementById('modal-icon-class');
    const okBtn = document.getElementById('modal-ok-btn');
    const cancelBtn = document.getElementById('modal-cancel-btn');
    const confirmBtn = document.getElementById('modal-confirm-btn');
    
    modalTitle.textContent = title;
    modalMessage.textContent = message;
    
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
    
    okBtn.classList.toggle('hidden', !showOk);
    cancelBtn.classList.add('hidden');
    confirmBtn.classList.add('hidden');
    
    overlay.classList.remove('hidden');
}

// Fermer le modal
function closeModal() {
    document.getElementById('modal-overlay').classList.add('hidden');
    modalCallback = null;
}

// Confirmer dans le modal
function confirmModal() {
    if (modalCallback) {
        modalCallback();
    }
    closeModal();
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

// Initialisation des modals
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('modal-overlay').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
    
    document.getElementById('search-modal-overlay').addEventListener('click', function(e) {
        if (e.target === this) {
            closeSearchModal();
        }
    });
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
            closeSearchModal();
        }
    });
    
    document.getElementById('search-address-input').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            performAddressSearch();
        }
    });
    
    // Validation du formulaire
    const form = document.getElementById('pharmacyForm');
    
    form.addEventListener('submit', function(e) {
        const latitude = document.getElementById('latitude').value;
        const longitude = document.getElementById('longitude').value;
        
        if (!latitude || !longitude) {
            e.preventDefault();
            showModal('Emplacement requis', 'Veuillez sélectionner un emplacement sur la carte avant de soumettre le formulaire.', 'error');
            return false;
        }
        
        const latNum = parseFloat(latitude);
        const lngNum = parseFloat(longitude);
        
        if (isNaN(latNum) || latNum < -90 || latNum > 90) {
            e.preventDefault();
            showModal('Coordonnées invalides', 'La latitude doit être comprise entre -90 et 90.', 'error');
            return false;
        }
        
        if (isNaN(lngNum) || lngNum < -180 || lngNum > 180) {
            e.preventDefault();
            showModal('Coordonnées invalides', 'La longitude doit être comprise entre -180 et 180.', 'error');
            return false;
        }
        
        // Formater les horaires avant la soumission
        formatOpeningHours();
    });
    
    // Gestion des horaires d'ouverture
    initOpeningHours();
    
    // Afficher les messages flash
    @if(session('success'))
        showMessage('{{ session('success') }}', 'success');
    @endif
    
    @if(session('error'))
        showMessage('{{ session('error') }}', 'error');
    @endif
});

// Initialiser la gestion des horaires
function initOpeningHours() {
    // Gérer les checkboxes pour ouvrir/fermer un jour
    document.querySelectorAll('.day-open-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const day = this.dataset.day;
            const dayRow = document.querySelector(`.day-hours-row[data-day="${day}"]`);
            const inputs = dayRow.querySelectorAll('input[type="time"]');
            
            if (this.checked) {
                dayRow.classList.remove('opacity-50');
                inputs.forEach(input => input.disabled = false);
            } else {
                dayRow.classList.add('opacity-50');
                inputs.forEach(input => {
                    input.disabled = true;
                    input.value = '';
                });
            }
        });
    });
    
    // Gérer le basculement entre mode simple et séparé
    document.querySelectorAll('.toggle-hours-type').forEach(button => {
        button.addEventListener('click', function() {
            const day = this.dataset.day;
            const simpleHours = document.querySelector(`.simple-hours[data-day="${day}"]`);
            const separatedHours = document.querySelector(`.separated-hours[data-day="${day}"]`);
            const checkbox = document.querySelector(`.day-open-checkbox[data-day="${day}"]`);
            
            if (!checkbox.checked) {
                showMessage('Veuillez d\'abord activer ce jour', 'warning');
                return;
            }
            
            if (simpleHours.classList.contains('hidden')) {
                // Passer en mode simple
                simpleHours.classList.remove('hidden');
                separatedHours.classList.add('hidden');
                this.innerHTML = '<i class="fas fa-exchange-alt mr-1"></i>Horaires séparés';
            } else {
                // Passer en mode séparé
                simpleHours.classList.add('hidden');
                separatedHours.classList.remove('hidden');
                this.innerHTML = '<i class="fas fa-exchange-alt mr-1"></i>Horaires simples';
            }
        });
    });
}

// Formater les horaires avant la soumission
function formatOpeningHours() {
    const days = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'];
    const formattedHours = {};
    
    days.forEach(day => {
        const checkbox = document.querySelector(`.day-open-checkbox[data-day="${day}"]`);
        
        if (!checkbox || !checkbox.checked) {
            // Jour fermé, ne pas inclure
            return;
        }
        
        const simpleHours = document.querySelector(`.simple-hours[data-day="${day}"]`);
        const separatedHours = document.querySelector(`.separated-hours[data-day="${day}"]`);
        
        if (separatedHours && !separatedHours.classList.contains('hidden')) {
            // Mode séparé
            const morningStart = separatedHours.querySelector(`input[name="opening_hours[${day}][morning][start]"]`).value;
            const morningEnd = separatedHours.querySelector(`input[name="opening_hours[${day}][morning][end]"]`).value;
            const afternoonStart = separatedHours.querySelector(`input[name="opening_hours[${day}][afternoon][start]"]`).value;
            const afternoonEnd = separatedHours.querySelector(`input[name="opening_hours[${day}][afternoon][end]"]`).value;
            
            if (morningStart && morningEnd && afternoonStart && afternoonEnd) {
                formattedHours[day] = {
                    morning: {
                        start: morningStart,
                        end: morningEnd
                    },
                    afternoon: {
                        start: afternoonStart,
                        end: afternoonEnd
                    }
                };
            }
        } else if (simpleHours && !simpleHours.classList.contains('hidden')) {
            // Mode simple
            const start = simpleHours.querySelector(`input[name="opening_hours[${day}][start]"]`).value;
            const end = simpleHours.querySelector(`input[name="opening_hours[${day}][end]"]`).value;
            
            if (start && end) {
                formattedHours[day] = {
                    start: start,
                    end: end
                };
            }
        }
    });
    
    // Créer un champ caché avec les horaires formatés
    let hiddenInput = document.getElementById('formatted-opening-hours');
    if (!hiddenInput) {
        hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.id = 'formatted-opening-hours';
        hiddenInput.name = 'opening_hours';
        document.querySelector('form').appendChild(hiddenInput);
    }
    
    hiddenInput.value = JSON.stringify(formattedHours);
    
    // Désactiver les champs originaux pour qu'ils ne soient pas envoyés
    document.querySelectorAll('input[name^="opening_hours["]').forEach(input => {
        input.disabled = true;
    });
}
</script>

<style>
#location-map {
    cursor: crosshair;
}

.leaflet-container {
    font-family: 'Inter', sans-serif;
}
</style>
@endpush

