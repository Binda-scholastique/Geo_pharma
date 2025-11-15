@extends('layouts.app')

@section('title', 'Dashboard Pharmacien - GeoPharma')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-green-50">
    <!-- Header avec gradient -->
    <div class="bg-gradient-to-r from-green-600 to-green-700 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-white">
                        <i class="fas fa-user-md mr-3"></i>
                        Dashboard Pharmacien
                    </h1>
                    <p class="text-green-100 mt-2 text-lg">
                        @if($pharmacies->count() > 0)
                            Vous gérez {{ $pharmacies->count() }} pharmacie{{ $pharmacies->count() > 1 ? 's' : '' }}
                        @else
                            Gérez vos pharmacies et votre profil
                        @endif
                    </p>
                </div>
                <div class="text-right">
                    <div class="text-green-100 text-sm">
                        @php
                            $hour = date('H');
                            if ($hour < 12) {
                                $greeting = 'Bonjour';
                            } elseif ($hour < 18) {
                                $greeting = 'Bon après-midi';
                            } else {
                                $greeting = 'Bonsoir';
                            }
                        @endphp
                        {{ $greeting }},
                    </div>
                    <div class="text-white font-semibold text-xl">{{ Auth::user()->name }}</div>
                    <div class="text-green-200 text-sm">{{ Auth::user()->email }}</div>
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
                <span class="text-gray-600 font-medium">Dashboard Pharmacien</span>
            </nav>
        </div>
    </div>

    <!-- Alerte profil incomplet -->
    @if(!Auth::user()->profile_completed || !Auth::user()->latitude || !Auth::user()->longitude)
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg shadow-md">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-yellow-400 text-2xl"></i>
                    </div>
                    <div class="ml-3 flex-1">
                        <h3 class="text-sm font-medium text-yellow-800 mb-2">Profil incomplet</h3>
                        <p class="text-sm text-yellow-700 mb-3">Votre profil n'est pas encore complet. Complétez-le pour améliorer votre visibilité.</p>
                        <div class="flex flex-wrap gap-2">
                            @if(!Auth::user()->profile_completed)
                                <a href="{{ route('pharmacist.complete-profile') }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors duration-200 text-sm">
                                    <i class="fas fa-user-edit mr-2"></i>Compléter le profil
                                </a>
                            @endif
                            @if(!Auth::user()->latitude || !Auth::user()->longitude)
                                <a href="{{ route('pharmacist.location') }}" class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors duration-200 text-sm">
                                    <i class="fas fa-map-marker-alt mr-2"></i>Ajouter ma localisation
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Statistiques -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6 flex items-center justify-between border-l-4 border-blue-500 hover:shadow-xl transition-shadow duration-300">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Mes Pharmacies</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $pharmacies->count() }}</p>
                    <p class="text-xs text-gray-500 mt-1">Total enregistrées</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-store text-blue-600 text-2xl"></i>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 flex items-center justify-between border-l-4 border-green-500 hover:shadow-xl transition-shadow duration-300">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Pharmacies Actives</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $pharmacies->where('is_active', true)->count() }}</p>
                    <p class="text-xs text-gray-500 mt-1">En service</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 flex items-center justify-between border-l-4 border-yellow-500 hover:shadow-xl transition-shadow duration-300">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Pharmacies Vérifiées</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $pharmacies->where('is_verified', true)->count() }}</p>
                    <p class="text-xs text-gray-500 mt-1">Approuvées</p>
                </div>
                <div class="bg-yellow-100 p-3 rounded-full">
                    <i class="fas fa-shield-alt text-yellow-600 text-2xl"></i>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 flex items-center justify-between border-l-4 border-red-500 hover:shadow-xl transition-shadow duration-300">
                <div>
                    <p class="text-gray-500 text-sm font-medium">En Attente</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $pharmacies->where('is_verified', false)->count() }}</p>
                    <p class="text-xs text-gray-500 mt-1">À vérifier</p>
                </div>
                <div class="bg-red-100 p-3 rounded-full">
                    <i class="fas fa-clock text-red-600 text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
        <div class="bg-white rounded-xl shadow-lg p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">
                <i class="fas fa-bolt text-green-500 mr-2"></i>Actions Rapides
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <a href="{{ route('pharmacist.create-pharmacy') }}" class="group flex flex-col items-center justify-center p-6 bg-gradient-to-br from-green-50 to-green-100 rounded-lg hover:from-green-100 hover:to-green-200 transition-all duration-300 transform hover:-translate-y-1 shadow-md hover:shadow-lg">
                    <div class="bg-green-500 p-4 rounded-full mb-4 group-hover:bg-green-600 transition-colors">
                        <i class="fas fa-plus text-white text-2xl"></i>
                    </div>
                    <span class="text-lg font-medium text-gray-800 text-center">Ajouter une pharmacie</span>
                    <span class="text-sm text-gray-600 mt-1 text-center">Nouvelle pharmacie</span>
                </a>

                <a href="{{ route('pharmacist.profile') }}" class="group flex flex-col items-center justify-center p-6 bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg hover:from-blue-100 hover:to-blue-200 transition-all duration-300 transform hover:-translate-y-1 shadow-md hover:shadow-lg">
                    <div class="bg-blue-500 p-4 rounded-full mb-4 group-hover:bg-blue-600 transition-colors">
                        <i class="fas fa-user-edit text-white text-2xl"></i>
                    </div>
                    <span class="text-lg font-medium text-gray-800 text-center">Modifier mon profil</span>
                    <span class="text-sm text-gray-600 mt-1 text-center">Informations personnelles</span>
                </a>

                <a href="{{ route('pharmacist.location') }}" class="group flex flex-col items-center justify-center p-6 bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg hover:from-purple-100 hover:to-purple-200 transition-all duration-300 transform hover:-translate-y-1 shadow-md hover:shadow-lg">
                    <div class="bg-purple-500 p-4 rounded-full mb-4 group-hover:bg-purple-600 transition-colors">
                        <i class="fas fa-map-marker-alt text-white text-2xl"></i>
                    </div>
                    <span class="text-lg font-medium text-gray-800 text-center">Ma localisation</span>
                    <span class="text-sm text-gray-600 mt-1 text-center">Position GPS</span>
                </a>

                <a href="{{ route('pharmacist.settings') }}" class="group flex flex-col items-center justify-center p-6 bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-lg hover:from-yellow-100 hover:to-yellow-200 transition-all duration-300 transform hover:-translate-y-1 shadow-md hover:shadow-lg">
                    <div class="bg-yellow-500 p-4 rounded-full mb-4 group-hover:bg-yellow-600 transition-colors">
                        <i class="fas fa-cog text-white text-2xl"></i>
                    </div>
                    <span class="text-lg font-medium text-gray-800 text-center">Paramètres</span>
                    <span class="text-sm text-gray-600 mt-1 text-center">Configuration du compte</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Informations du profil et statistiques -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Informations du Profil -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-800">
                        <i class="fas fa-user-md text-green-500 mr-2"></i>Informations du Profil
                    </h3>
                </div>
                <div class="space-y-4">
                    <div class="flex items-start justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Nom complet</p>
                            <p class="text-base font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                        </div>
                        <i class="fas fa-user text-gray-400 text-xl"></i>
                    </div>
                    
                    <div class="flex items-start justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Email</p>
                            <p class="text-base font-semibold text-gray-900">{{ Auth::user()->email }}</p>
                        </div>
                        <i class="fas fa-envelope text-gray-400 text-xl"></i>
                    </div>
                    
                    <div class="flex items-start justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Numéro d'autorisation</p>
                            <p class="text-base font-semibold text-gray-900">{{ Auth::user()->authorization_number ?? 'Non renseigné' }}</p>
                        </div>
                        <i class="fas fa-key text-gray-400 text-xl"></i>
                    </div>
                    
                    <div class="flex items-start justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Profil complété</p>
                            <div>
                                @if(Auth::user()->profile_completed)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-2"></i>Oui
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-exclamation-triangle mr-2"></i>Non
                                    </span>
                                @endif
                            </div>
                        </div>
                        <i class="fas fa-user-check text-gray-400 text-xl"></i>
                    </div>
                    
                    @if(Auth::user()->latitude && Auth::user()->longitude)
                    <div class="flex items-start justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Localisation GPS</p>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <i class="fas fa-map-marker-alt mr-2"></i>Définie
                            </span>
                        </div>
                        <i class="fas fa-map-marker-alt text-gray-400 text-xl"></i>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Statistiques Rapides -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-800">
                        <i class="fas fa-chart-pie text-green-500 mr-2"></i>Statistiques Rapides
                    </h3>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <p class="text-3xl font-bold text-blue-600 mb-1">{{ $pharmacies->count() }}</p>
                        <p class="text-sm text-gray-600">Total</p>
                    </div>
                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <p class="text-3xl font-bold text-green-600 mb-1">{{ $pharmacies->where('is_verified', true)->count() }}</p>
                        <p class="text-sm text-gray-600">Vérifiées</p>
                    </div>
                    <div class="text-center p-4 bg-yellow-50 rounded-lg">
                        <p class="text-3xl font-bold text-yellow-600 mb-1">{{ $pharmacies->where('is_verified', false)->count() }}</p>
                        <p class="text-sm text-gray-600">En attente</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mes pharmacies -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-800">
                    <i class="fas fa-store text-green-500 mr-2"></i>Mes Pharmacies
                    @if($pharmacies->count() > 0)
                        <span class="ml-2 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            {{ $pharmacies->count() }}
                        </span>
                    @endif
                </h3>
                <a href="{{ route('pharmacist.create-pharmacy') }}" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors duration-200 flex items-center">
                    <i class="fas fa-plus mr-2"></i>Ajouter
                </a>
            </div>
            <div>
            @if($pharmacies->count() > 0)
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200" id="dataTable">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Adresse</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ville</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vérification</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date création</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($pharmacies as $pharmacy)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-green-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-store text-green-600"></i>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $pharmacy->name }}</div>
                                            @if($pharmacy->phone)
                                                <div class="text-sm text-gray-500">{{ $pharmacy->phone }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $pharmacy->address }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $pharmacy->city }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($pharmacy->is_active)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-times-circle mr-1"></i>Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($pharmacy->is_verified)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-shield-alt mr-1"></i>Vérifiée
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-clock mr-1"></i>En attente
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if($pharmacy->created_at)
                                        {{ $pharmacy->created_at->format('d/m/Y H:i') }}
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('pharmacist.edit-pharmacy', $pharmacy->id) }}" 
                                           class="text-yellow-600 hover:text-yellow-900 transition-colors" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('pharmacies.show', $pharmacy->id) }}" 
                                           class="text-blue-600 hover:text-blue-900 transition-colors" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gray-100 mb-4">
                        <i class="fas fa-store text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune pharmacie enregistrée</h3>
                    <p class="text-gray-500 mb-6">Commencez par ajouter votre première pharmacie</p>
                    <a href="{{ route('pharmacist.create-pharmacy') }}" class="inline-flex items-center px-6 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors duration-200">
                        <i class="fas fa-plus mr-2"></i>
                        Ajouter ma première pharmacie
                    </a>
                </div>
            @endif
            </div>
        </div>
    </div>

    <!-- Activité récente -->
    @if($pharmacies->count() > 0)
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Statistiques détaillées -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-800">
                        <i class="fas fa-chart-line text-green-500 mr-2"></i>Statistiques détaillées
                    </h3>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <p class="text-3xl font-bold text-blue-600 mb-1">{{ $pharmacies->count() }}</p>
                        <p class="text-sm text-gray-600">Total pharmacies</p>
                    </div>
                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <p class="text-3xl font-bold text-green-600 mb-1">{{ $pharmacies->where('is_verified', true)->count() }}</p>
                        <p class="text-sm text-gray-600">Vérifiées</p>
                    </div>
                    <div class="text-center p-4 bg-purple-50 rounded-lg">
                        <p class="text-3xl font-bold text-purple-600 mb-1">{{ $pharmacies->where('is_active', true)->count() }}</p>
                        <p class="text-sm text-gray-600">Actives</p>
                    </div>
                    <div class="text-center p-4 bg-yellow-50 rounded-lg">
                        <p class="text-3xl font-bold text-yellow-600 mb-1">{{ $pharmacies->where('is_verified', false)->count() }}</p>
                        <p class="text-sm text-gray-600">En attente</p>
                    </div>
                </div>
            </div>

            <!-- Informations du compte -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-800">
                        <i class="fas fa-info-circle text-green-500 mr-2"></i>Informations du compte
                    </h3>
                </div>
                <div class="space-y-4">
                    <div class="flex items-start justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Nom complet</p>
                            <p class="text-base font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                        </div>
                        <i class="fas fa-user text-gray-400 text-xl"></i>
                    </div>
                    
                    <div class="flex items-start justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Email</p>
                            <p class="text-base font-semibold text-gray-900">{{ Auth::user()->email }}</p>
                        </div>
                        <i class="fas fa-envelope text-gray-400 text-xl"></i>
                    </div>

                    <div class="flex items-start justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Statut du profil</p>
                            <div>
                                @if(Auth::user()->profile_completed)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-2"></i>Complet
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-exclamation-triangle mr-2"></i>Incomplet
                                    </span>
                                @endif
                            </div>
                        </div>
                        <i class="fas fa-user-check text-gray-400 text-xl"></i>
                    </div>

                    <div class="flex items-start justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Membre depuis</p>
                            <p class="text-base font-semibold text-gray-900">{{ Auth::user()->created_at ? Auth::user()->created_at->format('d/m/Y') : '-' }}</p>
                        </div>
                        <i class="fas fa-calendar text-gray-400 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
/* Tableau moderne */
.min-w-full thead th {
    background-color: #f9fafb;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.min-w-full tbody tr:hover {
    background-color: #f9fafb;
}

/* Animations pour les cartes d'actions */
.group:hover {
    transform: translateY(-4px);
}

/* Gradients pour les backgrounds */
.bg-gradient-to-br {
    background: linear-gradient(to bottom right, var(--tw-gradient-stops));
}

.from-green-50 {
    --tw-gradient-from: #f0fdf4;
    --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(240, 253, 244, 0));
}

.to-green-100 {
    --tw-gradient-to: #dcfce7;
}

.from-blue-50 {
    --tw-gradient-from: #eff6ff;
    --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(239, 246, 255, 0));
}

.to-blue-100 {
    --tw-gradient-to: #dbeafe;
}

.from-purple-50 {
    --tw-gradient-from: #faf5ff;
    --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(250, 245, 255, 0));
}

.to-purple-100 {
    --tw-gradient-to: #f3e8ff;
}

.from-yellow-50 {
    --tw-gradient-from: #fefce8;
    --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(254, 252, 232, 0));
}

.to-yellow-100 {
    --tw-gradient-to: #fef9c3;
}
</style>
@endpush