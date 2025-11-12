@extends('layouts.app')

@section('title', 'Tableau de bord - GeoPharma')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-green-50">
    <!-- Header avec gradient -->
    <div class="bg-gradient-to-r from-green-600 to-green-700 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-white">
                        <i class="fas fa-tachometer-alt mr-3"></i>
                        Tableau de bord
                    </h1>
                    <p class="text-green-100 mt-2 text-lg">Bienvenue sur GeoPharma</p>
                </div>
                <div class="text-right">
                    <div class="text-green-100 text-sm">Bienvenue,</div>
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
                <span class="text-gray-600 font-medium">Tableau de bord</span>
            </nav>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if (session('status'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6" role="alert">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span>{{ session('status') }}</span>
                </div>
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6 flex items-center justify-between border-l-4 border-blue-500 hover:shadow-xl transition-shadow duration-300">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Pharmacies Disponibles</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $totalPharmacies }}</p>
                    <p class="text-xs text-gray-500 mt-1">Vérifiées et actives</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-pills text-blue-600 text-2xl"></i>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 flex items-center justify-between border-l-4 border-green-500 hover:shadow-xl transition-shadow duration-300">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Pharmaciens</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $totalPharmacists }}</p>
                    <p class="text-xs text-gray-500 mt-1">Sur la plateforme</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-user-md text-green-600 text-2xl"></i>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 flex items-center justify-between border-l-4 border-yellow-500 hover:shadow-xl transition-shadow duration-300">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Pharmacies Actives</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $activePharmacies }}</p>
                    <p class="text-xs text-gray-500 mt-1">En service</p>
                </div>
                <div class="bg-yellow-100 p-3 rounded-full">
                    <i class="fas fa-check-circle text-yellow-600 text-2xl"></i>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 flex items-center justify-between border-l-4 border-purple-500 hover:shadow-xl transition-shadow duration-300">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Vérifiées</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $verifiedPharmacies }}</p>
                    <p class="text-xs text-gray-500 mt-1">Approuvées</p>
                </div>
                <div class="bg-purple-100 p-3 rounded-full">
                    <i class="fas fa-shield-alt text-purple-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">
                <i class="fas fa-bolt text-green-500 mr-2"></i>Actions Rapides
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <a href="{{ route('pharmacies.index') }}" class="group flex flex-col items-center justify-center p-6 bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg hover:from-blue-100 hover:to-blue-200 transition-all duration-300 transform hover:-translate-y-1 shadow-md hover:shadow-lg">
                    <div class="bg-blue-500 p-4 rounded-full mb-4 group-hover:bg-blue-600 transition-colors">
                        <i class="fas fa-map-marker-alt text-white text-2xl"></i>
                    </div>
                    <span class="text-lg font-medium text-gray-800 text-center">Voir la carte</span>
                    <span class="text-sm text-gray-600 mt-1 text-center">Pharmacies disponibles</span>
                </a>

                <a href="{{ route('pharmacies.search') }}" class="group flex flex-col items-center justify-center p-6 bg-gradient-to-br from-green-50 to-green-100 rounded-lg hover:from-green-100 hover:to-green-200 transition-all duration-300 transform hover:-translate-y-1 shadow-md hover:shadow-lg">
                    <div class="bg-green-500 p-4 rounded-full mb-4 group-hover:bg-green-600 transition-colors">
                        <i class="fas fa-search text-white text-2xl"></i>
                    </div>
                    <span class="text-lg font-medium text-gray-800 text-center">Rechercher</span>
                    <span class="text-sm text-gray-600 mt-1 text-center">Trouver une pharmacie</span>
                </a>

                <a href="{{ route('user.profile') }}" class="group flex flex-col items-center justify-center p-6 bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg hover:from-purple-100 hover:to-purple-200 transition-all duration-300 transform hover:-translate-y-1 shadow-md hover:shadow-lg">
                    <div class="bg-purple-500 p-4 rounded-full mb-4 group-hover:bg-purple-600 transition-colors">
                        <i class="fas fa-user text-white text-2xl"></i>
                    </div>
                    <span class="text-lg font-medium text-gray-800 text-center">Mon profil</span>
                    <span class="text-sm text-gray-600 mt-1 text-center">Informations personnelles</span>
                </a>

                <a href="{{ route('user.settings') }}" class="group flex flex-col items-center justify-center p-6 bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-lg hover:from-yellow-100 hover:to-yellow-200 transition-all duration-300 transform hover:-translate-y-1 shadow-md hover:shadow-lg">
                    <div class="bg-yellow-500 p-4 rounded-full mb-4 group-hover:bg-yellow-600 transition-colors">
                        <i class="fas fa-cog text-white text-2xl"></i>
                    </div>
                    <span class="text-lg font-medium text-gray-800 text-center">Paramètres</span>
                    <span class="text-sm text-gray-600 mt-1 text-center">Configuration</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
