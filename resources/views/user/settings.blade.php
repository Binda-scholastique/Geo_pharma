@extends('layouts.app')

@section('title', 'Paramètres - GeoPharma')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-green-50">
    <!-- Header avec gradient -->
    <div class="bg-gradient-to-r from-green-600 to-green-700 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-white">
                        <i class="fas fa-cog mr-3"></i>
                        Paramètres
                    </h1>
                    <p class="text-green-100 mt-2 text-lg">Personnalisez votre expérience</p>
                </div>
                <a href="{{ route('user.profile') }}" class="bg-white text-green-700 px-4 py-2 rounded-lg hover:bg-green-50 transition-colors duration-200 font-medium shadow-md">
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
                <a href="{{ route('user.profile') }}" class="text-green-600 hover:text-green-800 transition-colors">
                    Profil
                </a>
                <i class="fas fa-chevron-right text-gray-400"></i>
                <span class="text-gray-600 font-medium">Paramètres</span>
            </nav>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Contenu principal -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Notifications -->
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">
                        <i class="fas fa-bell text-green-500 mr-2"></i>Notifications
                    </h2>
                    <form method="POST" action="{{ route('user.settings.notifications') }}" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <h3 class="text-lg font-medium text-gray-800">Notifications par email</h3>
                                <p class="text-sm text-gray-600">Recevez des notifications par email</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="email_notifications" value="1" class="sr-only peer" checked>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                            </label>
                        </div>
                        
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <h3 class="text-lg font-medium text-gray-800">Mises à jour des pharmacies</h3>
                                <p class="text-sm text-gray-600">Soyez informé des nouvelles pharmacies près de chez vous</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="pharmacy_updates" value="1" class="sr-only peer" checked>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                            </label>
                        </div>
                        
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <h3 class="text-lg font-medium text-gray-800">Emails promotionnels</h3>
                                <p class="text-sm text-gray-600">Recevez des offres spéciales et promotions</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="promotional_emails" value="1" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                            </label>
                        </div>
                        
                        <div class="pt-6 border-t border-gray-200">
                            <button type="submit" class="w-full bg-green-500 text-white px-6 py-3 rounded-lg hover:bg-green-600 transition-colors duration-200 flex items-center justify-center">
                                <i class="fas fa-save mr-2"></i>Sauvegarder les préférences
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Préférences d'affichage -->
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">
                        <i class="fas fa-palette text-green-500 mr-2"></i>Préférences d'affichage
                    </h2>
                    <form method="POST" action="{{ route('user.settings.display') }}" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <div>
                            <label for="language" class="block text-sm font-medium text-gray-700 mb-2">Langue</label>
                            <select id="language" 
                                    name="language" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <option value="fr" selected>Français</option>
                                <option value="en">English</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="timezone" class="block text-sm font-medium text-gray-700 mb-2">Fuseau horaire</label>
                            <select id="timezone" 
                                    name="timezone" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <option value="Europe/Paris" selected>Europe/Paris (GMT+1)</option>
                                <option value="Europe/London">Europe/London (GMT+0)</option>
                                <option value="America/New_York">America/New_York (GMT-5)</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="distance_unit" class="block text-sm font-medium text-gray-700 mb-2">Unité de distance</label>
                            <select id="distance_unit" 
                                    name="distance_unit" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <option value="km" selected>Kilomètres</option>
                                <option value="miles">Miles</option>
                            </select>
                        </div>
                        
                        <div class="pt-6 border-t border-gray-200">
                            <button type="submit" class="w-full bg-green-500 text-white px-6 py-3 rounded-lg hover:bg-green-600 transition-colors duration-200 flex items-center justify-center">
                                <i class="fas fa-save mr-2"></i>Sauvegarder les préférences
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Sécurité -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-shield-alt text-green-500 mr-2"></i>Sécurité
                    </h3>
                    <div class="space-y-3">
                        <a href="{{ route('user.profile') }}" class="w-full flex items-center justify-center px-4 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors">
                            <i class="fas fa-user-edit mr-2"></i>Modifier le profil
                        </a>
                        <a href="{{ route('user.profile') }}#password" class="w-full flex items-center justify-center px-4 py-3 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg transition-colors">
                            <i class="fas fa-key mr-2"></i>Changer le mot de passe
                        </a>
                    </div>
                </div>
                
                <!-- Données -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-download text-green-500 mr-2"></i>Données
                    </h3>
                    <p class="text-sm text-gray-600 mb-4">Téléchargez vos données personnelles</p>
                    <button type="button" class="w-full flex items-center justify-center px-4 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors">
                        <i class="fas fa-download mr-2"></i>Exporter mes données
                    </button>
                </div>
                
                <!-- Actions rapides -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-bolt text-green-500 mr-2"></i>Actions rapides
                    </h3>
                    <div class="space-y-3">
                        <a href="{{ route('pharmacies.index') }}" class="w-full flex items-center justify-center px-4 py-3 bg-green-500 hover:bg-green-600 text-white rounded-lg transition-colors">
                            <i class="fas fa-search mr-2"></i>Rechercher des pharmacies
                        </a>
                        <a href="{{ route('user.profile') }}" class="w-full flex items-center justify-center px-4 py-3 bg-purple-500 hover:bg-purple-600 text-white rounded-lg transition-colors">
                            <i class="fas fa-user mr-2"></i>Voir mon profil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
