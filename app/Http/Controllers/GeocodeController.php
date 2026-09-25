<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class GeocodeController extends Controller
{
    protected function key(): string
    {
        return config('services.locationiq.key');
    }

    // Recherche adresse -> suggestions
    public function search(Request $request)
    {
        $q = mb_substr(trim($request->query('q', '')), 0, 150);

        if (mb_strlen($q) < 3) {
            return response()->json([]);
        }

        $cacheKey = 'geocode:search:' . md5($q);

        $results = Cache::remember($cacheKey, now()->addDay(), function () use ($q) {
            $response = Http::get('https://us1.locationiq.com/v1/search', [
                'key' => $this->key(),
                'q' => $q,
                'format' => 'json',
                'addressdetails' => 1,
                'limit' => 5,
                'countrycodes' => 'fr',
            ]);

            return $response->successful() ? $response->json() : [];
        });

        return response()->json($results);
    }

    // Coordonnées -> adresse
    public function reverse(Request $request)
    {
        $lat = (float) $request->query('lat');
        $lon = (float) $request->query('lon');

        if (abs($lat) > 90 || abs($lon) > 180) {
            return response()->json(['error' => 'coordonnées invalides'], 422);
        }

        $cacheKey = 'geocode:reverse:' . round($lat, 5) . ':' . round($lon, 5);

        $result = Cache::remember($cacheKey, now()->addDay(), function () use ($lat, $lon) {
            $response = Http::get('https://us1.locationiq.com/v1/reverse', [
                'key' => $this->key(),
                'lat' => $lat,
                'lon' => $lon,
                'format' => 'json',
                'addressdetails' => 1,
            ]);

            return $response->successful() ? $response->json() : null;
        });

        return response()->json($result);
    }

    // Itinéraire entre 2 points (remplace OSRM démo)
    public function route(Request $request, string $mode)
    {
        $profiles = ['walking' => 'foot', 'cycling' => 'bicycle', 'driving' => 'driving'];
        $profile = $profiles[$mode] ?? null;

        if (!$profile) {
            return response()->json(['error' => 'mode invalide'], 422);
        }

        $originLat = (float) $request->query('origin_lat');
        $originLng = (float) $request->query('origin_lng');
        $destLat = (float) $request->query('dest_lat');
        $destLng = (float) $request->query('dest_lng');

        $coords = "{$originLng},{$originLat};{$destLng},{$destLat}";

        $response = Http::get("https://us1.locationiq.com/v1/directions/{$profile}/{$coords}", [
            'key' => $this->key(),
            'overview' => 'full',
            'geometries' => 'geojson',
        ]);

        return response()->json($response->successful() ? $response->json() : ['routes' => []]);
    }
}