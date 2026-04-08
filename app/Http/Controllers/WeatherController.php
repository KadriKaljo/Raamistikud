<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Inertia\Inertia;

class WeatherController extends Controller
{
    /**
     * Ilmateade (eraldi leht).
     */
    public function __invoke(Request $request)
    {
        $cityParam = $request->query('city');
        if (is_array($cityParam)) {
            $cityInput = isset($cityParam[0]) && is_string($cityParam[0]) ? trim($cityParam[0]) : '';
        } else {
            $cityInput = is_string($cityParam) ? trim($cityParam) : '';
        }

        if ($cityInput === '') {
            $city = 'Tallinn';
        } else {
            Validator::make(
                ['city' => $cityInput],
                [
                    'city' => ['string', 'max:100'],
                ],
                [
                    'city.max' => 'Linna või asukoha nimi võib olla kuni :max tähemärki.',
                ],
                [
                    'city' => 'asukoha nimi',
                ],
            )->validate();

            $city = $cityInput;
        }

        $cacheKey = 'weather:'.Str::lower($city);

        $cached = Cache::get($cacheKey);
        if ($this->isValidWeatherPayload($cached)) {
            $value = $cached;
        } else {
            $response = Http::get('https://api.openweathermap.org/data/2.5/weather', [
                'q' => $city,
                'appid' => config('services.weather.key'),
                'units' => 'metric',
                'lang' => 'et',
            ]);
            $raw = $response->json();
            $data = is_array($raw) ? $raw : [];
            if ($this->isValidWeatherPayload($data)) {
                Cache::put($cacheKey, $data, now()->addHour());
                $value = $data;
            } else {
                $value = $this->fallbackWeatherPayload(
                    $city,
                    $this->weatherErrorMessageForUser($response, $data),
                );
            }
        }

        return Inertia::render('weather/Index', [
            'weather' => $value,
            'requestedCity' => $city,
        ]);
    }

    /**
     * @param  mixed  $data
     */
    private function isValidWeatherPayload($data): bool
    {
        return is_array($data)
            && isset($data['main']['temp'], $data['main']['humidity'], $data['name'])
            && isset($data['weather'][0]['description'], $data['weather'][0]['icon'])
            && isset($data['wind']['speed']);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function weatherErrorMessageForUser(Response $response, array $data): string
    {
        $status = $response->status();
        $cod = $data['cod'] ?? null;
        $apiMessage = strtolower((string) ($data['message'] ?? ''));

        $looksLikeCityNotFound = $status === 404
            || $cod === '404'
            || $cod === 404
            || str_contains($apiMessage, 'city not found')
            || str_contains($apiMessage, 'not found');

        if ($looksLikeCityNotFound) {
            return 'Sellist linna ei leitud. Proovi teist nime või täpsemat kirjeldust (nt „Tartu, EE“ või „London, UK“).';
        }

        return 'Ilmaandmeid hetkel ei saanud. Proovi uuesti mõne aja pärast.';
    }

    /**
     * @return array<string, mixed>
     */
    private function fallbackWeatherPayload(string $city, string $userMessage): array
    {
        return [
            'main' => [
                'temp' => 0,
                'humidity' => 0,
            ],
            'weather' => [
                [
                    'description' => $userMessage,
                    'icon' => '02d',
                ],
            ],
            'name' => $city,
            'wind' => [
                'speed' => 0,
            ],
            'sys' => [
                'country' => '',
            ],
        ];
    }
}
