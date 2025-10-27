<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'GeoPharma - Trouvez votre pharmacie')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
        
        .card-shadow {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(16, 185, 129, 0.4);
        }
        
        .pharmacy-card {
            transition: all 0.3s ease;
        }
        
        .pharmacy-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.15);
        }
        
        .whatsapp-btn {
            background: #25D366;
            transition: all 0.3s ease;
        }
        
        .whatsapp-btn:hover {
            background: #128C7E;
            transform: scale(1.05);
        }
        
        .search-input {
            transition: all 0.3s ease;
        }
        
        .search-input:focus {
            transform: scale(1.02);
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }
        
        .green-accent {
            color: #10b981;
        }
        
        .bg-green-light {
            background-color: #f0fdf4;
        }
        
        .border-green {
            border-color: #10b981;
        }
        
        .text-green-dark {
            color: #065f46;
        }
        
        /* Dropdown personnalisé */
        .dropdown-toggle::after {
            display: none;
        }
        
        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            border-radius: 0.5rem;
        }
        
        .dropdown-item:hover {
            background-color: #f8f9fa;
        }
        
        .dropdown-item.text-danger:hover {
            background-color: #fef2f2;
        }
        
        /* Bouton dropdown personnalisé */
        .dropdown .btn-link {
            border-radius: 0.5rem;
            transition: all 0.2s ease;
        }
        
        .dropdown .btn-link:hover {
            background-color: #f8f9fa;
            color: #10b981 !important;
        }
        
        /* Navigation links hover */
        .navbar a:hover {
            color: #10b981 !important;
        }
    </style>
    
    <!-- Custom CSS -->
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    
    @stack('styles')
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top px-4">
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center w-100">
                <!-- Logo -->
                <div class="d-flex align-items-center">
                    <a href="{{ route('home') }}" class="d-flex align-items-center text-decoration-none">
                        <div class="bg-success rounded d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                            <i class="fas fa-pills text-white" style="font-size: 0.875rem;"></i>
                        </div>
                        <span class="fs-5 fw-bold" style="color: #1f2937;">GeoPharma</span>
                    </a>
                </div>
                
                <!-- Navigation Links -->
                <div class="d-none d-md-flex align-items-center">
                    <a href="{{ route('home') }}" class="text-decoration-none me-4 px-2 py-1" style="color: #6b7280;">
                        <i class="fas fa-home me-2"></i>Accueil
                    </a>
                    <a href="{{ route('pharmacies.index') }}" class="text-decoration-none me-4 px-2 py-1" style="color: #6b7280;">
                        <i class="fas fa-map-marker-alt me-2"></i>Pharmacies
                    </a>
                    <a href="{{ route('pharmacies.search') }}" class="text-decoration-none me-4 px-2 py-1" style="color: #6b7280;">
                        <i class="fas fa-search me-2"></i>Recherche
                    </a>
                    
                    @auth
                        @if(auth()->user()->isPharmacist())
                            <a href="{{ route('pharmacist.dashboard') }}" class="text-decoration-none me-4 px-2 py-1" style="color: #6b7280;">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                            </a>
                        @endif
                    @endauth
                </div>
                
                <!-- User Menu -->
                <div class="d-flex align-items-center">
                    @guest
                        <a href="{{ route('login') }}" class="text-decoration-none me-3" style="color: #6b7280;">
                            <i class="fas fa-sign-in-alt me-2"></i>Connexion
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-success">
                            <i class="fas fa-user-plus me-2"></i>S'inscrire
                        </a>
                    @else
                        <div class="dropdown">
                            <button class="btn btn-link dropdown-toggle d-flex align-items-center text-decoration-none" 
                                    type="button" 
                                    id="userDropdown" 
                                    data-bs-toggle="dropdown" 
                                    aria-expanded="false"
                                    style="color: #6b7280; border: none; background: none; padding: 0.5rem 1rem;">
                                <i class="fas fa-user-circle me-2" style="font-size: 1.25rem;"></i>
                                <span class="d-none d-md-inline me-2">{{ auth()->user()->name }}</span>
                                <i class="fas fa-chevron-down" style="font-size: 0.75rem;"></i>
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                @if(auth()->user()->isPharmacist())
                                    <li>
                                        <a class="dropdown-item" href="{{ route('pharmacist.dashboard') }}">
                                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                                        </a>
                                    </li>
                                @endif
                                @if(auth()->user()->isAdmin())
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                            <i class="fas fa-cogs me-2"></i>Administration
                                        </a>
                                    </li>
                                @endif
                                <li>
                                    @if(auth()->user()->isAdmin())
                                        <a class="dropdown-item" href="{{ route('admin.profile') }}">
                                            <i class="fas fa-user-shield me-2"></i>Profil Admin
                                        </a>
                                    @elseif(auth()->user()->isPharmacist())
                                        <a class="dropdown-item" href="{{ route('pharmacist.profile') }}">
                                            <i class="fas fa-user-md me-2"></i>Profil Pharmacien
                                        </a>
                                    @else
                                        <a class="dropdown-item" href="{{ route('user.profile') }}">
                                            <i class="fas fa-user me-2"></i>Mon Profil
                                        </a>
                                    @endif
                                </li>
                                <li>
                                    @if(auth()->user()->isAdmin())
                                        <a class="dropdown-item" href="{{ route('admin.settings') }}">
                                            <i class="fas fa-cog me-2"></i>Paramètres Admin
                                        </a>
                                    @elseif(auth()->user()->isPharmacist())
                                        <a class="dropdown-item" href="{{ route('pharmacist.settings') }}">
                                            <i class="fas fa-cog me-2"></i>Paramètres
                                        </a>
                                    @else
                                        <a class="dropdown-item" href="{{ route('user.settings') }}">
                                            <i class="fas fa-cog me-2"></i>Paramètres
                                        </a>
                                    @endif
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" 
                                       href="{{ route('logout') }}" 
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                                    </a>
                                </li>
                            </ul>
                        </div>
                        
                        <!-- Logout Form -->
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                        </form>
                    @endguest
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <main >
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mx-4 mt-4" role="alert">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif
        
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mx-4 mt-4" role="alert">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif
        
        @yield('content')
    </main>
    
    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-green-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-pills text-white text-sm"></i>
                        </div>
                        <span class="text-xl font-bold">GeoPharma</span>
                    </div>
                    <p class="text-gray-300 mb-4">
                        Trouvez facilement les pharmacies près de chez vous. 
                        Géolocalisation en temps réel et contact direct avec les pharmaciens.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-300 hover:text-white transition-colors duration-200">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white transition-colors duration-200">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white transition-colors duration-200">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Liens utiles</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('home') }}" class="text-gray-300 hover:text-white transition-colors duration-200">Accueil</a></li>
                        <li><a href="{{ route('pharmacies.index') }}" class="text-gray-300 hover:text-white transition-colors duration-200">Pharmacies</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors duration-200">À propos</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors duration-200">Contact</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Pharmaciens</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('register') }}" class="text-gray-300 hover:text-white transition-colors duration-200">S'inscrire</a></li>
                        <li><a href="{{ route('login') }}" class="text-gray-300 hover:text-white transition-colors duration-200">Connexion</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors duration-200">Aide</a></li>
                    </ul>
                </div>
            </div>
            
            <hr class="border-gray-700 my-8">
            
            <div class="text-center text-gray-300">
                <p>&copy; {{ date('Y') }} GeoPharma. Tous droits réservés.</p>
            </div>
        </div>
    </footer>
    
    <!-- Scripts -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>
