<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\FirebasePharmacy;
use Illuminate\Http\Request;

class PharmacyController extends Controller
{
    /**
     * Afficher la page d'accueil avec la carte des pharmacies
     */
    public function index()
    {
        // Récupérer toutes les pharmacies actives et vérifiées
        $allPharmacies = FirebasePharmacy::all();
        $pharmacies = $allPharmacies
            ->where('is_active', true)
            ->where('is_verified', true)
            ->values();

        return view('pharmacies.index', compact('pharmacies'));
    }

    /**
     * Afficher les détails d'une pharmacie
     */
    public function show($id)
    {
        // Vérifier que l'utilisateur est connecté pour voir les détails
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Vous devez être connecté pour voir les détails de la pharmacie.');
        }

        $pharmacy = FirebasePharmacy::findOrFail($id);

        return view('pharmacies.show', compact('pharmacy'));
    }

    /**
     * Rechercher des pharmacies par proximité
     */
    public function search(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius' => 'nullable|numeric|min:1|max:50'
        ]);

        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $radius = $request->radius ?? 10; // Rayon par défaut de 10km

        // Rechercher les pharmacies à proximité
        $allPharmacies = FirebasePharmacy::scopeNearby(null, $latitude, $longitude, $radius);
        $pharmacies = $allPharmacies
            ->where('is_active', true)
            ->where('is_verified', true)
            ->values();

        return response()->json([
            'success' => true,
            'pharmacies' => $pharmacies,
            'count' => $pharmacies->count()
        ]);
    }

    /**
     * Rechercher des pharmacies par ville
     */
    public function searchByCity(Request $request)
    {
        $request->validate([
            'city' => 'required|string|min:2|max:100'
        ]);

        $allPharmacies = FirebasePharmacy::all();
        $pharmacies = $allPharmacies
            ->where('is_active', true)
            ->where('is_verified', true)
            ->filter(function ($pharmacy) use ($request) {
                return stripos($pharmacy->city ?? '', $request->city) !== false;
            })
            ->values();

        return response()->json([
            'success' => true,
            'pharmacies' => $pharmacies,
            'count' => $pharmacies->count()
        ]);
    }

    /**
     * Obtenir les pharmacies pour l'API (pour la carte)
     */
    public function getPharmaciesForMap()
    {
        $allPharmacies = FirebasePharmacy::all();
        $pharmacies = $allPharmacies
            ->where('is_active', true)
            ->where('is_verified', true)
            ->map(function ($pharmacy) {
                return [
                    'id' => $pharmacy->id,
                    'name' => $pharmacy->name,
                    'address' => $pharmacy->address,
                    'city' => $pharmacy->city,
                    'latitude' => $pharmacy->latitude,
                    'longitude' => $pharmacy->longitude,
                    'phone' => $pharmacy->phone,
                    'whatsapp_number' => $pharmacy->whatsapp_number,
                ];
            })
            ->values();

        return response()->json([
            'success' => true,
            'pharmacies' => $pharmacies
        ]);
    }

    /**
     * Afficher la page de recherche avancée
     */
    public function searchPage(Request $request)
    {
        $pharmacies = collect();
        
        if ($request->has('search_type')) {
            if ($request->search_type === 'proximity' && $request->has('latitude') && $request->has('longitude')) {
                // Recherche par proximité
                $latitude = $request->latitude;
                $longitude = $request->longitude;
                $radius = $request->radius ?? 10;
                
                $allPharmacies = FirebasePharmacy::scopeNearby(null, $latitude, $longitude, $radius);
                $pharmacies = $allPharmacies
                    ->where('is_active', true)
                    ->where('is_verified', true)
                    ->values();
                    
            } elseif ($request->search_type === 'city' && $request->has('search_city')) {
                // Recherche par ville
                $allPharmacies = FirebasePharmacy::all();
                $pharmacies = $allPharmacies
                    ->where('is_active', true)
                    ->where('is_verified', true)
                    ->filter(function ($pharmacy) use ($request) {
                        return stripos($pharmacy->city ?? '', $request->search_city) !== false;
                    })
                    ->values();
                    
            } elseif ($request->search_type === 'name' && $request->has('search_name')) {
                // Recherche par nom
                $allPharmacies = FirebasePharmacy::all();
                $pharmacies = $allPharmacies
                    ->where('is_active', true)
                    ->where('is_verified', true)
                    ->filter(function ($pharmacy) use ($request) {
                        return stripos($pharmacy->name ?? '', $request->search_name) !== false;
                    })
                    ->values();
            }
            
            // Filtrer par services si spécifiés
            if ($request->has('services') && is_array($request->services)) {
                $pharmacies = $pharmacies->filter(function ($pharmacy) use ($request) {
                    if (!$pharmacy->services) return false;
                    return !empty(array_intersect($request->services, $pharmacy->services));
                });
            }
        }
        
        return view('pharmacies.search', compact('pharmacies'));
    }
}
