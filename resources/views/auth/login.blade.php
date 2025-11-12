@extends('layouts.app')

@section('title', 'Connexion - GeoPharma')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 to-green-100 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="text-center">
            <div class="w-20 h-20 bg-gradient-to-r from-green-500 to-green-600 rounded-xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                <i class="fas fa-pills text-white text-3xl"></i>
            </div>
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Connexion</h2>
            <p class="text-sm text-gray-600">
                Accédez à votre compte GeoPharma
            </p>
        </div>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow-xl rounded-xl sm:px-10 border border-green-100">
            <form class="space-y-6" method="POST" action="{{ route('login') }}">
                @csrf
                
                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-envelope text-green-500 mr-2"></i>Adresse email
                    </label>
                    <div class="mt-1">
                        <input id="email" name="email" type="email" autocomplete="email" required 
                               value="{{ old('email') }}"
                               class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200 @error('email') border-red-500 @enderror"
                               placeholder="votre@email.com">
                    </div>
                    @error('email')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-lock text-green-500 mr-2"></i>Mot de passe
                    </label>
                    <div class="mt-1 relative">
                        <input id="password" name="password" type="password" autocomplete="current-password" required 
                               class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200 @error('password') border-red-500 @enderror"
                               placeholder="Votre mot de passe">
                        <button type="button" onclick="togglePassword()" 
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                            <i id="password-icon" class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" 
                               class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-900">
                            Se souvenir de moi
                        </label>
                    </div>

                    @if (Route::has('password.request'))
                        <div class="text-sm">
                            <a href="{{ route('password.request') }}" 
                               class="font-medium text-green-600 hover:text-green-500 transition-colors duration-200">
                                Mot de passe oublié ?
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" 
                            class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <i class="fas fa-sign-in-alt text-green-200 group-hover:text-green-100"></i>
                        </span>
                        Se connecter
                    </button>
                </div>
            </form>

            <!-- Navigation vers Register -->
            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300" />
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">Pas encore de compte ?</span>
                    </div>
                </div>

                <div class="mt-6">
                    <a href="{{ route('register') }}" 
                       class="group w-full flex justify-center items-center py-3 px-4 border-2 border-green-300 rounded-lg shadow-sm text-sm font-medium text-green-700 bg-white hover:bg-green-50 hover:border-green-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 transform hover:-translate-y-0.5 hover:shadow-md">
                        <i class="fas fa-user-plus mr-2 group-hover:scale-110 transition-transform duration-200"></i>
                        Créer un compte
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Demo Accounts -->
    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-4 px-4 shadow-lg rounded-lg border border-green-100">
            <h3 class="text-sm font-medium text-gray-900 mb-3 text-center">
                <i class="fas fa-info-circle text-green-500 mr-1"></i>
                Comptes de démonstration
            </h3>
            <div class="space-y-2 text-xs">
                <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                    <span class="font-medium">Utilisateur :</span>
                    <span class="text-gray-600">jedidia.umba@geopharma.com / password</span>
                </div>
                <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                    <span class="font-medium">Pharmacien :</span>
                    <span class="text-gray-600">joviette.kandolo@geopharma.com / password</span>
                </div>
                <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                    <span class="font-medium">Pharmacien :</span>
                    <span class="text-gray-600">binda.scholastique@geopharma.com / password</span>
                </div>
                <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                    <span class="font-medium">Pharmacien :</span>
                    <span class="text-gray-600">gothie@geopharma.com / password</span>
                </div>
                <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                    <span class="font-medium">Administrateur :</span>
                    <span class="text-gray-600">admin@geopharma.com / password</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const passwordIcon = document.getElementById('password-icon');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        passwordIcon.classList.remove('fa-eye');
        passwordIcon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        passwordIcon.classList.remove('fa-eye-slash');
        passwordIcon.classList.add('fa-eye');
    }
}

// Animation d'entrée
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const inputs = document.querySelectorAll('input');
    
    // Animation des inputs
    inputs.forEach((input, index) => {
        input.style.opacity = '0';
        input.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            input.style.transition = 'all 0.5s ease';
            input.style.opacity = '1';
            input.style.transform = 'translateY(0)';
        }, index * 100);
    });
    
    // Animation du formulaire
    form.style.opacity = '0';
    form.style.transform = 'translateY(30px)';
    
    setTimeout(() => {
        form.style.transition = 'all 0.6s ease';
        form.style.opacity = '1';
        form.style.transform = 'translateY(0)';
    }, 200);
});
</script>
@endpush

@push('styles')
<style>
    .min-h-screen {
        min-height: 100vh;
    }
    
    .bg-gradient-to-br {
        background: linear-gradient(to bottom right, var(--tw-gradient-stops));
    }
    
    .from-green-50 {
        --tw-gradient-from: #f0fdf4;
        --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(240, 253, 244, 0));
    }
    
    .to-green-100 {
        --tw-gradient-to: #dcfce7;
    }
    
    .from-green-500 {
        --tw-gradient-from: #10b981;
        --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(16, 185, 129, 0));
    }
    
    .to-green-600 {
        --tw-gradient-to: #059669;
    }
    
    .hover\:from-green-600:hover {
        --tw-gradient-from: #059669;
        --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(5, 150, 105, 0));
    }
    
    .hover\:to-green-700:hover {
        --tw-gradient-to: #047857;
    }
    
    .focus\:ring-green-500:focus {
        --tw-ring-color: rgba(16, 185, 129, 0.5);
    }
    
    .border-green-100 {
        border-color: #dcfce7;
    }
    
    .border-green-300 {
        border-color: #86efac;
    }
    
    .text-green-500 {
        color: #10b981;
    }
    
    .text-green-600 {
        color: #059669;
    }
    
    .text-green-700 {
        color: #047857;
    }
    
    .hover\:text-green-500:hover {
        color: #10b981;
    }
    
    .hover\:bg-green-50:hover {
        background-color: #f0fdf4;
    }
    
    .transform {
        transform: translateZ(0);
    }
    
    .hover\:-translate-y-0\.5:hover {
        transform: translateY(-2px);
    }
    
    .transition-all {
        transition-property: all;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 200ms;
    }
    
    .duration-200 {
        transition-duration: 200ms;
    }
</style>
@endpush