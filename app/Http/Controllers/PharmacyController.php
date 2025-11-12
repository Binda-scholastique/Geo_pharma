<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pharmacy;
use Illuminate\Http\Request;

class PharmacyController extends Controller
{
    /**
     * Afficher la page d'accueil avec la carte des pharmacies
     */
    public function index()
    {
        // Récupérer toutes les pharmacies actives et vérifiées
        $pharmacies = Pharmacy::active()
            ->verified()
            ->with('pharmacist')
            ->get();

        return view('pharmacies.index', compact('pharmacies'));
    }

    /**
     * Afficher les détails d'une pharmacie
     */
    public function show(Pharmacy $pharmacy)
    {
        // Vérifier que l'utilisateur est connecté pour voir les détails
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Vous devez être connecté pour voir les détails de la pharmacie.');
        }

        // Charger la relation avec le pharmacien
        $pharmacy->load('pharmacist');

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
        $pharmacies = Pharmacy::active()
            ->verified()
            ->nearby($latitude, $longitude, $radius)
            ->with('pharmacist')
            ->get();

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

        $pharmacies = Pharmacy::active()
            ->verified()
            ->where('city', 'like', '%' . $request->city . '%')
            ->with('pharmacist')
            ->get();

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
        $pharmacies = Pharmacy::active()
            ->verified()
            ->select(['id', 'name', 'address', 'city', 'latitude', 'longitude', 'phone', 'whatsapp_number'])
            ->get();

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
                
                $pharmacies = Pharmacy::active()
                    ->verified()
                    ->nearby($latitude, $longitude, $radius)
                    ->with('pharmacist')
                    ->get();
                    
            } elseif ($request->search_type === 'city' && $request->has('search_city')) {
                // Recherche par ville
                $pharmacies = Pharmacy::active()
                    ->verified()
                    ->where('city', 'like', '%' . $request->search_city . '%')
                    ->with('pharmacist')
                    ->get();
                    
            } elseif ($request->search_type === 'name' && $request->has('search_name')) {
                // Recherche par nom
                $pharmacies = Pharmacy::active()
                    ->verified()
                    ->where('name', 'like', '%' . $request->search_name . '%')
                    ->with('pharmacist')
                    ->get();
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
