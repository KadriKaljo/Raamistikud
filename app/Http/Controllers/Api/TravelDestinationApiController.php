<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TravelDestination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TravelDestinationApiController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'best_season' => ['nullable', 'string', 'max:100'],
            'sort' => ['nullable', 'in:title,country,best_season,created_at'],
            'direction' => ['nullable', 'in:asc,desc'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $cacheKey = $this->buildCacheKey($validated);

        $payload = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($validated) {
            $query = TravelDestination::query();

            if (! empty($validated['search'])) {
                $search = $validated['search'];
                $query->where(function ($builder) use ($search) {
                    $builder->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('country', 'like', "%{$search}%");
                });
            }

            if (! empty($validated['country'])) {
                $query->where('country', $validated['country']);
            }

            if (! empty($validated['best_season'])) {
                $query->where('best_season', $validated['best_season']);
            }

            $sort = $validated['sort'] ?? 'created_at';
            $direction = $validated['direction'] ?? 'desc';
            $limit = $validated['limit'] ?? 20;

            $items = $query
                ->orderBy($sort, $direction)
                ->limit($limit)
                ->get();

            return [
                'data' => $items,
                'meta' => [
                    'search' => $validated['search'] ?? null,
                    'country' => $validated['country'] ?? null,
                    'best_season' => $validated['best_season'] ?? null,
                    'sort' => $sort,
                    'direction' => $direction,
                    'limit' => $limit,
                    'count' => $items->count(),
                    'cached_for_seconds' => 600,
                ],
            ];
        });

        return response()->json($payload);
    }

    /**
     * @param  array<string, mixed>  $validated
     */
    private function buildCacheKey(array $validated): string
    {
        ksort($validated);

        return 'travel_destinations_api:'.md5(json_encode($validated));
    }
}
