@extends('layouts.app')

@section('title', 'Dashboard Administration - GeoPharma')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-green-50">
    <!-- Header avec gradient -->
    <div class="bg-gradient-to-r from-green-600 to-green-700 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-white">
                        <i class="fas fa-cogs mr-3"></i>
                        Dashboard Administration
                    </h1>
                    <p class="text-green-100 mt-2 text-lg">Gérez votre plateforme GeoPharma</p>
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
                <span class="text-gray-600 font-medium">Administration</span>
            </nav>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6 flex items-center justify-between border-l-4 border-blue-500 hover:shadow-xl transition-shadow duration-300">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Utilisateurs</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $totalUsers }}</p>
                    <p class="text-xs text-gray-500 mt-1">Tous les comptes</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-users text-blue-600 text-2xl"></i>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 flex items-center justify-between border-l-4 border-green-500 hover:shadow-xl transition-shadow duration-300">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Pharmaciens</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $totalPharmacists }}</p>
                    <p class="text-xs text-gray-500 mt-1">Comptes actifs</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-user-md text-green-600 text-2xl"></i>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 flex items-center justify-between border-l-4 border-yellow-500 hover:shadow-xl transition-shadow duration-300">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Pharmacies</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $totalPharmacies }}</p>
                    <p class="text-xs text-gray-500 mt-1">Enregistrées</p>
                </div>
                <div class="bg-yellow-100 p-3 rounded-full">
                    <i class="fas fa-pills text-yellow-600 text-2xl"></i>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 flex items-center justify-between border-l-4 border-red-500 hover:shadow-xl transition-shadow duration-300">
                <div>
                    <p class="text-gray-500 text-sm font-medium">En Attente</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $pendingPharmacies }}</p>
                    <p class="text-xs text-gray-500 mt-1">À vérifier</p>
                </div>
                <div class="bg-red-100 p-3 rounded-full">
                    <i class="fas fa-hourglass-half text-red-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">
                <i class="fas fa-bolt text-green-500 mr-2"></i>Actions Rapides
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <a href="{{ route('admin.users') }}" class="group flex flex-col items-center justify-center p-6 bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg hover:from-blue-100 hover:to-blue-200 transition-all duration-300 transform hover:-translate-y-1">
                    <div class="bg-blue-500 p-4 rounded-full mb-4 group-hover:bg-blue-600 transition-colors">
                        <i class="fas fa-users text-white text-2xl"></i>
                    </div>
                    <span class="text-lg font-medium text-gray-800 text-center">Gérer les utilisateurs</span>
                    <span class="text-sm text-gray-600 mt-1">Créer, modifier, supprimer</span>
                </a>

                <a href="{{ route('admin.pharmacies') }}" class="group flex flex-col items-center justify-center p-6 bg-gradient-to-br from-green-50 to-green-100 rounded-lg hover:from-green-100 hover:to-green-200 transition-all duration-300 transform hover:-translate-y-1">
                    <div class="bg-green-500 p-4 rounded-full mb-4 group-hover:bg-green-600 transition-colors">
                        <i class="fas fa-pills text-white text-2xl"></i>
                    </div>
                    <span class="text-lg font-medium text-gray-800 text-center">Gérer les pharmacies</span>
                    <span class="text-sm text-gray-600 mt-1">Vérifier, activer</span>
                </a>

                <a href="{{ route('admin.authorization-numbers') }}" class="group flex flex-col items-center justify-center p-6 bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg hover:from-purple-100 hover:to-purple-200 transition-all duration-300 transform hover:-translate-y-1">
                    <div class="bg-purple-500 p-4 rounded-full mb-4 group-hover:bg-purple-600 transition-colors">
                        <i class="fas fa-key text-white text-2xl"></i>
                    </div>
                    <span class="text-lg font-medium text-gray-800 text-center">Numéros d'autorisation</span>
                    <span class="text-sm text-gray-600 mt-1">Gérer les accès</span>
                </a>

                <a href="{{ route('admin.users.create') }}" class="group flex flex-col items-center justify-center p-6 bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-lg hover:from-yellow-100 hover:to-yellow-200 transition-all duration-300 transform hover:-translate-y-1">
                    <div class="bg-yellow-500 p-4 rounded-full mb-4 group-hover:bg-yellow-600 transition-colors">
                        <i class="fas fa-user-plus text-white text-2xl"></i>
                    </div>
                    <span class="text-lg font-medium text-gray-800 text-center">Créer un utilisateur</span>
                    <span class="text-sm text-gray-600 mt-1">Nouveau compte</span>
                </a>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Recent Pharmacies -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-800">
                        <i class="fas fa-pills text-green-500 mr-2"></i>Pharmacies Récentes
                    </h3>
                    <a href="{{ route('admin.pharmacies') }}" class="text-green-600 hover:text-green-800 text-sm font-medium">
                        Voir tout <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                
                @if($recentPharmacies && $recentPharmacies->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentPharmacies as $pharmacy)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="flex items-center">
                                <div class="bg-green-100 p-2 rounded-full mr-3">
                                    <i class="fas fa-pills text-green-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-800">{{ $pharmacy->name }}</h4>
                                    <p class="text-sm text-gray-600">{{ $pharmacy->pharmacist->name }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                @if($pharmacy->is_verified)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check mr-1"></i>Vérifiée
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock mr-1"></i>En attente
                                    </span>
                                @endif
                                <p class="text-xs text-gray-500 mt-1">{{ $pharmacy->created_at->format('d/m/Y') }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-pills text-gray-300 text-4xl mb-3"></i>
                        <p class="text-gray-500">Aucune pharmacie récente</p>
                    </div>
                @endif
            </div>

            <!-- Recent Users -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-800">
                        <i class="fas fa-users text-blue-500 mr-2"></i>Utilisateurs Récents
                    </h3>
                    <a href="{{ route('admin.users') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Voir tout <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                
                @if($recentUsers && $recentUsers->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentUsers as $user)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="flex items-center">
                                <div class="bg-blue-100 p-2 rounded-full mr-3">
                                    <i class="fas fa-user text-blue-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-800">{{ $user->name }}</h4>
                                    <p class="text-sm text-gray-600">{{ $user->email }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                @if($user->role === 'admin')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-crown mr-1"></i>Admin
                                    </span>
                                @elseif($user->role === 'pharmacist')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-user-md mr-1"></i>Pharmacien
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <i class="fas fa-user mr-1"></i>Utilisateur
                                    </span>
                                @endif
                                <p class="text-xs text-gray-500 mt-1">{{ $user->created_at->format('d/m/Y') }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-users text-gray-300 text-4xl mb-3"></i>
                        <p class="text-gray-500">Aucun utilisateur récent</p>
                    </div>
                @endif
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
    
    .text-green-200 {
        color: #bbf7d0;
    }
    
    .hover\:text-green-800:hover {
        color: #065f46;
    }
    
    .transition-colors {
        transition-property: color, background-color, border-color, text-decoration-color, fill, stroke;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 150ms;
    }
    
    .transition-shadow {
        transition-property: box-shadow;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 150ms;
    }
    
    .duration-300 {
        transition-duration: 300ms;
    }
    
    .transform {
        transform: translateZ(0);
    }
    
    .hover\:-translate-y-1:hover {
        transform: translateY(-4px);
    }
    
    .transition-all {
        transition-property: all;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 150ms;
    }
</style>
@endpush