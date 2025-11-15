@extends('layouts.app')

@section('title', 'Détails Utilisateur - Administration')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-green-50">
    <!-- Header avec gradient -->
    <div class="bg-gradient-to-r from-green-600 to-green-700 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-white">
                        <i class="fas fa-user mr-3"></i>
                        Détails de l'utilisateur
                    </h1>
                    <p class="text-green-100 mt-2 text-lg">Informations complètes sur {{ $user->name }}</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition-colors duration-200">
                        <i class="fas fa-edit mr-2"></i>Modifier
                    </a>
                    <a href="{{ route('admin.users') }}" class="bg-white text-green-700 px-4 py-2 rounded-lg hover:bg-green-50 transition-colors duration-200 font-medium shadow-md">
                        <i class="fas fa-arrow-left mr-2"></i>Retour
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
                <a href="{{ route('admin.users') }}" class="text-green-600 hover:text-green-800 transition-colors">
                    Utilisateurs
                </a>
                <i class="fas fa-chevron-right text-gray-400"></i>
                <span class="text-gray-600 font-medium">{{ $user->name }}</span>
            </nav>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Informations principales -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Informations personnelles -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">
                        <i class="fas fa-info-circle text-green-500 mr-2"></i>Informations personnelles
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Nom complet</label>
                            <p class="text-lg font-semibold text-gray-900">{{ $user->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Adresse email</label>
                            <div class="flex items-center">
                                <p class="text-lg font-semibold text-gray-900">{{ $user->email }}</p>
                                @if($user->email_verified_at)
                                    <span class="ml-2 px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-medium">Vérifié</span>
                                @else
                                    <span class="ml-2 px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-xs font-medium">Non vérifié</span>
                                @endif
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Rôle</label>
                            <p>
                                @if($user->role === 'admin')
                                    <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-medium">Administrateur</span>
                                @elseif($user->role === 'pharmacist')
                                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">Pharmacien</span>
                                @else
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">Utilisateur</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Statut du profil</label>
                            <p>
                                @if($user->profile_completed)
                                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">Complet</span>
                                @else
                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">Incomplet</span>
                                @endif
                            </p>
                        </div>
                        @if($user->authorization_number)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Numéro d'autorisation</label>
                            <p class="text-lg font-semibold text-gray-900 font-mono">{{ $user->authorization_number }}</p>
                        </div>
                        @endif
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Date d'inscription</label>
                            <p class="text-lg font-semibold text-gray-900">{{ $user->created_at ? $user->created_at->format('d/m/Y à H:i') : '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Dernière connexion</label>
                            <p class="text-lg font-semibold text-gray-900">
                                @if($user->last_login_at)
                                    {{ $user->last_login_at->format('d/m/Y à H:i') }}
                                @else
                                    <span class="text-gray-400">Jamais connecté</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Pharmacies (si pharmacien) -->
                @if($user->role === 'pharmacist' && $user->pharmacies->count() > 0)
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">
                        <i class="fas fa-store text-green-500 mr-2"></i>Pharmacies gérées ({{ $user->pharmacies->count() }})
                    </h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Adresse</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vérifiée</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($user->pharmacies as $pharmacy)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $pharmacy->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $pharmacy->address }}, {{ $pharmacy->city }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($pharmacy->is_active)
                                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-medium">Active</span>
                                        @else
                                            <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs font-medium">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($pharmacy->is_verified)
                                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-medium">Vérifiée</span>
                                        @else
                                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-xs font-medium">En attente</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <a href="{{ route('admin.pharmacies.show', $pharmacy->id) }}" 
                                           class="text-blue-600 hover:text-blue-900 transition-colors">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Actions rapides -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-bolt text-green-500 mr-2"></i>Actions rapides
                    </h3>
                    <div class="space-y-3">
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="w-full flex items-center justify-center px-4 py-3 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg transition-colors">
                            <i class="fas fa-edit mr-2"></i>Modifier l'utilisateur
                        </a>
                        
                        @if($user->role === 'pharmacist')
                        <a href="{{ route('admin.pharmacies') }}?pharmacist={{ $user->id }}" class="w-full flex items-center justify-center px-4 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors">
                            <i class="fas fa-store mr-2"></i>Voir ses pharmacies
                        </a>
                        @endif

                        <button type="button" 
                                onclick="confirmDelete('{{ route('admin.users.destroy', $user->id) }}', '{{ $user->name }}')"
                                class="w-full flex items-center justify-center px-4 py-3 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors">
                            <i class="fas fa-trash mr-2"></i>Supprimer l'utilisateur
                        </button>
                    </div>
                </div>

                <!-- Statistiques -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-chart-bar text-green-500 mr-2"></i>Statistiques
                    </h3>
                    <div class="grid grid-cols-2 gap-4 text-center">
                        <div>
                            <div class="text-3xl font-bold text-blue-600">{{ $user->pharmacies->count() }}</div>
                            <div class="text-xs text-gray-500 mt-1">Pharmacies</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold text-green-600">{{ $user->pharmacies->where('is_verified', true)->count() }}</div>
                            <div class="text-xs text-gray-500 mt-1">Vérifiées</div>
                        </div>
                    </div>
                </div>

                <!-- Informations système -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-cog text-green-500 mr-2"></i>Informations système
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">ID utilisateur</label>
                            <p class="text-lg font-semibold text-gray-900">{{ $user->id }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Email vérifié le</label>
                            <p class="text-sm text-gray-900">
                                @if($user->email_verified_at)
                                    {{ $user->email_verified_at->format('d/m/Y à H:i') }}
                                @else
                                    <span class="text-gray-400">Non vérifié</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Dernière mise à jour</label>
                            <p class="text-sm text-gray-900">{{ $user->updated_at ? $user->updated_at->format('d/m/Y à H:i') : '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-900">Confirmer la suppression</h3>
                <button onclick="closeDeleteModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="mb-6">
                <p class="text-gray-700">Êtes-vous sûr de vouloir supprimer l'utilisateur <strong id="userName"></strong> ?</p>
                <p class="text-red-600 font-semibold mt-2">Cette action est irréversible !</p>
            </div>
            <div class="flex justify-end space-x-3">
                <button onclick="closeDeleteModal()" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                    Annuler
                </button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-6 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                        Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(url, userName) {
    document.getElementById('userName').textContent = userName;
    document.getElementById('deleteForm').action = url;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Fermer avec Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDeleteModal();
    }
});
</script>
@endpush
@endsection
