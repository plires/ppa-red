<?php

namespace App\Http\Controllers;

use App\Models\Zone;
use Illuminate\Support\Facades\Auth;
use \App\Models\User;
use \App\Models\Province;
use App\Http\Requests\ZoneRequest;

class ZoneController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $zones = [];

        // Si el usuario es 'admin', se listan las zonas
        if ($user->role === User::ADMIN_USER) {
            $zones = Zone::all();
        }

        $role_admin = User::ADMIN_USER;

        return view('zones.index', compact('zones', 'user', 'role_admin'));
    }

    public function create()
    {
        $provinces = Province::all();
        return view('zones.create', compact('provinces'));
    }

    public function store(ZoneRequest $request)
    {
        $zone = Zone::create($request->validated());

        return redirect()->route('zones.index')->with('success', 'La zona ' . $zone->name . ' fue agregada correctamente.');
    }

    public function show(Zone $zone)
    {
        //
    }

    public function edit(Zone $zone)
    {
        $provinces = Province::all();
        return view('zones.edit', compact('zone', 'provinces'));
    }

    public function update(ZoneRequest $request, Zone $zone)
    {
        $zone->update($request->validated());

        return redirect()->route('zones.index')->with('success', 'La zona ' . $zone->name . ' fue actualizada correctamente.');
    }

    public function destroy(ZoneRequest $request, Zone $zone)
    {
        $zone->delete();

        return redirect()->route('zones.index')->with('success', 'La zona ' . $zone->name . ' fue eliminada correctamente.');
    }

    public function trashed()
    {
        $zones = Zone::onlyTrashed()->get();
        return view('zones.trashed', compact('zones'));
    }

    public function restore($id)
    {

        $zone = Zone::withTrashed()->findOrFail($id);
        $zone->restore();

        return redirect()->route('zones.trashed')->with('success', 'La zona ' . $zone->name . ' fue restaurada correctamente.');
    }
}
