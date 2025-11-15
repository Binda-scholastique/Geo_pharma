@extends('layouts.app')

@section('title', 'S\'inscrire - GeoPharma')

@section('content')
<div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="text-center">
            <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-green-600 rounded-xl flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-pills text-white text-2xl"></i>
            </div>
            <h2 class="text-3xl font-bold text-gray-900">Créer un compte</h2>
            <p class="mt-2 text-sm text-gray-600">
                Rejoignez GeoPharma pour accéder à tous nos services
            </p>
        </div>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow-xl rounded-xl sm:px-10">
            <form class="space-y-6" method="POST" action="{{ route('register') }}">
                @csrf
                
                <!-- Role Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        Je suis un :
                    </label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="relative cursor-pointer">
                            <input type="radio" name="role" value="user" class="sr-only" {{ old('role') == 'user' ? 'checked' : '' }}>
                            <div class="border-2 rounded-lg p-4 text-center transition-all duration-200 {{ old('role') == 'user' ? 'border-green-500 bg-green-50' : 'border-gray-200 hover:border-gray-300' }}">
                                <i class="fas fa-user text-2xl mb-2 {{ old('role') == 'user' ? 'text-green-500' : 'text-gray-400' }}"></i>
                                <p class="font-medium {{ old('role') == 'user' ? 'text-green-700' : 'text-gray-700' }}">Utilisateur</p>
                                <p class="text-xs text-gray-500 mt-1">Chercher des pharmacies</p>
                            </div>
                        </label>
                        
                        <label class="relative cursor-pointer">
                            <input type="radio" name="role" value="pharmacist" class="sr-only" {{ old('role') == 'pharmacist' ? 'checked' : '' }}>
                            <div class="border-2 rounded-lg p-4 text-center transition-all duration-200 {{ old('role') == 'pharmacist' ? 'border-green-500 bg-green-50' : 'border-gray-200 hover:border-gray-300' }}">
                                <i class="fas fa-user-md text-2xl mb-2 {{ old('role') == 'pharmacist' ? 'text-green-500' : 'text-gray-400' }}"></i>
                                <p class="font-medium {{ old('role') == 'pharmacist' ? 'text-green-700' : 'text-gray-700' }}">Pharmacien</p>
                                <p class="text-xs text-gray-500 mt-1">Gérer ma pharmacie</p>
                            </div>
                        </label>
                    </div>
                    @error('role')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">
                        Nom complet
                    </label>
                    <div class="mt-1">
                        <input id="name" name="name" type="text" required 
                               value="{{ old('name') }}"
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm @error('name') border-red-500 @enderror"
                               placeholder="Votre nom complet">
                    </div>
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">
                        Adresse email
                    </label>
                    <div class="mt-1">
                        <input id="email" name="email" type="email" autocomplete="email" required 
                               value="{{ old('email') }}"
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm @error('email') border-red-500 @enderror"
                               placeholder="votre@email.com">
                    </div>
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Authorization Number (for pharmacists) -->
                <div id="authorization-section" class="hidden">
                    <label for="authorization_number" class="block text-sm font-medium text-gray-700">
                        Numéro d'autorisation
                    </label>
                    <div class="mt-1">
                        <input id="authorization_number" name="authorization_number" type="text" 
                               value="{{ old('authorization_number') }}"
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm @error('authorization_number') border-red-500 @enderror"
                               placeholder="Votre numéro d'autorisation">
                    </div>
                    <!-- <p class="mt-2 text-sm text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>
                        Ce numéro sera vérifié via notre API d'autorisation
                    </p> -->
                    @error('authorization_number')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        Mot de passe
                    </label>
                    <div class="mt-1">
                        <input id="password" name="password" type="password" autocomplete="new-password" required 
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm @error('password') border-red-500 @enderror"
                               placeholder="Votre mot de passe">
                    </div>
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                        Confirmer le mot de passe
                    </label>
                    <div class="mt-1">
                        <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required 
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                               placeholder="Confirmez votre mot de passe">
                    </div>
                </div>

                <!-- Terms -->
                <div class="flex items-center">
                    <input id="terms" name="terms" type="checkbox" required 
                           class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                    <label for="terms" class="ml-2 block text-sm text-gray-900">
                        J'accepte les <a href="#" class="text-blue-600 hover:text-green-500">conditions d'utilisation</a>
                    </label>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" 
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                        <i class="fas fa-user-plus mr-2"></i>
                        Créer mon compte
                    </button>
                </div>
            </form>

            <!-- Navigation vers Login -->
            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300" />
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">Déjà un compte ?</span>
                    </div>
                </div>

                <div class="mt-6">
                    <a href="{{ route('login') }}" 
                       class="group w-full flex justify-center items-center py-3 px-4 border-2 border-green-300 rounded-lg shadow-sm text-sm font-medium text-green-700 bg-white hover:bg-green-50 hover:border-green-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 transform hover:-translate-y-0.5 hover:shadow-md">
                        <i class="fas fa-sign-in-alt mr-2 group-hover:scale-110 transition-transform duration-200"></i>
                        Se connecter
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Gérer l'affichage du champ d'autorisation selon le rôle sélectionné
document.addEventListener('DOMContentLoaded', function() {
    const roleInputs = document.querySelectorAll('input[name="role"]');
    const authorizationSection = document.getElementById('authorization-section');
    const authorizationInput = document.getElementById('authorization_number');
    
    function toggleAuthorizationSection() {
        const selectedRole = document.querySelector('input[name="role"]:checked');
        
        if (selectedRole && selectedRole.value === 'pharmacist') {
            authorizationSection.classList.remove('hidden');
            authorizationInput.required = true;
        } else {
            authorizationSection.classList.add('hidden');
            authorizationInput.required = false;
        }
    }
    
    // Écouter les changements de rôle
    roleInputs.forEach(input => {
        input.addEventListener('change', toggleAuthorizationSection);
    });
    
    // Initialiser l'état
    toggleAuthorizationSection();
    
    // Mettre à jour les styles des cartes de rôle
    roleInputs.forEach(input => {
        input.addEventListener('change', function() {
            // Réinitialiser tous les styles
            document.querySelectorAll('input[name="role"]').forEach(radio => {
                const card = radio.closest('label').querySelector('div');
                const icon = card.querySelector('i');
                const title = card.querySelector('p:first-of-type');
                
                card.classList.remove('border-green-500', 'bg-green-50');
                card.classList.add('border-gray-200');
                icon.classList.remove('text-green-500');
                icon.classList.add('text-gray-400');
                title.classList.remove('text-green-700');
                title.classList.add('text-gray-700');
            });
            
            // Appliquer le style au rôle sélectionné
            if (this.checked) {
                const card = this.closest('label').querySelector('div');
                const icon = card.querySelector('i');
                const title = card.querySelector('p:first-of-type');
                
                card.classList.remove('border-gray-200');
                card.classList.add('border-green-500', 'bg-green-50');
                icon.classList.remove('text-gray-400');
                icon.classList.add('text-green-500');
                title.classList.remove('text-gray-700');
                title.classList.add('text-green-700');
            }
        });
    });
});
</script>
@endpush
