<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pharmacy;
use Illuminate\Http\Request;

class PharmacyApiController extends Controller
{
    /**
     * Obtenir toutes les pharmacies pour l'API
     */
    public function index()
    {
        $pharmacies = Pharmacy::active()
            ->verified()
            ->with('pharmacist:id,name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $pharmacies,
            'count' => $pharmacies->count()
        ]);
    }

    /**
     * Rechercher des pharmacies par proximité
     */
    public function nearby(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius' => 'nullable|numeric|min:1|max:50'
        ]);

        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $radius = $request->radius ?? 10;

        $pharmacies = Pharmacy::active()
            ->verified()
            ->nearby($latitude, $longitude, $radius)
            ->with('pharmacist:id,name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $pharmacies,
            'count' => $pharmacies->count(),
            'search_params' => [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'radius' => $radius
            ]
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
            ->with('pharmacist:id,name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $pharmacies,
            'count' => $pharmacies->count(),
            'search_params' => [
                'city' => $request->city
            ]
        ]);
    }

    /**
     * Obtenir les détails d'une pharmacie
     */
    public function show(Pharmacy $pharmacy)
    {
        // Vérifier que la pharmacie est active et vérifiée
        if (!$pharmacy->is_active || !$pharmacy->is_verified) {
            return response()->json([
                'success' => false,
                'message' => 'Pharmacie non disponible'
            ], 404);
        }

        $pharmacy->load('pharmacist:id,name,email');

        return response()->json([
            'success' => true,
            'data' => $pharmacy
        ]);
    }

    /**
     * Obtenir les pharmacies pour l'affichage sur carte
     */
    public function forMap()
    {
        $pharmacies = Pharmacy::active()
            ->verified()
            ->select([
                'id', 'name', 'address', 'city', 'postal_code',
                'latitude', 'longitude', 'phone', 'whatsapp_number',
                'opening_hours', 'services'
            ])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $pharmacies,
            'count' => $pharmacies->count()
        ]);
    }

    /**
     * Rechercher des pharmacies avec filtres
     */
    public function search(Request $request)
    {
        $query = Pharmacy::active()->verified();

        // Filtre par ville
        if ($request->has('city') && $request->city) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        // Filtre par services
        if ($request->has('services') && is_array($request->services)) {
            foreach ($request->services as $service) {
                $query->whereJsonContains('services', $service);
            }
        }

        // Filtre par proximité si les coordonnées sont fournies
        if ($request->has(['latitude', 'longitude'])) {
            $latitude = $request->latitude;
            $longitude = $request->longitude;
            $radius = $request->radius ?? 10;

            $query->nearby($latitude, $longitude, $radius);
        }

        $pharmacies = $query->with('pharmacist:id,name')->get();

        return response()->json([
            'success' => true,
            'data' => $pharmacies,
            'count' => $pharmacies->count(),
            'filters' => $request->only(['city', 'services', 'latitude', 'longitude', 'radius'])
        ]);
    }
}
