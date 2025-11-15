@extends('layouts.app')

@section('title', 'Gestion des Utilisateurs - Administration')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-green-50">
    <!-- Header avec gradient -->
    <div class="bg-gradient-to-r from-green-600 to-green-700 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-white">
                        <i class="fas fa-users mr-3"></i>
                        Gestion des Utilisateurs
                    </h1>
                    <p class="text-green-100 mt-2 text-lg">Gérez tous les utilisateurs de la plateforme</p>
                </div>
                <a href="{{ route('admin.users.create') }}" class="bg-white text-green-600 px-6 py-3 rounded-lg hover:bg-green-50 transition-colors duration-200 flex items-center font-semibold shadow-md hover:shadow-lg">
                    <i class="fas fa-plus mr-2"></i>Nouvel Utilisateur
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
                <span class="text-gray-600 font-medium">Utilisateurs</span>
            </nav>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6 flex items-center justify-between border-l-4 border-blue-500 hover:shadow-xl transition-shadow duration-300">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Utilisateurs</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $users->total() }}</p>
                    <p class="text-xs text-gray-500 mt-1">Tous les comptes</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-users text-blue-600 text-2xl"></i>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 flex items-center justify-between border-l-4 border-green-500 hover:shadow-xl transition-shadow duration-300">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Pharmaciens</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $users->where('role', 'pharmacist')->count() }}</p>
                    <p class="text-xs text-gray-500 mt-1">Sur la plateforme</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-user-md text-green-600 text-2xl"></i>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 flex items-center justify-between border-l-4 border-purple-500 hover:shadow-xl transition-shadow duration-300">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Utilisateurs Normaux</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $users->where('role', 'user')->count() }}</p>
                    <p class="text-xs text-gray-500 mt-1">Comptes standards</p>
                </div>
                <div class="bg-purple-100 p-3 rounded-full">
                    <i class="fas fa-user text-purple-600 text-2xl"></i>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 flex items-center justify-between border-l-4 border-yellow-500 hover:shadow-xl transition-shadow duration-300">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Profils Incomplets</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $users->where('profile_completed', false)->count() }}</p>
                    <p class="text-xs text-gray-500 mt-1">À compléter</p>
                </div>
                <div class="bg-yellow-100 p-3 rounded-full">
                    <i class="fas fa-exclamation-triangle text-yellow-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Filtres et Recherche -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-filter text-green-500 mr-2"></i>Filtres et Recherche
            </h3>
            <form method="GET" action="{{ route('admin.users') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Rechercher</label>
                    <input type="text" id="search" name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Nom, email..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Rôle</label>
                    <select id="role" name="role" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">Tous les rôles</option>
                        <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Utilisateur</option>
                        <option value="pharmacist" {{ request('role') == 'pharmacist' ? 'selected' : '' }}>Pharmacien</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Administrateur</option>
                    </select>
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                    <select id="status" name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">Tous les statuts</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Profil complet</option>
                        <option value="incomplete" {{ request('status') == 'incomplete' ? 'selected' : '' }}>Profil incomplet</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-green-500 text-white px-6 py-2 rounded-lg hover:bg-green-600 transition-colors duration-200 flex items-center justify-center">
                        <i class="fas fa-search mr-2"></i>Filtrer
                    </button>
                </div>
            </form>
        </div>

        <!-- Liste des Utilisateurs -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-800">
                    <i class="fas fa-list text-green-500 mr-2"></i>Liste des Utilisateurs
                </h3>
                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">{{ $users->total() }} utilisateur(s)</span>
            </div>
            
            @if($users->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rôle</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date d'inscription</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($users as $user)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-green-500 text-white flex items-center justify-center font-semibold">
                                                {{ substr($user->name, 0, 1) }}
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                            @if($user->authorization_number)
                                                <div class="text-sm text-gray-500">Auth: {{ $user->authorization_number }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($user->role === 'admin')
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Administrateur</span>
                                    @elseif($user->role === 'pharmacist')
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Pharmacien</span>
                                    @else
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Utilisateur</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($user->profile_completed)
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Complet</span>
                                    @else
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Incomplet</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.users.show', $user->id) }}" 
                                           class="text-blue-600 hover:text-blue-900 transition-colors" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.users.edit', $user->id) }}" 
                                           class="text-yellow-600 hover:text-yellow-900 transition-colors" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" 
                                              method="POST" class="inline"
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
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
                    {{ $users->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-users text-gray-300 text-6xl mb-4"></i>
                    <h5 class="text-gray-500 text-lg font-medium mb-2">Aucun utilisateur trouvé</h5>
                    <p class="text-gray-400 mb-6">Aucun utilisateur ne correspond à vos critères de recherche.</p>
                    <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-6 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                        <i class="fas fa-plus mr-2"></i>Créer le premier utilisateur
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
