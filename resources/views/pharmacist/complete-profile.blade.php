@extends('layouts.app')

@section('title', 'Compléter le profil - GeoPharma')

@section('content')
<div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="text-center">
            <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-green-600 rounded-xl flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-user-md text-white text-2xl"></i>
            </div>
            <h2 class="text-3xl font-bold text-gray-900">Compléter votre profil</h2>
            <p class="mt-2 text-sm text-gray-600">
                Veuillez compléter vos informations pour pouvoir ajouter vos pharmacies
            </p>
        </div>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow-xl rounded-xl sm:px-10">
            <form class="space-y-6" method="POST" action="{{ route('pharmacist.complete-profile.store') }}">
                @csrf
                
                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">
                        Numéro de téléphone *
                    </label>
                    <div class="mt-1">
                        <input id="phone" name="phone" type="tel" required 
                               value="{{ old('phone') }}"
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm @error('phone') border-red-500 @enderror"
                               placeholder="01 23 45 67 89">
                    </div>
                    @error('phone')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Address -->
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700">
                        Adresse *
                    </label>
                    <div class="mt-1">
                        <input id="address" name="address" type="text" required 
                               value="{{ old('address') }}"
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm @error('address') border-red-500 @enderror"
                               placeholder="123 Rue de la Paix">
                    </div>
                    @error('address')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- City -->
                <div>
                    <label for="city" class="block text-sm font-medium text-gray-700">
                        Ville *
                    </label>
                    <div class="mt-1">
                        <input id="city" name="city" type="text" required 
                               value="{{ old('city') }}"
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm @error('city') border-red-500 @enderror"
                               placeholder="Kinshasa">
                    </div>
                    @error('city')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Postal Code -->
                <div>
                    <label for="postal_code" class="block text-sm font-medium text-gray-700">
                        Code postal *
                    </label>
                    <div class="mt-1">
                        <input id="postal_code" name="postal_code" type="text" required 
                               value="{{ old('postal_code') }}"
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm @error('postal_code') border-red-500 @enderror"
                               placeholder="001">
                    </div>
                    @error('postal_code')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Information Box -->
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-green-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-green-800">
                                Pourquoi compléter votre profil ?
                            </h3>
                            <div class="mt-2 text-sm text-green-700">
                                <ul class="list-disc list-inside space-y-1">
                                    <li>Ajouter et gérer vos pharmacies</li>
                                    <li>Apparaître sur la carte de géolocalisation</li>
                                    <li>Recevoir des demandes de contact</li>
                                    <li>Accéder à toutes les fonctionnalités</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" 
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                        <i class="fas fa-check mr-2"></i>
                        Compléter mon profil
                    </button>
                </div>
            </form>

            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300" />
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">Ou</span>
                    </div>
                </div>

                <div class="mt-6">
                    <a href="{{ route('pharmacist.dashboard') }}" 
                       class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Retour au dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
