<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\FirebasePharmacy as Pharmacy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PharmacistController extends Controller
{
    /**
     * Créer un middleware pour vérifier que l'utilisateur est un pharmacien
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->isPharmacist()) {
                abort(403, 'Accès non autorisé. Cette section est réservée aux pharmaciens.');
            }
            return $next($request);
        });
    }

    /**
     * Afficher le dashboard du pharmacien
     */
    public function dashboard()
    {
        $pharmacist = Auth::user();
        $pharmacies = $pharmacist->pharmacies();

        return view('pharmacist.dashboard', compact('pharmacist', 'pharmacies'));
    }

    /**
     * Afficher le formulaire de création de pharmacie
     */
    public function createPharmacy()
    {
        // Vérifier si le profil est complété
        if (!Auth::user()->profile_completed) {
            return view('pharmacist.complete-profile');
        }

        return view('pharmacist.create-pharmacy');
    }

    /**
     * Traiter la création d'une pharmacie
     */
    public function storePharmacy(Request $request)
    {
        // Vérifier si le profil est complété
        if (!Auth::user()->profile_completed) {
            return redirect()->route('pharmacist.complete-profile')
                ->with('error', 'Veuillez d\'abord compléter votre profil.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'country' => 'required|string|max:100',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'whatsapp_number' => 'nullable|string|max:20',
            'opening_hours' => 'nullable|array',
            'services' => 'nullable|array',
        ]);

        // Gérer les horaires (peuvent être envoyés en JSON string ou en array)
        $openingHours = $request->opening_hours;
        if (is_string($openingHours)) {
            $openingHours = json_decode($openingHours, true);
        }

        $pharmacy = new Pharmacy([
            'name' => $request->name,
            'description' => $request->description,
            'address' => $request->address,
            'city' => $request->city,
            'postal_code' => $request->postal_code,
            'country' => $request->country,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'phone' => $request->phone,
            'email' => $request->email,
            'whatsapp_number' => $request->whatsapp_number,
            'opening_hours' => $openingHours,
            'services' => $request->services,
            'pharmacist_id' => Auth::id(),
            'is_active' => true,
            'is_verified' => false,
        ]);
        $pharmacy->save();

        return redirect()->route('pharmacist.dashboard')
            ->with('success', 'Pharmacie créée avec succès ! Elle sera vérifiée par nos équipes avant d\'apparaître sur la carte.');
    }

    /**
     * Afficher le formulaire d'édition d'une pharmacie
     */
    public function editPharmacy($id)
    {
        $pharmacy = Pharmacy::findOrFail($id);
        
        // Vérifier que la pharmacie appartient au pharmacien connecté
        if ($pharmacy->pharmacist_id !== Auth::id()) {
            abort(403, 'Vous ne pouvez pas modifier cette pharmacie.');
        }

        return view('pharmacist.edit-pharmacy', compact('pharmacy'));
    }

    /**
     * Traiter la mise à jour d'une pharmacie
     */
    public function updatePharmacy(Request $request, $id)
    {
        $pharmacy = Pharmacy::findOrFail($id);
        
        // Vérifier que la pharmacie appartient au pharmacien connecté
        if ($pharmacy->pharmacist_id !== Auth::id()) {
            abort(403, 'Vous ne pouvez pas modifier cette pharmacie.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'country' => 'required|string|max:100',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'whatsapp_number' => 'nullable|string|max:20',
            'opening_hours' => 'nullable|array',
            'services' => 'nullable|array',
        ]);

        // Gérer les horaires (peuvent être envoyés en JSON string ou en array)
        $openingHours = $request->opening_hours;
        if (is_string($openingHours)) {
            $openingHours = json_decode($openingHours, true);
        }

        $pharmacy->fill([
            'name' => $request->name,
            'description' => $request->description,
            'address' => $request->address,
            'city' => $request->city,
            'postal_code' => $request->postal_code,
            'country' => $request->country,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'phone' => $request->phone,
            'email' => $request->email,
            'whatsapp_number' => $request->whatsapp_number,
            'opening_hours' => $openingHours,
            'services' => $request->services,
        ]);
        $pharmacy->save();

        return redirect()->route('pharmacist.dashboard')
            ->with('success', 'Pharmacie mise à jour avec succès !');
    }

    /**
     * Compléter le profil du pharmacien
     */
    public function completeProfile(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
        ]);

        $user = Auth::user();
        $user->profile_completed = true;
        $user->save();

        return redirect()->route('pharmacist.dashboard')
            ->with('success', 'Profil complété avec succès ! Vous pouvez maintenant ajouter vos pharmacies.');
    }

    /**
     * Afficher le profil du pharmacien
     */
    public function profile()
    {
        return view('pharmacist.profile');
    }

    /**
     * Mettre à jour le profil du pharmacien
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
        ]);

        // Vérifier l'unicité de l'email manuellement
        $user = Auth::user();
        $existingUser = \App\Models\FirebaseUser::whereEmail($request->email)->first();
        if ($existingUser && $existingUser->id !== $user->id) {
            return back()->withErrors(['email' => 'Cet email est déjà utilisé.']);
        }

        $user->fill($request->only(['name', 'email']));
        $user->save();

        return redirect()->route('pharmacist.profile')->with('success', 'Profil mis à jour avec succès.');
    }

    /**
     * Mettre à jour le mot de passe du pharmacien
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

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('pharmacist.profile')->with('success', 'Mot de passe mis à jour avec succès.');
    }

    /**
     * Afficher les paramètres du pharmacien
     */
    public function settings()
    {
        return view('pharmacist.settings');
    }

    /**
     * Mettre à jour les préférences de notifications
     */
    public function updateNotifications(Request $request)
    {
        // Ici vous pouvez ajouter la logique pour sauvegarder les préférences de notifications
        // Pour l'instant, on simule juste la sauvegarde
        
        return redirect()->route('pharmacist.settings')->with('success', 'Préférences de notifications mises à jour.');
    }

    /**
     * Mettre à jour les préférences d'affichage
     */
    public function updateDisplay(Request $request)
    {
        // Ici vous pouvez ajouter la logique pour sauvegarder les préférences d'affichage
        // Pour l'instant, on simule juste la sauvegarde
        
        return redirect()->route('pharmacist.settings')->with('success', 'Préférences d\'affichage mises à jour.');
    }

    /**
     * Afficher la page de localisation du pharmacien
     */
    public function location()
    {
        $pharmacist = Auth::user();
        return view('pharmacist.profile-location', compact('pharmacist'));
    }

    /**
     * Mettre à jour la localisation du pharmacien
     */
    public function updateLocation(Request $request)
    {
        $pharmacist = Auth::user();
        
        $request->validate([
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:10',
        ]);

        $pharmacist->fill($request->only('latitude', 'longitude', 'address', 'city', 'postal_code'));
        $pharmacist->save();

        return redirect()->route('pharmacist.location')->with('success', 'Localisation mise à jour avec succès.');
    }
}
