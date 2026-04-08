<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Avaleht: tegevuste valik (ilm, kaart, pood, blogi). Kaart on eraldi /map.
     */
    public function __invoke(Request $request)
    {
        return Inertia::render('Dashboard');
    }
}
