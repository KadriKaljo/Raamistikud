<?php

namespace App\Http\Controllers;

use App\Models\TravelDestination;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TravelDestinationController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'best_season' => ['nullable', 'string', 'max:100'],
            'sort' => ['nullable', 'in:title,country,best_season,created_at'],
            'direction' => ['nullable', 'in:asc,desc'],
        ]);

        $query = TravelDestination::query();

        if (! empty($validated['q'])) {
            $search = $validated['q'];
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
        $query->orderBy($sort, $direction);

        return Inertia::render('travel-destinations/Index', [
            'destinations' => $query->paginate(9)->withQueryString(),
            'filters' => [
                'q' => $validated['q'] ?? '',
                'country' => $validated['country'] ?? '',
                'best_season' => $validated['best_season'] ?? '',
                'sort' => $sort,
                'direction' => $direction,
            ],
            'countries' => TravelDestination::query()
                ->select('country')
                ->distinct()
                ->orderBy('country')
                ->pluck('country'),
            'seasons' => TravelDestination::query()
                ->select('best_season')
                ->distinct()
                ->orderBy('best_season')
                ->pluck('best_season'),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'url', 'max:2048'],
            'description' => ['required', 'string', 'max:5000'],
            'country' => ['required', 'string', 'max:100'],
            'best_season' => ['required', 'string', 'max:100'],
        ]);

        TravelDestination::create($data);

        return redirect()
            ->route('travel-destinations.index')
            ->with('success', 'Reisisihtkoht lisatud.');
    }
}
