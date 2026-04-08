<?php

namespace App\Http\Controllers;

use App\Models\Marker;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MapController extends Controller
{
    /**
     * Interaktiivne markerite kaart (klõpsuga uue markeri salvestamine).
     */
    public function __invoke(Request $request)
    {
        return Inertia::render('map/Index', [
            'markers' => Marker::query()->orderByDesc('added')->get(),
        ]);
    }
}
