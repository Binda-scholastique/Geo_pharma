<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Redirection basée sur le rôle de l'utilisateur
        if (auth()->check()) {
            $user = auth()->user();
            
            // Si l'utilisateur est un admin, rediriger vers son dashboard
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            
            // Si l'utilisateur est un utilisateur normal, rediriger vers la page de recherche de pharmacies
            if ($user->role === 'user') {
                return redirect()->route('pharmacies.index');
            }
            
            // Si l'utilisateur est un pharmacien, rediriger vers son dashboard
            if ($user->role === 'pharmacist') {
                return redirect()->route('pharmacist.dashboard');
            }
        }
        
        // Par défaut, rediriger vers la page des pharmacies
        return redirect()->route('pharmacies.index');
    }
}
