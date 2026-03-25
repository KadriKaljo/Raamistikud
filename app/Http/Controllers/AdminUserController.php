<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        if (! $request->user()?->is_admin) {
            abort(403);
        }

        return Inertia::render('admin/Users', [
            'users' => User::query()
                ->orderBy('name')
                ->get(['id', 'name', 'email', 'is_admin']),
        ]);
    }

    public function update(Request $request, User $user)
    {
        if (! $request->user()?->is_admin) {
            abort(403);
        }

        $validated = $request->validate([
            'is_admin' => ['required', 'boolean'],
        ]);

        $newAdmin = $validated['is_admin'];

        if ($user->is_admin && ! $newAdmin) {
            $adminCount = User::query()->where('is_admin', true)->count();
            if ($adminCount <= 1) {
                return redirect()->back()->with('error', 'Vähemalt üks administraator peab alles jääma.');
            }
        }

        $user->update(['is_admin' => $newAdmin]);

        return redirect()->back()->with('success', 'Kasutaja õigused uuendatud.');
    }
}
