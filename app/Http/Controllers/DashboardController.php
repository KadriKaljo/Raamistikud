<?php

namespace App\Http\Controllers;

use App\Models\Marker;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $city = $request->query('city'); // get city from query string
        $city = is_string($city) ? trim($city) : ''; // if city is not a string, trim it
        if ($city === '') { // if city is empty, set it to Tallinn
            $city = 'Tallinn'; // set city to Tallinn
        }

        $cacheKey = 'weather:'.Str::lower($city); // create cache key for the city

        $value = Cache::remember($cacheKey, now()->addHour(), function () use($city){
            $response = Http::get('https://api.openweathermap.org/data/2.5/weather', [
                'q' => $city,
                'appid' => config('services.weather.key'),
                'units' => 'metric',
                'lang' => 'et', // miks ei tööta, tahan kirjelduse saada eesti keeles?? 
            ]);
            return $response->json();
        });

        
        return Inertia::render('Dashboard', [
            'weather' => $value,
            'requestedCity' => $city,
            'markers' => Marker::orderByDesc('added')->get(),
        ]);
    }
}
