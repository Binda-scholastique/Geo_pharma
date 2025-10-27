<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pharmacy;
use App\Models\AuthorizationNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Créer une nouvelle instance du contrôleur.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->isAdmin()) {
                abort(403, 'Accès non autorisé.');
            }
            return $next($request);
        });
    }

    /**
     * Afficher le dashboard d'administration
     */
    public function dashboard()
    {
        $totalUsers = User::count();
        $totalPharmacists = User::where('role', 'pharmacist')->count();
        $totalPharmacies = Pharmacy::count();
        $pendingPharmacies = Pharmacy::where('is_verified', false)->count();
        $validAuthorizationNumbers = AuthorizationNumber::valid()->notExpired()->count();
        
        // Récupérer les pharmacies récentes
        $recentPharmacies = Pharmacy::with('pharmacist')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        // Récupérer les utilisateurs récents
        $recentUsers = User::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers', 'totalPharmacists', 'totalPharmacies',
            'pendingPharmacies', 'validAuthorizationNumbers',
            'recentPharmacies', 'recentUsers'
        ));
    }

    /**
     * Afficher la liste des utilisateurs
     */
    public function users(Request $request)
    {
        $query = User::query();

        // Filtres
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            if ($request->status === 'completed') {
                $query->where('profile_completed', true);
            } elseif ($request->status === 'incomplete') {
                $query->where('profile_completed', false);
            }
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Afficher le formulaire de création d'utilisateur
     */
    public function createUser()
    {
        return view('admin.users.create');
    }

    /**
     * Enregistrer un nouvel utilisateur
     */
    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:user,pharmacist,admin',
            'authorization_number' => 'nullable|string|max:255',
            'profile_completed' => 'boolean',
            'email_verified' => 'boolean',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'profile_completed' => $request->has('profile_completed'),
        ];

        if ($request->filled('authorization_number')) {
            $userData['authorization_number'] = $request->authorization_number;
        }

        if ($request->has('email_verified')) {
            $userData['email_verified_at'] = now();
        }

        User::create($userData);

        return redirect()->route('admin.users')->with('success', 'Utilisateur créé avec succès.');
    }

    /**
     * Afficher les détails d'un utilisateur
     */
    public function showUser(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Afficher le formulaire d'édition d'utilisateur
     */
    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Mettre à jour un utilisateur
     */
    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:user,pharmacist,admin',
            'authorization_number' => 'nullable|string|max:255',
            'profile_completed' => 'boolean',
            'email_verified' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'profile_completed' => $request->has('profile_completed'),
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        if ($request->filled('authorization_number')) {
            $userData['authorization_number'] = $request->authorization_number;
        }

        if ($request->has('email_verified')) {
            $userData['email_verified_at'] = now();
        } else {
            $userData['email_verified_at'] = null;
        }

        $user->update($userData);

        return redirect()->route('admin.users.show', $user)->with('success', 'Utilisateur mis à jour avec succès.');
    }

    /**
     * Supprimer un utilisateur
     */
    public function destroyUser(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users')->with('success', 'Utilisateur supprimé avec succès.');
    }

    /**
     * Afficher la liste des pharmacies
     */
    public function pharmacies(Request $request)
    {
        $query = Pharmacy::with('pharmacist');

        // Filtres
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        if ($request->filled('verification')) {
            if ($request->verification === 'verified') {
                $query->where('is_verified', true);
            } elseif ($request->verification === 'pending') {
                $query->where('is_verified', false);
            }
        }

        if ($request->filled('city')) {
            $query->where('city', 'like', "%{$request->city}%");
        }

        if ($request->filled('pharmacist')) {
            $query->where('pharmacist_id', $request->pharmacist);
        }

        $pharmacies = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.pharmacies.index', compact('pharmacies'));
    }

    /**
     * Afficher les détails d'une pharmacie
     */
    public function showPharmacy(Pharmacy $pharmacy)
    {
        $pharmacy->load('pharmacist');
        return view('admin.pharmacies.show', compact('pharmacy'));
    }

    /**
     * Afficher le formulaire d'édition d'une pharmacie
     */
    public function editPharmacy(Pharmacy $pharmacy)
    {
        $pharmacy->load('pharmacist');
        return view('admin.pharmacies.edit', compact('pharmacy'));
    }

    /**
     * Mettre à jour une pharmacie
     */
    public function updatePharmacy(Request $request, Pharmacy $pharmacy)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'pharmacist_id' => 'nullable|exists:users,id',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'is_verified' => 'boolean',
        ]);

        $pharmacyData = $request->only([
            'name', 'pharmacist_id', 'address', 'city', 'postal_code',
            'phone', 'email', 'website', 'latitude', 'longitude', 'description'
        ]);

        $pharmacyData['is_active'] = $request->has('is_active');
        $pharmacyData['is_verified'] = $request->has('is_verified');

        $pharmacy->update($pharmacyData);

        return redirect()->route('admin.pharmacies.show', $pharmacy)->with('success', 'Pharmacie mise à jour avec succès.');
    }

    /**
     * Supprimer une pharmacie
     */
    public function destroyPharmacy(Pharmacy $pharmacy)
    {
        $pharmacy->delete();
        return redirect()->route('admin.pharmacies')->with('success', 'Pharmacie supprimée avec succès.');
    }

    /**
     * Basculer le statut de vérification d'une pharmacie
     */
    public function togglePharmacyVerification(Pharmacy $pharmacy)
    {
        $pharmacy->update(['is_verified' => !$pharmacy->is_verified]);
        
        $status = $pharmacy->is_verified ? 'vérifiée' : 'non vérifiée';
        return redirect()->back()->with('success', "Pharmacie marquée comme {$status}.");
    }

    /**
     * Basculer le statut actif d'une pharmacie
     */
    public function togglePharmacyStatus(Pharmacy $pharmacy)
    {
        $pharmacy->update(['is_active' => !$pharmacy->is_active]);
        
        $status = $pharmacy->is_active ? 'active' : 'inactive';
        return redirect()->back()->with('success', "Pharmacie marquée comme {$status}.");
    }


    /**
     * Afficher la liste des numéros d'autorisation
     */
    public function authorizationNumbers()
    {
        $authorizationNumbers = AuthorizationNumber::orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.authorization-numbers.index', compact('authorizationNumbers'));
    }

    /**
     * Créer un nouveau numéro d'autorisation
     */
    public function createAuthorizationNumber()
    {
        return view('admin.authorization-numbers.create');
    }

    /**
     * Enregistrer un nouveau numéro d'autorisation
     */
    public function storeAuthorizationNumber(Request $request)
    {
        $request->validate([
            'number' => 'required|string|max:255|unique:authorization_numbers',
            'pharmacist_name' => 'nullable|string|max:255',
            'pharmacy_name' => 'nullable|string|max:255',
            'expires_at' => 'nullable|date|after:today',
        ]);

        AuthorizationNumber::create($request->all());

        return redirect()->route('admin.authorization-numbers')
            ->with('success', 'Numéro d\'autorisation créé avec succès.');
    }

    /**
     * Modifier un numéro d'autorisation
     */
    public function editAuthorizationNumber(AuthorizationNumber $authorizationNumber)
    {
        return view('admin.authorization-numbers.edit', compact('authorizationNumber'));
    }

    /**
     * Mettre à jour un numéro d'autorisation
     */
    public function updateAuthorizationNumber(Request $request, AuthorizationNumber $authorizationNumber)
    {
        $request->validate([
            'number' => 'required|string|max:255|unique:authorization_numbers,number,' . $authorizationNumber->id,
            'pharmacist_name' => 'nullable|string|max:255',
            'pharmacy_name' => 'nullable|string|max:255',
            'expires_at' => 'nullable|date|after:today',
            'is_valid' => 'boolean',
        ]);

        $authorizationNumber->update($request->all());

        return redirect()->route('admin.authorization-numbers')
            ->with('success', 'Numéro d\'autorisation mis à jour avec succès.');
    }

    /**
     * Supprimer un numéro d'autorisation
     */
    public function destroyAuthorizationNumber(AuthorizationNumber $authorizationNumber)
    {
        $authorizationNumber->delete();

        return redirect()->route('admin.authorization-numbers')
            ->with('success', 'Numéro d\'autorisation supprimé avec succès.');
    }

    /**
     * Afficher le profil de l'administrateur
     */
    public function profile()
    {
        return view('admin.profile');
    }

    /**
     * Mettre à jour le profil de l'administrateur
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . auth()->id(),
        ]);

        auth()->user()->update($request->only(['name', 'email']));

        return redirect()->route('admin.profile')->with('success', 'Profil mis à jour avec succès.');
    }

    /**
     * Mettre à jour le mot de passe de l'administrateur
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

        return redirect()->route('admin.profile')->with('success', 'Mot de passe mis à jour avec succès.');
    }

    /**
     * Afficher les paramètres de l'administrateur
     */
    public function settings()
    {
        return view('admin.settings');
    }

    /**
     * Mettre à jour les préférences de notifications de l'administrateur
     */
    public function updateNotifications(Request $request)
    {
        // Ici vous pouvez ajouter la logique pour sauvegarder les préférences de notifications
        // Pour l'instant, on simule juste la sauvegarde
        
        return redirect()->route('admin.settings')->with('success', 'Préférences de notifications mises à jour.');
    }

    /**
     * Mettre à jour la configuration système
     */
    public function updateSystemSettings(Request $request)
    {
        // Ici vous pouvez ajouter la logique pour sauvegarder la configuration système
        // Pour l'instant, on simule juste la sauvegarde
        
        return redirect()->route('admin.settings')->with('success', 'Configuration système mise à jour.');
    }
}