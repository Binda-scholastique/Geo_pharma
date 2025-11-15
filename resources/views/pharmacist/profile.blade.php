@extends('layouts.app')

@section('title', 'Mon Profil - GeoPharma')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-green-50">
    <!-- Header avec gradient -->
    <div class="bg-gradient-to-r from-green-600 to-green-700 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-white">
                        <i class="fas fa-user-edit mr-3"></i>
                        Mon Profil
                    </h1>
                    <p class="text-green-100 mt-2">Gérez vos informations personnelles</p>
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
                <span class="text-gray-600 font-medium">Mon Profil</span>
            </nav>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Informations du profil -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">
                        <i class="fas fa-user mr-2 text-green-500"></i>Informations Personnelles
                    </h2>
                    
                    <form method="POST" action="{{ route('pharmacist.profile.update') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nom complet *
                                </label>
                                <input type="text" id="name" name="name" value="{{ old('name', Auth::user()->name) }}" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('name') border-red-500 @enderror" required>
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Adresse email *
                                </label>
                                <input type="email" id="email" name="email" value="{{ old('email', Auth::user()->email) }}" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('email') border-red-500 @enderror" required>
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6">
                            <label for="authorization_number" class="block text-sm font-medium text-gray-700 mb-2">
                                Numéro d'autorisation
                            </label>
                            <input type="text" id="authorization_number" name="authorization_number" 
                                   value="{{ old('authorization_number', Auth::user()->authorization_number) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('authorization_number') border-red-500 @enderror" readonly>
                            <p class="mt-1 text-sm text-gray-500">Ce numéro ne peut pas être modifié</p>
                            @error('authorization_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-8 flex justify-end space-x-4">
                            <a href="{{ route('pharmacist.dashboard') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                                Annuler
                            </a>
                            <button type="submit" class="px-6 py-3 bg-green-500 hover:bg-green-600 text-white rounded-lg transition-colors">
                                <i class="fas fa-save mr-2"></i>Enregistrer
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Changement de mot de passe -->
                <div class="bg-white rounded-xl shadow-lg p-8 mt-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">
                        <i class="fas fa-lock mr-2 text-green-500"></i>Changer le Mot de Passe
                    </h2>
                    
                    <form method="POST" action="{{ route('pharmacist.password.update') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="space-y-6">
                            <div>
                                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                                    Mot de passe actuel *
                                </label>
                                <input type="password" id="current_password" name="current_password" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('current_password') border-red-500 @enderror" required>
                                @error('current_password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nouveau mot de passe *
                                </label>
                                <input type="password" id="password" name="password" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('password') border-red-500 @enderror" required>
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                    Confirmer le nouveau mot de passe *
                                </label>
                                <input type="password" id="password_confirmation" name="password_confirmation" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" required>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end">
                            <button type="submit" class="px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors">
                                <i class="fas fa-key mr-2"></i>Changer le mot de passe
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Informations du compte -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">
                        <i class="fas fa-info-circle mr-2 text-green-500"></i>Informations du Compte
                    </h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Statut du profil</span>
                            @if(Auth::user()->profile_completed)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check mr-1"></i>Complété
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>Incomplet
                                </span>
                            @endif
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Membre depuis</span>
                            <span class="text-sm font-medium text-gray-800">{{ Auth::user()->created_at ? Auth::user()->created_at->format('d/m/Y') : '-' }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Dernière connexion</span>
                            <span class="text-sm font-medium text-gray-800">{{ Auth::user()->updated_at ? Auth::user()->updated_at->format('d/m/Y H:i') : '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Statistiques -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">
                        <i class="fas fa-chart-bar mr-2 text-green-500"></i>Statistiques
                    </h3>
                    
                    <div class="space-y-4">
                        @php
                            $userPharmacies = Auth::user()->pharmacies ?? collect([]);
                        @endphp
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Pharmacies</span>
                            <span class="text-lg font-bold text-gray-800">{{ $userPharmacies->count() }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Pharmacies actives</span>
                            <span class="text-lg font-bold text-green-600">{{ $userPharmacies->where('is_active', true)->count() }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Pharmacies vérifiées</span>
                            <span class="text-lg font-bold text-blue-600">{{ $userPharmacies->where('is_verified', true)->count() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Actions rapides -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">
                        <i class="fas fa-bolt mr-2 text-green-500"></i>Actions Rapides
                    </h3>
                    
                    <div class="space-y-3">
                        <a href="{{ route('pharmacist.create-pharmacy') }}" class="w-full flex items-center justify-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg transition-colors">
                            <i class="fas fa-plus mr-2"></i>Ajouter une pharmacie
                        </a>
                        
                        <a href="{{ route('pharmacist.dashboard') }}" class="w-full flex items-center justify-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                            <i class="fas fa-tachometer-alt mr-2"></i>Retour au dashboard
                        </a>
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
