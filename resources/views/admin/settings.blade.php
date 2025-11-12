@extends('layouts.app')

@section('title', 'Paramètres Administrateur - GeoPharma')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-green-50">
    <!-- Header avec gradient -->
    <div class="bg-gradient-to-r from-green-600 to-green-700 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-white">
                        <i class="fas fa-cog mr-3"></i>
                        Paramètres Administrateur
                    </h1>
                    <p class="text-green-100 mt-2 text-lg">Configuration du système</p>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="bg-white text-green-700 px-4 py-2 rounded-lg hover:bg-green-50 transition-colors duration-200 font-medium shadow-md">
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
                <a href="{{ route('admin.dashboard') }}" class="text-green-600 hover:text-green-800 transition-colors">
                    Administration
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
                <!-- Configuration système -->
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">
                        <i class="fas fa-server text-green-500 mr-2"></i>Configuration Système
                    </h2>
                    <form method="POST" action="{{ route('admin.settings.system') }}" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <div>
                            <label for="site_name" class="block text-sm font-medium text-gray-700 mb-2">Nom du site</label>
                            <input type="text" 
                                   id="site_name" 
                                   name="site_name" 
                                   value="GeoPharma"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        </div>
                        
                        <div>
                            <label for="default_radius" class="block text-sm font-medium text-gray-700 mb-2">Rayon de recherche par défaut (km)</label>
                            <input type="number" 
                                   id="default_radius" 
                                   name="default_radius" 
                                   value="10" 
                                   min="1" 
                                   max="100"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        </div>
                        
                        <div>
                            <label for="max_pharmacies_per_user" class="block text-sm font-medium text-gray-700 mb-2">Nombre maximum de pharmacies par pharmacien</label>
                            <input type="number" 
                                   id="max_pharmacies_per_user" 
                                   name="max_pharmacies_per_user" 
                                   value="5" 
                                   min="1" 
                                   max="50"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        </div>
                        
                        <div class="pt-6 border-t border-gray-200">
                            <button type="submit" class="w-full bg-green-500 text-white px-6 py-3 rounded-lg hover:bg-green-600 transition-colors duration-200 flex items-center justify-center">
                                <i class="fas fa-save mr-2"></i>Sauvegarder la configuration
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Notifications administrateur -->
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">
                        <i class="fas fa-bell text-green-500 mr-2"></i>Notifications Administrateur
                    </h2>
                    <form method="POST" action="{{ route('admin.settings.notifications') }}" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <h3 class="text-lg font-medium text-gray-800">Notifications nouveaux utilisateurs</h3>
                                <p class="text-sm text-gray-600">Recevez une notification lors de l'inscription d'un nouvel utilisateur</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="new_user_notifications" value="1" class="sr-only peer" checked>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                            </label>
                        </div>
                        
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <h3 class="text-lg font-medium text-gray-800">Notifications nouvelles pharmacies</h3>
                                <p class="text-sm text-gray-600">Recevez une notification lors de l'ajout d'une nouvelle pharmacie</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="new_pharmacy_notifications" value="1" class="sr-only peer" checked>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                            </label>
                        </div>
                        
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <h3 class="text-lg font-medium text-gray-800">Alertes système</h3>
                                <p class="text-sm text-gray-600">Recevez des alertes en cas de problème système</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="system_alerts" value="1" class="sr-only peer" checked>
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
            </div>
            
            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Maintenance -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-database text-green-500 mr-2"></i>Maintenance
                    </h3>
                    <div class="space-y-3">
                        <button type="button" class="w-full flex items-center justify-center px-4 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors">
                            <i class="fas fa-download mr-2"></i>Sauvegarde de la base de données
                        </button>
                        <button type="button" class="w-full flex items-center justify-center px-4 py-3 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg transition-colors">
                            <i class="fas fa-broom mr-2"></i>Nettoyer le cache
                        </button>
                        <button type="button" class="w-full flex items-center justify-center px-4 py-3 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                            <i class="fas fa-sync mr-2"></i>Optimiser la base de données
                        </button>
                    </div>
                </div>
                
                <!-- Statistiques rapides -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-chart-line text-green-500 mr-2"></i>Statistiques rapides
                    </h3>
                    <div class="grid grid-cols-2 gap-4 text-center">
                        <div>
                            <div class="text-2xl font-bold text-blue-600">{{ \App\Models\User::where('role', 'user')->count() }}</div>
                            <div class="text-xs text-gray-500 mt-1">Utilisateurs</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-green-600">{{ \App\Models\User::where('role', 'pharmacist')->count() }}</div>
                            <div class="text-xs text-gray-500 mt-1">Pharmaciens</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-purple-600">{{ \App\Models\Pharmacy::where('is_verified', true)->count() }}</div>
                            <div class="text-xs text-gray-500 mt-1">Vérifiées</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-yellow-600">{{ \App\Models\Pharmacy::where('is_verified', false)->count() }}</div>
                            <div class="text-xs text-gray-500 mt-1">En attente</div>
                        </div>
                    </div>
                </div>
                
                <!-- Sécurité -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-shield-alt text-green-500 mr-2"></i>Sécurité
                    </h3>
                    <div class="space-y-3">
                        <a href="{{ route('admin.profile') }}" class="w-full flex items-center justify-center px-4 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors">
                            <i class="fas fa-user-edit mr-2"></i>Modifier le profil
                        </a>
                        <a href="{{ route('admin.profile') }}#password" class="w-full flex items-center justify-center px-4 py-3 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg transition-colors">
                            <i class="fas fa-key mr-2"></i>Changer le mot de passe
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
