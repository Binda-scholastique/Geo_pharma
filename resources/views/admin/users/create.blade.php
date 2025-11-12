@extends('layouts.app')

@section('title', 'Créer un Utilisateur - Administration')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-green-50">
    <!-- Header avec gradient -->
    <div class="bg-gradient-to-r from-green-600 to-green-700 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-white">
                        <i class="fas fa-user-plus mr-3"></i>
                        Créer un Utilisateur
                    </h1>
                    <p class="text-green-100 mt-2 text-lg">Ajouter un nouvel utilisateur à la plateforme</p>
                </div>
                <a href="{{ route('admin.users') }}" class="bg-white text-green-700 px-4 py-2 rounded-lg hover:bg-green-50 transition-colors duration-200 font-medium shadow-md">
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
                <a href="{{ route('admin.users') }}" class="text-green-600 hover:text-green-800 transition-colors">
                    Utilisateurs
                </a>
                <i class="fas fa-chevron-right text-gray-400"></i>
                <span class="text-gray-600 font-medium">Créer</span>
            </nav>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Formulaire -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">
                        <i class="fas fa-user text-green-500 mr-2"></i>Informations de l'utilisateur
                    </h2>
                    
                    <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nom complet <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name') }}" 
                                       required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('name') border-red-500 @enderror">
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Adresse email <span class="text-red-500">*</span>
                                </label>
                                <input type="email" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('email') border-red-500 @enderror">
                                @error('email')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                    Mot de passe <span class="text-red-500">*</span>
                                </label>
                                <input type="password" 
                                       id="password" 
                                       name="password" 
                                       required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('password') border-red-500 @enderror">
                                @error('password')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Minimum 8 caractères</p>
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                    Confirmer le mot de passe <span class="text-red-500">*</span>
                                </label>
                                <input type="password" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                                    Rôle <span class="text-red-500">*</span>
                                </label>
                                <select id="role" 
                                        name="role" 
                                        required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('role') border-red-500 @enderror">
                                    <option value="">Sélectionner un rôle</option>
                                    <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>Utilisateur</option>
                                    <option value="pharmacist" {{ old('role') == 'pharmacist' ? 'selected' : '' }}>Pharmacien</option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrateur</option>
                                </select>
                                @error('role')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="authorization_number" class="block text-sm font-medium text-gray-700 mb-2">
                                    Numéro d'autorisation
                                </label>
                                <input type="text" 
                                       id="authorization_number" 
                                       name="authorization_number" 
                                       value="{{ old('authorization_number') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('authorization_number') border-red-500 @enderror">
                                @error('authorization_number')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Requis pour les pharmaciens</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="profile_completed" 
                                       name="profile_completed" 
                                       value="1" 
                                       {{ old('profile_completed') ? 'checked' : '' }}
                                       class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                <label for="profile_completed" class="ml-2 block text-sm text-gray-700">
                                    Profil complété
                                </label>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="email_verified" 
                                       name="email_verified" 
                                       value="1" 
                                       {{ old('email_verified') ? 'checked' : '' }}
                                       class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                <label for="email_verified" class="ml-2 block text-sm text-gray-700">
                                    Email vérifié
                                </label>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                            <a href="{{ route('admin.users') }}" class="px-6 py-3 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors duration-200">
                                <i class="fas fa-times mr-2"></i>Annuler
                            </a>
                            <button type="submit" class="px-6 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors duration-200">
                                <i class="fas fa-save mr-2"></i>Créer l'utilisateur
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Aide -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-info-circle text-green-500 mr-2"></i>Informations
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <h4 class="font-semibold text-gray-700 mb-2">Rôles disponibles :</h4>
                            <ul class="space-y-2 text-sm text-gray-600">
                                <li class="flex items-center">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-medium mr-2">Utilisateur</span>
                                    <span class="text-gray-500">- Accès public</span>
                                </li>
                                <li class="flex items-center">
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-medium mr-2">Pharmacien</span>
                                    <span class="text-gray-500">- Gestion pharmacies</span>
                                </li>
                                <li class="flex items-center">
                                    <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs font-medium mr-2">Administrateur</span>
                                    <span class="text-gray-500">- Accès complet</span>
                                </li>
                            </ul>
                        </div>

                        <div class="border-t border-gray-200 pt-4">
                            <h4 class="font-semibold text-gray-700 mb-2">Champs obligatoires :</h4>
                            <ul class="space-y-1 text-sm text-gray-600">
                                <li class="flex items-center">
                                    <i class="fas fa-check text-green-500 mr-2"></i>Nom complet
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check text-green-500 mr-2"></i>Adresse email
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check text-green-500 mr-2"></i>Mot de passe
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check text-green-500 mr-2"></i>Rôle
                                </li>
                            </ul>
                        </div>

                        <div class="border-t border-gray-200 pt-4">
                            <p class="text-sm text-gray-600">
                                <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>
                                Le numéro d'autorisation est requis pour les pharmaciens. 
                                L'utilisateur recevra un email de bienvenue avec ses identifiants.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Statistiques -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-chart-bar text-green-500 mr-2"></i>Statistiques
                    </h3>
                    <div class="grid grid-cols-3 gap-4 text-center">
                        <div>
                            <div class="text-2xl font-bold text-blue-600">{{ \App\Models\User::count() }}</div>
                            <div class="text-xs text-gray-500 mt-1">Total</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-green-600">{{ \App\Models\User::where('role', 'pharmacist')->count() }}</div>
                            <div class="text-xs text-gray-500 mt-1">Pharmaciens</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-purple-600">{{ \App\Models\User::where('role', 'user')->count() }}</div>
                            <div class="text-xs text-gray-500 mt-1">Utilisateurs</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('role');
    const authorizationField = document.getElementById('authorization_number');
    const authorizationLabel = authorizationField.previousElementSibling;

    function toggleAuthorizationField() {
        if (roleSelect.value === 'pharmacist') {
            authorizationField.required = true;
            authorizationLabel.innerHTML = 'Numéro d\'autorisation <span class="text-red-500">*</span>';
        } else {
            authorizationField.required = false;
            authorizationLabel.innerHTML = 'Numéro d\'autorisation';
        }
    }

    roleSelect.addEventListener('change', toggleAuthorizationField);
    toggleAuthorizationField(); // Initialiser l'état
});
</script>
@endpush
@endsection
