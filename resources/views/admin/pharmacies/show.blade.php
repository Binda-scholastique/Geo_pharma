@extends('layouts.app')

@section('title', 'Détails Pharmacie - Administration')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-green-50">
    <!-- Header avec gradient -->
    <div class="bg-gradient-to-r from-green-600 to-green-700 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-white">
                        <i class="fas fa-store mr-3"></i>
                        Détails de la pharmacie
                    </h1>
                    <p class="text-green-100 mt-2 text-lg">Informations complètes sur {{ $pharmacy->name }}</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.pharmacies.edit', $pharmacy->id) }}" class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition-colors duration-200">
                        <i class="fas fa-edit mr-2"></i>Modifier
                    </a>
                    <a href="{{ route('admin.pharmacies') }}" class="bg-white text-green-700 px-4 py-2 rounded-lg hover:bg-green-50 transition-colors duration-200 font-medium shadow-md">
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
                <a href="{{ route('admin.pharmacies') }}" class="text-green-600 hover:text-green-800 transition-colors">
                    Pharmacies
                </a>
                <i class="fas fa-chevron-right text-gray-400"></i>
                <span class="text-gray-600 font-medium">{{ $pharmacy->name }}</span>
            </nav>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Informations principales -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Informations générales -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">
                        <i class="fas fa-info-circle text-green-500 mr-2"></i>Informations générales
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Nom de la pharmacie</label>
                            <p class="text-lg font-semibold text-gray-900">{{ $pharmacy->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Statut</label>
                            <p>
                                @if($pharmacy->is_active)
                                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">Active</span>
                                @else
                                    <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-medium">Inactive</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Vérification</label>
                            <p>
                                @if($pharmacy->is_verified)
                                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">Vérifiée</span>
                                @else
                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">En attente de vérification</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Date de création</label>
                            <p class="text-lg font-semibold text-gray-900">{{ $pharmacy->created_at ? $pharmacy->created_at->format('d/m/Y à H:i') : '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Adresse et contact -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">
                        <i class="fas fa-map-marker-alt text-green-500 mr-2"></i>Adresse et contact
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-500 mb-1">Adresse</label>
                            <p class="text-lg font-semibold text-gray-900">{{ $pharmacy->address }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Ville</label>
                            <p class="text-lg font-semibold text-gray-900">{{ $pharmacy->city }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Code postal</label>
                            <p class="text-lg font-semibold text-gray-900">{{ $pharmacy->postal_code }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Téléphone</label>
                            <p class="text-lg font-semibold text-gray-900">
                                @if($pharmacy->phone)
                                    <a href="tel:{{ $pharmacy->phone }}" class="text-green-600 hover:text-green-800 transition-colors">{{ $pharmacy->phone }}</a>
                                @else
                                    <span class="text-gray-400">Non renseigné</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Email</label>
                            <p class="text-lg font-semibold text-gray-900">
                                @if($pharmacy->email)
                                    <a href="{{ $pharmacy->email_url }}" class="text-green-600 hover:text-green-800 transition-colors">{{ $pharmacy->email }}</a>
                                @else
                                    <span class="text-gray-400">Non renseigné</span>
                                @endif
                            </p>
                        </div>
                        @if($pharmacy->latitude && $pharmacy->longitude)
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-500 mb-1">Coordonnées GPS</label>
                            <p class="text-sm font-mono text-gray-600">Latitude: {{ $pharmacy->latitude }}, Longitude: {{ $pharmacy->longitude }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Horaires d'ouverture -->
                @if($pharmacy->opening_hours)
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">
                        <i class="fas fa-clock text-green-500 mr-2"></i>Horaires d'ouverture
                    </h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jour</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Matin</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Après-midi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @php
                                    $dayNames = [
                                        'lundi' => 'Lundi',
                                        'mardi' => 'Mardi',
                                        'mercredi' => 'Mercredi',
                                        'jeudi' => 'Jeudi',
                                        'vendredi' => 'Vendredi',
                                        'samedi' => 'Samedi',
                                        'dimanche' => 'Dimanche'
                                    ];
                                @endphp
                                @foreach($dayNames as $dayKey => $dayName)
                                @php
                                    $hours = $pharmacy->opening_hours[$dayKey] ?? null;
                                @endphp
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $dayName }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @if($hours && isset($hours['morning']) && isset($hours['morning']['start']) && isset($hours['morning']['end']))
                                            {{ $hours['morning']['start'] }} - {{ $hours['morning']['end'] }}
                                        @elseif($hours && isset($hours['start']) && isset($hours['end']))
                                            {{ $hours['start'] }} - {{ $hours['end'] }}
                                        @else
                                            <span class="text-gray-400">Fermé</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @if($hours && isset($hours['afternoon']) && isset($hours['afternoon']['start']) && isset($hours['afternoon']['end']))
                                            {{ $hours['afternoon']['start'] }} - {{ $hours['afternoon']['end'] }}
                                        @elseif($hours && isset($hours['start']) && isset($hours['end']))
                                            <span class="text-gray-400">-</span>
                                        @else
                                            <span class="text-gray-400">Fermé</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                <!-- Services -->
                @if($pharmacy->services && count($pharmacy->services) > 0)
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">
                        <i class="fas fa-concierge-bell text-green-500 mr-2"></i>Services proposés
                    </h2>
                    <div class="flex flex-wrap gap-2">
                        @foreach($pharmacy->services as $service)
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">{{ $service }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Description -->
                @if($pharmacy->description)
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">
                        <i class="fas fa-align-left text-green-500 mr-2"></i>Description
                    </h2>
                    <p class="text-gray-700 leading-relaxed">{{ $pharmacy->description }}</p>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Pharmacien responsable -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-user-md text-green-500 mr-2"></i>Pharmacien responsable
                    </h3>
                    @if($pharmacy->pharmacist)
                        <div class="text-center">
                            <div class="h-20 w-20 rounded-full bg-green-500 text-white flex items-center justify-center mx-auto mb-3 text-3xl font-bold">
                                {{ substr($pharmacy->pharmacist->name, 0, 1) }}
                            </div>
                            <h4 class="font-bold text-gray-900">{{ $pharmacy->pharmacist->name }}</h4>
                            <p class="text-gray-500 text-sm">{{ $pharmacy->pharmacist->email }}</p>
                            <a href="{{ route('admin.users.show', $pharmacy->pharmacist->id) }}" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors text-sm">
                                <i class="fas fa-eye mr-2"></i>Voir le profil
                            </a>
                        </div>
                    @else
                        <div class="text-center">
                            <div class="h-20 w-20 rounded-full bg-gray-300 text-white flex items-center justify-center mx-auto mb-3 text-3xl font-bold">
                                ?
                            </div>
                            <h4 class="font-bold text-gray-900">Non assigné</h4>
                            <p class="text-gray-500 text-sm">Aucun pharmacien assigné</p>
                        </div>
                    @endif
                </div>

                <!-- Actions rapides -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-bolt text-green-500 mr-2"></i>Actions rapides
                    </h3>
                    <div class="space-y-3">
                        <a href="{{ route('admin.pharmacies.edit', $pharmacy->id) }}" class="w-full flex items-center justify-center px-4 py-3 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg transition-colors">
                            <i class="fas fa-edit mr-2"></i>Modifier la pharmacie
                        </a>
                        
                        <form action="{{ route('admin.pharmacies.toggle-verification', $pharmacy->id) }}" method="POST" class="inline w-full">
                            @csrf
                            <button type="submit" class="w-full flex items-center justify-center px-4 py-3 {{ $pharmacy->is_verified ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-green-500 hover:bg-green-600' }} text-white rounded-lg transition-colors">
                                <i class="fas {{ $pharmacy->is_verified ? 'fa-times' : 'fa-check' }} mr-2"></i>
                                {{ $pharmacy->is_verified ? 'Désactiver la vérification' : 'Vérifier la pharmacie' }}
                            </button>
                        </form>

                        <form action="{{ route('admin.pharmacies.toggle-status', $pharmacy->id) }}" method="POST" class="inline w-full">
                            @csrf
                            <button type="submit" class="w-full flex items-center justify-center px-4 py-3 {{ $pharmacy->is_active ? 'bg-gray-500 hover:bg-gray-600' : 'bg-blue-500 hover:bg-blue-600' }} text-white rounded-lg transition-colors">
                                <i class="fas {{ $pharmacy->is_active ? 'fa-pause' : 'fa-play' }} mr-2"></i>
                                {{ $pharmacy->is_active ? 'Désactiver' : 'Activer' }}
                            </button>
                        </form>

                        <button type="button" 
                                onclick="confirmDelete('{{ route('admin.pharmacies.destroy', $pharmacy->id) }}', '{{ $pharmacy->name }}')"
                                class="w-full flex items-center justify-center px-4 py-3 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors">
                            <i class="fas fa-trash mr-2"></i>Supprimer la pharmacie
                        </button>
                    </div>
                </div>

                <!-- Statistiques -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-chart-bar text-green-500 mr-2"></i>Informations
                    </h3>
                    <div class="grid grid-cols-2 gap-4 text-center">
                        <div>
                            <div class="text-3xl font-bold text-blue-600">{{ $pharmacy->id }}</div>
                            <div class="text-xs text-gray-500 mt-1">ID Pharmacie</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold text-green-600">{{ $pharmacy->created_at->diffInDays(now()) }}</div>
                            <div class="text-xs text-gray-500 mt-1">Jours d'existence</div>
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
                            <label class="block text-sm font-medium text-gray-500 mb-1">ID</label>
                            <p class="text-sm font-semibold text-gray-900">{{ $pharmacy->id }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Créée le</label>
                            <p class="text-sm text-gray-900">{{ $pharmacy->created_at ? $pharmacy->created_at->format('d/m/Y à H:i') : '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Dernière mise à jour</label>
                            <p class="text-sm text-gray-900">{{ $pharmacy->updated_at ? $pharmacy->updated_at->format('d/m/Y à H:i') : '-' }}</p>
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
                <p class="text-gray-700">Êtes-vous sûr de vouloir supprimer la pharmacie <strong id="pharmacyName"></strong> ?</p>
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
function confirmDelete(url, pharmacyName) {
    document.getElementById('pharmacyName').textContent = pharmacyName;
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
