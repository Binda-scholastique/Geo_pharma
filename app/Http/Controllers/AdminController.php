<?php

namespace App\Http\Controllers;

use App\Models\FirebaseUser as User;
use App\Models\FirebasePharmacy as Pharmacy;
use App\Models\FirebaseAuthorizationNumber as AuthorizationNumber;
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
        $allUsers = User::all();
        $allPharmacies = Pharmacy::all();
        $allAuthNumbers = AuthorizationNumber::all();
        
        $totalUsers = $allUsers->count();
        $totalPharmacists = $allUsers->where('role', 'pharmacist')->count();
        $totalPharmacies = $allPharmacies->count();
        $pendingPharmacies = $allPharmacies->where('is_verified', false)->count();
        
        $validAuthNumbers = $allAuthNumbers->filter(function ($auth) {
            return $auth->is_valid && (!$auth->expires_at || (is_string($auth->expires_at) ? new \DateTime($auth->expires_at) : $auth->expires_at) > now());
        });
        $validAuthorizationNumbers = $validAuthNumbers->count();
        
        // Récupérer les pharmacies récentes
        $recentPharmacies = $allPharmacies
            ->sortByDesc(function ($pharmacy) {
                return $pharmacy->created_at ?? '';
            })
            ->take(5)
            ->values();
            
        // Récupérer les utilisateurs récents
        $recentUsers = $allUsers
            ->sortByDesc(function ($user) {
                return $user->created_at ?? '';
            })
            ->take(5)
            ->values();

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
        $allUsers = User::all();

        // Filtres
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $allUsers = $allUsers->filter(function ($user) use ($search) {
                return stripos($user->name ?? '', $search) !== false || 
                       stripos($user->email ?? '', $search) !== false;
            });
        }

        if ($request->filled('role')) {
            $allUsers = $allUsers->where('role', $request->role);
        }

        if ($request->filled('status')) {
            if ($request->status === 'completed') {
                $allUsers = $allUsers->where('profile_completed', true);
            } elseif ($request->status === 'incomplete') {
                $allUsers = $allUsers->where('profile_completed', false);
            }
        }

        $users = $allUsers
            ->sortByDesc(function ($user) {
                return $user->created_at ?? '';
            })
            ->values();

        // Pagination manuelle
        $page = $request->get('page', 1);
        $perPage = 15;
        $total = $users->count();
        $items = $users->forPage($page, $perPage);
        $users = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

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
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:user,pharmacist,admin',
            'authorization_number' => 'nullable|string|max:255',
            'profile_completed' => 'boolean',
            'email_verified' => 'boolean',
        ]);

        // Vérifier l'unicité de l'email
        $existingUser = User::whereEmail($request->email)->first();
        if ($existingUser) {
            return back()->withErrors(['email' => 'Cet email est déjà utilisé.'])->withInput();
        }

        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'profile_completed' => $request->has('profile_completed'),
        ]);

        if ($request->filled('authorization_number')) {
            $user->authorization_number = $request->authorization_number;
        }

        if ($request->has('email_verified')) {
            $user->email_verified_at = now();
        }

        $user->save();

        return redirect()->route('admin.users')->with('success', 'Utilisateur créé avec succès.');
    }

    /**
     * Afficher les détails d'un utilisateur
     */
    public function showUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Afficher le formulaire d'édition d'utilisateur
     */
    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Mettre à jour un utilisateur
     */
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:user,pharmacist,admin',
            'authorization_number' => 'nullable|string|max:255',
            'profile_completed' => 'boolean',
            'email_verified' => 'boolean',
            'is_active' => 'boolean',
        ]);

        // Vérifier l'unicité de l'email
        $existingUser = User::whereEmail($request->email)->first();
        if ($existingUser && $existingUser->id !== $user->id) {
            return back()->withErrors(['email' => 'Cet email est déjà utilisé.'])->withInput();
        }

        $user->fill([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'profile_completed' => $request->has('profile_completed'),
        ]);

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->filled('authorization_number')) {
            $user->authorization_number = $request->authorization_number;
        }

        if ($request->has('email_verified')) {
            $user->email_verified_at = now();
        } else {
            $user->email_verified_at = null;
        }

        $user->save();

        return redirect()->route('admin.users.show', $user)->with('success', 'Utilisateur mis à jour avec succès.');
    }

    /**
     * Supprimer un utilisateur
     */
    public function destroyUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('admin.users')->with('success', 'Utilisateur supprimé avec succès.');
    }

    /**
     * Afficher la liste des pharmacies
     */
    public function pharmacies(Request $request)
    {
        $allPharmacies = Pharmacy::all();

        // Filtres
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $allPharmacies = $allPharmacies->filter(function ($pharmacy) use ($search) {
                return stripos($pharmacy->name ?? '', $search) !== false ||
                       stripos($pharmacy->address ?? '', $search) !== false ||
                       stripos($pharmacy->city ?? '', $search) !== false;
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $allPharmacies = $allPharmacies->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $allPharmacies = $allPharmacies->where('is_active', false);
            }
        }

        if ($request->filled('verification')) {
            if ($request->verification === 'verified') {
                $allPharmacies = $allPharmacies->where('is_verified', true);
            } elseif ($request->verification === 'pending') {
                $allPharmacies = $allPharmacies->where('is_verified', false);
            }
        }

        if ($request->filled('city')) {
            $allPharmacies = $allPharmacies->filter(function ($pharmacy) use ($request) {
                return stripos($pharmacy->city ?? '', $request->city) !== false;
            });
        }

        if ($request->filled('pharmacist')) {
            $allPharmacies = $allPharmacies->where('pharmacist_id', $request->pharmacist);
        }

        $pharmacies = $allPharmacies
            ->sortByDesc(function ($pharmacy) {
                return $pharmacy->created_at ?? '';
            })
            ->values();

        // Pagination manuelle
        $page = $request->get('page', 1);
        $perPage = 15;
        $total = $pharmacies->count();
        $items = $pharmacies->forPage($page, $perPage);
        $pharmacies = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('admin.pharmacies.index', compact('pharmacies'));
    }

    /**
     * Afficher le formulaire de création d'une pharmacie
     */
    public function createPharmacy()
    {
        $allUsers = User::all();
        $pharmacists = $allUsers->where('role', 'pharmacist')->values();
        return view('admin.pharmacies.create', compact('pharmacists'));
    }

    /**
     * Enregistrer une nouvelle pharmacie
     */
    public function storePharmacy(Request $request)
    {
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
            'pharmacist_id' => 'required',
            'is_verified' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
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
            'pharmacist_id' => $request->pharmacist_id,
            'is_verified' => $request->has('is_verified') ? $request->is_verified : true,
            'is_active' => $request->has('is_active') ? $request->is_active : true,
        ]);
        $pharmacy->save();

        return redirect()->route('admin.pharmacies')
            ->with('success', 'Pharmacie créée avec succès !');
    }

    /**
     * Afficher les détails d'une pharmacie
     */
    public function showPharmacy($id)
    {
        $pharmacy = Pharmacy::findOrFail($id);
        return view('admin.pharmacies.show', compact('pharmacy'));
    }

    /**
     * Afficher le formulaire d'édition d'une pharmacie
     */
    public function editPharmacy($id)
    {
        $pharmacy = Pharmacy::findOrFail($id);
        return view('admin.pharmacies.edit', compact('pharmacy'));
    }

    /**
     * Mettre à jour une pharmacie
     */
    public function updatePharmacy(Request $request, $id)
    {
        $pharmacy = Pharmacy::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'pharmacist_id' => 'nullable',
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

        $pharmacy->fill([
            'name' => $request->name,
            'pharmacist_id' => $request->pharmacist_id ?? $pharmacy->pharmacist_id,
            'address' => $request->address,
            'city' => $request->city,
            'postal_code' => $request->postal_code,
            'phone' => $request->phone,
            'email' => $request->email,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
            'is_verified' => $request->has('is_verified'),
        ]);
        $pharmacy->save();

        return redirect()->route('admin.pharmacies.show', $pharmacy)->with('success', 'Pharmacie mise à jour avec succès.');
    }

    /**
     * Supprimer une pharmacie
     */
    public function destroyPharmacy($id)
    {
        $pharmacy = Pharmacy::findOrFail($id);
        $pharmacy->delete();
        return redirect()->route('admin.pharmacies')->with('success', 'Pharmacie supprimée avec succès.');
    }

    /**
     * Basculer le statut de vérification d'une pharmacie
     */
    public function togglePharmacyVerification($id)
    {
        $pharmacy = Pharmacy::findOrFail($id);
        $pharmacy->is_verified = !$pharmacy->is_verified;
        $pharmacy->save();
        
        $status = $pharmacy->is_verified ? 'vérifiée' : 'non vérifiée';
        return redirect()->back()->with('success', "Pharmacie marquée comme {$status}.");
    }

    /**
     * Basculer le statut actif d'une pharmacie
     */
    public function togglePharmacyStatus($id)
    {
        $pharmacy = Pharmacy::findOrFail($id);
        $pharmacy->is_active = !$pharmacy->is_active;
        $pharmacy->save();
        
        $status = $pharmacy->is_active ? 'active' : 'inactive';
        return redirect()->back()->with('success', "Pharmacie marquée comme {$status}.");
    }


    /**
     * Afficher la liste des numéros d'autorisation
     */
    public function authorizationNumbers()
    {
        $allAuthNumbers = AuthorizationNumber::all();
        $authorizationNumbers = $allAuthNumbers
            ->sortByDesc(function ($auth) {
                return $auth->created_at ?? '';
            })
            ->values();

        // Pagination manuelle
        $page = request()->get('page', 1);
        $perPage = 15;
        $total = $authorizationNumbers->count();
        $items = $authorizationNumbers->forPage($page, $perPage);
        $authorizationNumbers = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

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
            'number' => 'required|string|max:255',
            'pharmacist_name' => 'nullable|string|max:255',
            'pharmacy_name' => 'nullable|string|max:255',
            'expires_at' => 'nullable|date|after:today',
        ]);

        // Vérifier l'unicité du numéro
        $existing = AuthorizationNumber::all()->where('number', $request->number)->first();
        if ($existing) {
            return back()->withErrors(['number' => 'Ce numéro d\'autorisation existe déjà.'])->withInput();
        }

        $authNumber = new AuthorizationNumber($request->all());
        $authNumber->is_valid = true;
        $authNumber->save();

        return redirect()->route('admin.authorization-numbers')
            ->with('success', 'Numéro d\'autorisation créé avec succès.');
    }

    /**
     * Modifier un numéro d'autorisation
     */
    public function editAuthorizationNumber($id)
    {
        $authorizationNumber = AuthorizationNumber::findOrFail($id);
        return view('admin.authorization-numbers.edit', compact('authorizationNumber'));
    }

    /**
     * Mettre à jour un numéro d'autorisation
     */
    public function updateAuthorizationNumber(Request $request, $id)
    {
        $authorizationNumber = AuthorizationNumber::findOrFail($id);
        
        $request->validate([
            'number' => 'required|string|max:255',
            'pharmacist_name' => 'nullable|string|max:255',
            'pharmacy_name' => 'nullable|string|max:255',
            'expires_at' => 'nullable|date|after:today',
            'is_valid' => 'boolean',
        ]);

        // Vérifier l'unicité du numéro
        $existing = AuthorizationNumber::all()->where('number', $request->number)->first();
        if ($existing && $existing->id !== $authorizationNumber->id) {
            return back()->withErrors(['number' => 'Ce numéro d\'autorisation existe déjà.'])->withInput();
        }

        $authorizationNumber->fill($request->all());
        $authorizationNumber->save();

        return redirect()->route('admin.authorization-numbers')
            ->with('success', 'Numéro d\'autorisation mis à jour avec succès.');
    }

    /**
     * Basculer la validité d'un numéro d'autorisation
     */
    public function toggleAuthorizationNumberValidity($id)
    {
        $authorizationNumber = AuthorizationNumber::findOrFail($id);
        $authorizationNumber->is_valid = !$authorizationNumber->is_valid;
        $authorizationNumber->save();
        
        $status = $authorizationNumber->is_valid ? 'valide' : 'invalide';
        return redirect()->back()->with('success', "Numéro d'autorisation marqué comme {$status}.");
    }

    /**
     * Supprimer un numéro d'autorisation
     */
    public function destroyAuthorizationNumber($id)
    {
        $authorizationNumber = AuthorizationNumber::findOrFail($id);
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