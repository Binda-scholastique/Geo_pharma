@extends('layouts.app')

@section('title', 'Gestion des Pharmacies - Administration')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-green-50">
    <!-- Header avec gradient -->
    <div class="bg-gradient-to-r from-green-600 to-green-700 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-white">
                        <i class="fas fa-store mr-3"></i>
                        Gestion des Pharmacies
                    </h1>
                    <p class="text-green-100 mt-2 text-lg">Gérez toutes les pharmacies de la plateforme</p>
                </div>
                <div>
                    <a href="{{ route('admin.pharmacies.create') }}" 
                       class="bg-white text-green-700 px-6 py-3 rounded-lg hover:bg-green-50 transition-colors duration-200 font-medium shadow-md flex items-center">
                        <i class="fas fa-plus-circle mr-2"></i>Créer une pharmacie
                    </a>
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
                <a href="{{ route('admin.dashboard') }}" class="text-green-600 hover:text-green-800 transition-colors">
                    Administration
                </a>
                <i class="fas fa-chevron-right text-gray-400"></i>
                <span class="text-gray-600 font-medium">Pharmacies</span>
            </nav>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6 flex items-center justify-between border-l-4 border-blue-500 hover:shadow-xl transition-shadow duration-300">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Pharmacies</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $pharmacies->total() }}</p>
                    <p class="text-xs text-gray-500 mt-1">Toutes les pharmacies</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-store text-blue-600 text-2xl"></i>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 flex items-center justify-between border-l-4 border-green-500 hover:shadow-xl transition-shadow duration-300">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Pharmacies Vérifiées</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $pharmacies->where('is_verified', true)->count() }}</p>
                    <p class="text-xs text-gray-500 mt-1">Approuvées</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 flex items-center justify-between border-l-4 border-yellow-500 hover:shadow-xl transition-shadow duration-300">
                <div>
                    <p class="text-gray-500 text-sm font-medium">En Attente</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $pharmacies->where('is_verified', false)->count() }}</p>
                    <p class="text-xs text-gray-500 mt-1">À vérifier</p>
                </div>
                <div class="bg-yellow-100 p-3 rounded-full">
                    <i class="fas fa-clock text-yellow-600 text-2xl"></i>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 flex items-center justify-between border-l-4 border-purple-500 hover:shadow-xl transition-shadow duration-300">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Pharmacies Actives</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $pharmacies->where('is_active', true)->count() }}</p>
                    <p class="text-xs text-gray-500 mt-1">Opérationnelles</p>
                </div>
                <div class="bg-purple-100 p-3 rounded-full">
                    <i class="fas fa-power-off text-purple-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Filtres et Recherche -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-filter text-green-500 mr-2"></i>Filtres et Recherche
            </h3>
            <form method="GET" action="{{ route('admin.pharmacies') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Rechercher</label>
                    <input type="text" id="search" name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Nom, adresse, ville..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                    <select id="status" name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">Tous</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actives</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactives</option>
                    </select>
                </div>
                <div>
                    <label for="verification" class="block text-sm font-medium text-gray-700 mb-2">Vérification</label>
                    <select id="verification" name="verification" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">Toutes</option>
                        <option value="verified" {{ request('verification') == 'verified' ? 'selected' : '' }}>Vérifiées</option>
                        <option value="pending" {{ request('verification') == 'pending' ? 'selected' : '' }}>En attente</option>
                    </select>
                </div>
                <div>
                    <label for="city" class="block text-sm font-medium text-gray-700 mb-2">Ville</label>
                    <input type="text" id="city" name="city" 
                           value="{{ request('city') }}" 
                           placeholder="Ville..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-green-500 text-white px-6 py-2 rounded-lg hover:bg-green-600 transition-colors duration-200 flex items-center justify-center">
                        <i class="fas fa-search mr-2"></i>Filtrer
                    </button>
                </div>
            </form>
        </div>

        <!-- Liste des Pharmacies -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-800">
                    <i class="fas fa-list text-green-500 mr-2"></i>Liste des Pharmacies
                </h3>
                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">{{ $pharmacies->total() }} pharmacie(s)</span>
            </div>
            
            @if($pharmacies->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                                <!-- <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Adresse</th> -->
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pharmacien</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vérifiée</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date création</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($pharmacies as $pharmacy)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $pharmacy->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-green-500 text-white flex items-center justify-center">
                                                <i class="fas fa-store"></i>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $pharmacy->name }}</div>
                                            @if($pharmacy->phone)
                                                <div class="text-sm text-gray-500">{{ $pharmacy->phone }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <!-- <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $pharmacy->address }}</div>
                                    <div class="text-sm text-gray-500">{{ $pharmacy->city }}, {{ $pharmacy->postal_code }}</div>
                                </td> -->
                                <td class="px-6 py-4">
                                    @if($pharmacy->pharmacist)
                                        <div class="text-sm font-medium text-gray-900">{{ $pharmacy->pharmacist->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $pharmacy->pharmacist->email }}</div>
                                    @else
                                        <span class="text-sm text-gray-400">Non assigné</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($pharmacy->is_active)
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                    @else
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Inactive</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($pharmacy->is_verified)
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Vérifiée</span>
                                    @else
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">En attente</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $pharmacy->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.pharmacies.show', $pharmacy) }}" 
                                           class="text-blue-600 hover:text-blue-900 transition-colors" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <!-- <a href="{{ route('admin.pharmacies.edit', $pharmacy) }}" 
                                           class="text-yellow-600 hover:text-yellow-900 transition-colors" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a> -->
                                        <form action="{{ route('admin.pharmacies.toggle-verification', $pharmacy) }}" 
                                              method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="text-{{ $pharmacy->is_verified ? 'yellow' : 'green' }}-600 hover:text-{{ $pharmacy->is_verified ? 'yellow' : 'green' }}-900 transition-colors" 
                                                    title="{{ $pharmacy->is_verified ? 'Désactiver' : 'Vérifier' }}">
                                                <i class="fas {{ $pharmacy->is_verified ? 'fa-times' : 'fa-check' }}"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.pharmacies.toggle-status', $pharmacy) }}" 
                                              method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="text-{{ $pharmacy->is_active ? 'gray' : 'blue' }}-600 hover:text-{{ $pharmacy->is_active ? 'gray' : 'blue' }}-900 transition-colors" 
                                                    title="{{ $pharmacy->is_active ? 'Désactiver' : 'Activer' }}">
                                                <i class="fas {{ $pharmacy->is_active ? 'fa-pause' : 'fa-play' }}"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.pharmacies.destroy', $pharmacy) }}" 
                                              method="POST" class="inline"
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette pharmacie ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 transition-colors" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $pharmacies->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-store text-gray-300 text-6xl mb-4"></i>
                    <h5 class="text-gray-500 text-lg font-medium mb-2">Aucune pharmacie trouvée</h5>
                    <p class="text-gray-400">Aucune pharmacie ne correspond à vos critères de recherche.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
