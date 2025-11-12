@extends('layouts.app')

@section('title', 'Gestion des Numéros d\'Autorisation - GeoPharma')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-green-50">
    <!-- Header avec gradient -->
    <div class="bg-gradient-to-r from-green-600 to-green-700 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-white">
                        <i class="fas fa-key mr-3"></i>
                        Gestion des Numéros d'Autorisation
                    </h1>
                    <p class="text-green-100 mt-2 text-lg">Gérez les numéros d'autorisation des pharmaciens</p>
                </div>
                <a href="{{ route('admin.authorization-numbers.create') }}" class="bg-white bg-opacity-20 text-white px-6 py-3 rounded-lg hover:bg-opacity-30 transition-colors duration-200 flex items-center">
                    <i class="fas fa-plus mr-2"></i>Nouveau Numéro
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
                <span class="text-gray-600 font-medium">Numéros d'Autorisation</span>
            </nav>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Filtres -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-filter text-green-500 mr-2"></i>Filtres et Recherche
            </h3>
            <form method="GET" action="{{ route('admin.authorization-numbers') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                    <select name="status" id="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">Tous les statuts</option>
                        <option value="valid" {{ request('status') == 'valid' ? 'selected' : '' }}>Valides</option>
                        <option value="invalid" {{ request('status') == 'invalid' ? 'selected' : '' }}>Invalides</option>
                    </select>
                </div>
                <div>
                    <label for="expired" class="block text-sm font-medium text-gray-700 mb-2">Expiration</label>
                    <select name="expired" id="expired" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">Tous</option>
                        <option value="expired" {{ request('expired') == 'expired' ? 'selected' : '' }}>Expirés</option>
                        <option value="not_expired" {{ request('expired') == 'not_expired' ? 'selected' : '' }}>Non expirés</option>
                    </select>
                </div>
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Recherche</label>
                    <input type="text" name="search" id="search" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                           placeholder="Numéro, nom..." 
                           value="{{ request('search') }}">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-green-500 text-white px-6 py-2 rounded-lg hover:bg-green-600 transition-colors duration-200 flex items-center justify-center">
                        <i class="fas fa-search mr-2"></i>Filtrer
                    </button>
                </div>
            </form>
        </div>

        <!-- Tableau des numéros d'autorisation -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-800">
                    <i class="fas fa-list text-green-500 mr-2"></i>Liste des Numéros d'Autorisation
                </h3>
                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">{{ $authorizationNumbers->total() }} numéro(s)</span>
            </div>
            
            @if($authorizationNumbers->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Numéro</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pharmacien</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pharmacie</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expiration</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($authorizationNumbers as $authNumber)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $authNumber->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <code class="px-2 py-1 bg-gray-100 text-gray-800 rounded text-sm font-mono">{{ $authNumber->number }}</code>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $authNumber->pharmacist_name ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $authNumber->pharmacy_name ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($authNumber->is_valid)
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Valide</span>
                                    @else
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Invalide</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($authNumber->expires_at)
                                        @if($authNumber->expires_at->isFuture())
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">{{ $authNumber->expires_at->format('d/m/Y') }}</span>
                                        @else
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Expiré</span>
                                        @endif
                                    @else
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Permanent</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $authNumber->created_at->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.authorization-numbers.edit', $authNumber) }}" 
                                           class="text-yellow-600 hover:text-yellow-900 transition-colors" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <!-- Toggle validité -->
                                        <form method="POST" action="{{ route('admin.authorization-numbers.toggle-validity', $authNumber) }}" 
                                              class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="text-{{ $authNumber->is_valid ? 'red' : 'green' }}-600 hover:text-{{ $authNumber->is_valid ? 'red' : 'green' }}-900 transition-colors" 
                                                    title="{{ $authNumber->is_valid ? 'Invalider' : 'Valider' }}">
                                                <i class="fas {{ $authNumber->is_valid ? 'fa-times' : 'fa-check' }}"></i>
                                            </button>
                                        </form>
                                        
                                        <!-- Supprimer -->
                                        <form method="POST" action="{{ route('admin.authorization-numbers.destroy', $authNumber) }}" 
                                              class="inline"
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce numéro d\'autorisation ?')">
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
                    {{ $authorizationNumbers->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-key text-gray-300 text-6xl mb-4"></i>
                    <h5 class="text-gray-500 text-lg font-medium mb-2">Aucun numéro d'autorisation trouvé</h5>
                    <p class="text-gray-400 mb-6">Aucun numéro d'autorisation ne correspond à vos critères de recherche.</p>
                    <a href="{{ route('admin.authorization-numbers.create') }}" class="inline-flex items-center px-6 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                        <i class="fas fa-plus mr-2"></i>Créer un numéro d'autorisation
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
