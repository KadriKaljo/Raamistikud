<?php

namespace App\Http\Controllers;

use App\Models\Marker;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MarkerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() // toob markerid andmebaasist
    {
        return Inertia::render('markers/Index', [ // renderdab markerid index.vue leheküljele
            'markers' => Marker::orderByDesc('added')->get(), // sorteerib markerid lisamise järjekorras
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('map.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'description' => 'nullable|string',
        ]); // kontrollib andmeid, kas on korrektsed

        Marker::create($data); // salvestab rea tabelisse

        return redirect()->route('map.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Marker $marker)
    {
        return Inertia::render('markers/View', [ // renderdab markerid view.vue leheküljele
            'marker' => $marker,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Marker $marker)
    {
        return Inertia::render('markers/Edit', [
            'marker' => $marker,
        ]); // toob sama markeri, ja saadab edit lehele
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Marker $marker)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'description' => 'nullable|string',
        ]);

        $marker->update($data);

        return redirect()->route('markers.index')->with('success', 'Marker uuendatud.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Marker $marker)
    {
        $marker->delete();

        return redirect()->route('markers.index')->with('success', 'Marker kustutatud.');
    }
}
