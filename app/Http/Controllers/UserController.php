<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->isUser()) {
                abort(403, 'Accès non autorisé. Cette section est réservée aux utilisateurs.');
            }
            return $next($request);
        });
    }

    /**
     * Afficher le profil de l'utilisateur
     */
    public function profile()
    {
        return view('user.profile');
    }

    /**
     * Mettre à jour le profil de l'utilisateur
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . auth()->id(),
        ]);

        auth()->user()->update($request->only(['name', 'email']));

        return redirect()->route('user.profile')->with('success', 'Profil mis à jour avec succès.');
    }

    /**
     * Mettre à jour le mot de passe de l'utilisateur
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, auth()->user()->password)) {
            return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.']);
        }

        auth()->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('user.profile')->with('success', 'Mot de passe mis à jour avec succès.');
    }

    /**
     * Afficher les paramètres de l'utilisateur
     */
    public function settings()
    {
        return view('user.settings');
    }

    /**
     * Mettre à jour les préférences de notifications
     */
    public function updateNotifications(Request $request)
    {
        // Ici vous pouvez ajouter la logique pour sauvegarder les préférences de notifications
        // Pour l'instant, on simule juste la sauvegarde
        
        return redirect()->route('user.settings')->with('success', 'Préférences de notifications mises à jour.');
    }

    /**
     * Mettre à jour les préférences d'affichage
     */
    public function updateDisplay(Request $request)
    {
        // Ici vous pouvez ajouter la logique pour sauvegarder les préférences d'affichage
        // Pour l'instant, on simule juste la sauvegarde
        
        return redirect()->route('user.settings')->with('success', 'Préférences d\'affichage mises à jour.');
    }
}
