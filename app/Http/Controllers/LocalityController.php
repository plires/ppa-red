<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Zone;
use App\Models\Locality;
use App\Models\Province;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LocalityRequest;

class LocalityController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $localities = [];

        // Si el usuario es 'admin', se listan las zonas
        if ($user->role === User::ADMIN_USER) {
            $localities = Locality::all();
        }

        $role_admin = User::ADMIN_USER;

        return view('localities.index', compact('localities', 'user', 'role_admin'));
    }

    public function create()
    {
        $provinces = Province::all();
        $partners = User::where('role', 'partner')->get();
        return view('localities.create', compact('provinces', 'partners'));
    }

    public function store(LocalityRequest $request)
    {
        $locality = Locality::create($request->validated());

        return back()->with('success', 'La localidad ' . $locality->name . ' fue agregada correctamente.');
    }

    public function show(Locality $locality)
    {
        //
    }

    public function edit(Locality $locality)
    {
        // Obtener todas las provincias
        $provinces = Province::with('zones')->get(); // Cargamos las zonas para usarlas en el front
        $partners = User::where('role', 'partner')->get();

        return view('localities.edit', compact('locality', 'provinces', 'partners'));
    }

    public function update(LocalityRequest $request, Locality $locality)
    {
        $locality->update([
            'name' => $request->name,
            'province_id' => $request->province_id,
            'zone_id' => $request->zone_id ?? null, // Si no hay zone_id, se guarda como null
        ]);

        return back()->with('success', 'Localidad actualizada correctamente.');
    }

    public function destroy(Locality $locality)
    {
        $locality->delete();

        return back()->with('success', 'La localidad ' . $locality->name . ' fue eliminada correctamente.');
    }

    public function trashed()
    {
        $localities = Locality::onlyTrashed()->get();
        return view('localities.trashed', compact('localities'));
    }

    public function restore($id)
    {

        $locality = Locality::withTrashed()->findOrFail($id);
        $locality->restore();

        return back()->with('success', 'La localidad ' . $locality->name . ' fue restaurada correctamente.');
    }
}
