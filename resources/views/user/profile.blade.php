@extends('layouts.app')

@section('title', 'Mon Profil - GeoPharma')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-green-50">
    <!-- Header avec gradient -->
    <div class="bg-gradient-to-r from-green-600 to-green-700 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-white">
                        <i class="fas fa-user mr-3"></i>
                        Mon Profil
                    </h1>
                    <p class="text-green-100 mt-2 text-lg">Gérez vos informations personnelles</p>
                </div>
                <a href="{{ route('pharmacies.index') }}" class="bg-white text-green-700 px-4 py-2 rounded-lg hover:bg-green-50 transition-colors duration-200 font-medium shadow-md">
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
                <a href="{{ route('user.settings') }}" class="text-green-600 hover:text-green-800 transition-colors">
                    Paramètres
                </a>
                <i class="fas fa-chevron-right text-gray-400"></i>
                <span class="text-gray-600 font-medium">Profil</span>
            </nav>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Contenu principal -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Informations Personnelles -->
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">
                        <i class="fas fa-user-edit text-green-500 mr-2"></i>Informations Personnelles
                    </h2>
                    <form method="POST" action="{{ route('user.profile.update') }}" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nom complet</label>
                                <input type="text" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', auth()->user()->name) }}" 
                                       required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('name') border-red-500 @enderror">
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input type="email" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email', auth()->user()->email) }}" 
                                       required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('email') border-red-500 @enderror">
                                @error('email')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="pt-6 border-t border-gray-200">
                            <button type="submit" class="w-full bg-green-500 text-white px-6 py-3 rounded-lg hover:bg-green-600 transition-colors duration-200 flex items-center justify-center">
                                <i class="fas fa-save mr-2"></i>Mettre à jour le profil
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Changer le mot de passe -->
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">
                        <i class="fas fa-lock text-green-500 mr-2"></i>Changer le mot de passe
                    </h2>
                    <form method="POST" action="{{ route('user.password.update') }}" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Mot de passe actuel</label>
                            <input type="password" 
                                   id="current_password" 
                                   name="current_password" 
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('current_password') border-red-500 @enderror">
                            @error('current_password')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Nouveau mot de passe</label>
                                <input type="password" 
                                       id="password" 
                                       name="password" 
                                       required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('password') border-red-500 @enderror">
                                @error('password')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirmer le mot de passe</label>
                                <input type="password" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            </div>
                        </div>
                        
                        <div class="pt-6 border-t border-gray-200">
                            <button type="submit" class="w-full bg-yellow-500 text-white px-6 py-3 rounded-lg hover:bg-yellow-600 transition-colors duration-200 flex items-center justify-center">
                                <i class="fas fa-key mr-2"></i>Changer le mot de passe
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Informations du compte -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-info-circle text-green-500 mr-2"></i>Informations du compte
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Type de compte</label>
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">Utilisateur</span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Membre depuis</label>
                            <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->created_at ? auth()->user()->created_at->format('d/m/Y') : '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Email vérifié</label>
                            @if(auth()->user()->email_verified_at)
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                                    <i class="fas fa-check mr-1"></i>Oui
                                </span>
                            @else
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>Non
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Actions rapides -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-bolt text-green-500 mr-2"></i>Actions rapides
                    </h3>
                    <div class="space-y-3">
                        <a href="{{ route('user.settings') }}" class="w-full flex items-center justify-center px-4 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors">
                            <i class="fas fa-cog mr-2"></i>Paramètres
                        </a>
                        <a href="{{ route('pharmacies.index') }}" class="w-full flex items-center justify-center px-4 py-3 bg-green-500 hover:bg-green-600 text-white rounded-lg transition-colors">
                            <i class="fas fa-search mr-2"></i>Rechercher des pharmacies
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
