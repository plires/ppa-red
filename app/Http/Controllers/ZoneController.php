<?php

namespace App\Http\Controllers;

use App\Http\Requests\ZoneRequest;
use App\Models\Province;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ZoneController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $zones = [];

        // Si el usuario es 'admin', se listan las zonas
        if ($user->role === User::ADMIN_USER) {
            $zones = Zone::with('province')->get();
        }

        $role_admin = User::ADMIN_USER;

        return Inertia::render('Zones/Index', ['zones' => $zones]);
    }

    public function create()
    {
        $provinces = Province::all();

        return Inertia::render('Zones/Create', ['provinces' => $provinces]);
    }

    public function store(ZoneRequest $request)
    {
        $zone = Zone::create($request->validated());

        return redirect()->route('zones.index')->with('success', 'La zona '.$zone->name.' fue agregada correctamente.');
    }

    public function show(Zone $zone)
    {
        return Inertia::render('Zones/Show', ['zone' => $zone->load(['province', 'localities.user'])]);
    }

    public function edit(Zone $zone)
    {
        $provinces = Province::all();

        return Inertia::render('Zones/Edit', ['zone' => $zone, 'provinces' => $provinces]);
    }

    public function update(ZoneRequest $request, Zone $zone)
    {
        $zone->update($request->validated());

        return redirect()->back()->with('success', 'La zona '.$zone->name.' fue actualizada correctamente.');
    }

    public function destroy(ZoneRequest $request, Zone $zone)
    {
        $zone->delete();

        return redirect()->route('zones.index')->with('success', 'La zona '.$zone->name.' fue eliminada correctamente.');
    }

    public function trashed()
    {
        $zones = Zone::onlyTrashed()->with('province')->get();

        return Inertia::render('Zones/Trashed', ['zones' => $zones]);
    }

    public function restore($id)
    {

        $zone = Zone::withTrashed()->findOrFail($id);
        $zone->restore();

        return redirect()->route('zones.trashed')->with('success', 'La zona '.$zone->name.' fue restaurada correctamente.');
    }
}
