<?php

namespace App\Http\Controllers;

use App\Http\Requests\LocalityRequest;
use App\Models\Locality;
use App\Models\Province;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class LocalityController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $localities = [];

        // Si el usuario es 'admin', se listan las zonas
        if ($user->role === User::ADMIN_USER) {
            $localities = Locality::with(['zone', 'province', 'user'])->get();
        }

        $role_admin = User::ADMIN_USER;

        return Inertia::render('Localities/Index', ['localities' => $localities]);
    }

    public function create()
    {
        $provinces = Province::with('zones')->get();
        $partners = User::where('role', 'partner')->get();

        return Inertia::render('Localities/Create', ['provinces' => $provinces, 'partners' => $partners]);
    }

    public function store(LocalityRequest $request)
    {
        $locality = Locality::create($request->validated());

        return back()->with('success', 'La localidad '.$locality->name.' fue agregada correctamente.');
    }

    public function show(Locality $locality)
    {
        return Inertia::render('Localities/Show', [
            'locality' => $locality->load(['zone', 'province', 'user']),
        ]);
    }

    public function edit(Locality $locality)
    {
        // Obtener todas las provincias
        $provinces = Province::with('zones')->get(); // Cargamos las zonas para usarlas en el front
        $partners = User::where('role', 'partner')->get();

        return Inertia::render('Localities/Edit', [
            'locality' => $locality->load('zone'),
            'provinces' => $provinces,
            'partners' => $partners,
        ]);
    }

    public function update(LocalityRequest $request, Locality $locality)
    {
        $locality->update($request->validated());

        return back()->with('success', 'Localidad actualizada correctamente.');
    }

    public function destroy(Locality $locality)
    {
        $locality->delete();

        return back()->with('success', 'La localidad '.$locality->name.' fue eliminada correctamente.');
    }

    public function trashed()
    {
        $localities = Locality::onlyTrashed()->get();

        return Inertia::render('Localities/Trashed', ['localities' => $localities]);
    }

    public function restore($id)
    {

        $locality = Locality::withTrashed()->findOrFail($id);
        $locality->restore();

        return back()->with('success', 'La localidad '.$locality->name.' fue restaurada correctamente.');
    }
}
