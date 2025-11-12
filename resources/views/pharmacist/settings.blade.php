@extends('layouts.app')

@section('title', 'Paramètres - GeoPharma')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-green-50">
    <!-- Header avec gradient -->
    <div class="bg-gradient-to-r from-green-600 to-green-700 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-white">
                        <i class="fas fa-cog mr-3"></i>
                        Paramètres
                    </h1>
                    <p class="text-green-100 mt-2">Configurez votre compte et vos préférences</p>
                </div>
                <div class="text-right">
                    <div class="text-green-100 text-sm">Pharmacien</div>
                    <div class="text-white font-semibold">{{ Auth::user()->name }}</div>
                </div>
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
                <span class="text-gray-600 font-medium">Paramètres</span>
            </nav>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Contenu principal -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Notifications -->
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">
                        <i class="fas fa-bell mr-2 text-green-500"></i>Préférences de Notifications
                    </h2>
                    
                    <form method="POST" action="{{ route('pharmacist.settings.notifications') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="space-y-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-800">Notifications par email</h3>
                                    <p class="text-sm text-gray-600">Recevez des notifications par email pour les mises à jour importantes</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="email_notifications" value="1" class="sr-only peer" checked>
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                                </label>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-800">Notifications de vérification</h3>
                                    <p class="text-sm text-gray-600">Être notifié quand vos pharmacies sont vérifiées</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="verification_notifications" value="1" class="sr-only peer" checked>
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                                </label>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-800">Notifications de nouveaux utilisateurs</h3>
                                    <p class="text-sm text-gray-600">Être notifié des nouvelles inscriptions dans votre région</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="user_notifications" value="1" class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                                </label>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end">
                            <button type="submit" class="px-6 py-3 bg-green-500 hover:bg-green-600 text-white rounded-lg transition-colors">
                                <i class="fas fa-save mr-2"></i>Enregistrer les préférences
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Préférences d'affichage -->
                <!-- <div class="bg-white rounded-xl shadow-lg p-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">
                        <i class="fas fa-palette mr-2 text-green-500"></i>Préférences d'Affichage
                    </h2>
                    
                    <form method="POST" action="{{ route('pharmacist.settings.display') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="space-y-6">
                            <div>
                                <label for="theme" class="block text-sm font-medium text-gray-700 mb-2">
                                    Thème
                                </label>
                                <select id="theme" name="theme" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                                    <option value="light">Clair</option>
                                    <option value="dark">Sombre</option>
                                    <option value="auto">Automatique</option>
                                </select>
                            </div>
                            
                            <div>
                                <label for="language" class="block text-sm font-medium text-gray-700 mb-2">
                                    Langue
                                </label>
                                <select id="language" name="language" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                                    <option value="fr">Français</option>
                                    <option value="en">English</option>
                                </select>
                            </div>
                            
                            <div>
                                <label for="timezone" class="block text-sm font-medium text-gray-700 mb-2">
                                    Fuseau horaire
                                </label>
                                <select id="timezone" name="timezone" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                                    <option value="Europe/Paris">Europe/Paris (GMT+1)</option>
                                    <option value="Europe/London">Europe/London (GMT+0)</option>
                                    <option value="America/New_York">America/New_York (GMT-5)</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end">
                            <button type="submit" class="px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors">
                                <i class="fas fa-save mr-2"></i>Enregistrer les préférences
                            </button>
                        </div>
                    </form>
                </div> -->

                <!-- Sécurité -->
                <!-- <div class="bg-white rounded-xl shadow-lg p-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">
                        <i class="fas fa-shield-alt mr-2 text-green-500"></i>Sécurité
                    </h2>
                    
                    <div class="space-y-6">
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <h3 class="text-lg font-medium text-gray-800">Authentification à deux facteurs</h3>
                                <p class="text-sm text-gray-600">Ajoutez une couche de sécurité supplémentaire à votre compte</p>
                            </div>
                            <button class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg transition-colors">
                                Activer
                            </button>
                        </div>
                        
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <h3 class="text-lg font-medium text-gray-800">Sessions actives</h3>
                                <p class="text-sm text-gray-600">Gérez vos sessions de connexion actives</p>
                            </div>
                            <button class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors">
                                Voir les sessions
                            </button>
                        </div>
                        
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <h3 class="text-lg font-medium text-gray-800">Historique de connexion</h3>
                                <p class="text-sm text-gray-600">Consultez l'historique de vos connexions</p>
                            </div>
                            <button class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                                Voir l'historique
                            </button>
                        </div>
                    </div>
                </div> -->
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Navigation -->
                <!-- <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">
                        <i class="fas fa-cog mr-2 text-green-500"></i>Paramètres
                    </h3>
                    
                    <nav class="space-y-2">
                        <a href="#notifications" class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                            <i class="fas fa-bell mr-3 text-gray-400"></i>
                            Notifications
                        </a>
                        <a href="#display" class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                            <i class="fas fa-palette mr-3 text-gray-400"></i>
                            Affichage
                        </a>
                        <a href="#security" class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                            <i class="fas fa-shield-alt mr-3 text-gray-400"></i>
                            Sécurité
                        </a>
                    </nav>
                </div> -->

                <!-- Actions rapides -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">
                        <i class="fas fa-bolt mr-2 text-green-500"></i>Actions Rapides
                    </h3>
                    
                    <div class="space-y-3">
                        <a href="{{ route('pharmacist.profile') }}" class="w-full flex items-center justify-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors">
                            <i class="fas fa-user-edit mr-2"></i>Modifier le profil
                        </a>
                        
                        <a href="{{ route('pharmacist.dashboard') }}" class="w-full flex items-center justify-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                            <i class="fas fa-tachometer-alt mr-2"></i>Retour au dashboard
                        </a>
                    </div>
                </div>

                <!-- Informations -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">
                        <i class="fas fa-info-circle mr-2 text-green-500"></i>Informations
                    </h3>
                    
                    <div class="space-y-3 text-sm text-gray-600">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-2 text-green-500"></i>
                            Compte vérifié
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-shield-alt mr-2 text-blue-500"></i>
                            Sécurité activée
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-bell mr-2 text-yellow-500"></i>
                            Notifications activées
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .min-h-screen {
        min-height: 100vh;
    }
    
    .bg-gradient-to-br {
        background: linear-gradient(to bottom right, var(--tw-gradient-stops));
    }
    
    .from-gray-50 {
        --tw-gradient-from: #f9fafb;
        --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(249, 250, 251, 0));
    }
    
    .to-green-50 {
        --tw-gradient-to: #f0fdf4;
    }
    
    .bg-gradient-to-r {
        background: linear-gradient(to right, var(--tw-gradient-stops));
    }
    
    .from-green-600 {
        --tw-gradient-from: #059669;
        --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(5, 150, 105, 0));
    }
    
    .to-green-700 {
        --tw-gradient-to: #047857;
    }
    
    .text-green-100 {
        color: #dcfce7;
    }
    
    .hover\:text-green-800:hover {
        color: #065f46;
    }
    
    .transition-colors {
        transition-property: color, background-color, border-color, text-decoration-color, fill, stroke;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 150ms;
    }
</style>
@endpush
