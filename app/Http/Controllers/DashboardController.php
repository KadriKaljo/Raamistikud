<?php

namespace App\Http\Controllers;

use App\Models\Marker;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Dashboard: valikud ja kaart (ilm on eraldi /weather).
     */
    public function __invoke(Request $request)
    {
        $panel = $request->query('panel');
        if (is_array($panel)) {
            $panel = isset($panel[0]) && is_string($panel[0]) ? $panel[0] : null;
        }
        $initialPanel = is_string($panel) && in_array($panel, ['home', 'map'], true) ? $panel : 'home';

        return Inertia::render('Dashboard', [
            'markers' => Marker::orderByDesc('added')->get(),
            'initialPanel' => $initialPanel,
        ]);
    }
}
