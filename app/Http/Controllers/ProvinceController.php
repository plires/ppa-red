<?php

namespace App\Http\Controllers;

use App\Models\Province;
use Illuminate\Support\Facades\Auth;
use \App\Models\User;
use App\Http\Requests\ProvinceRequest;

class ProvinceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $provinces = [];

        // Si el usuario es 'admin', se listan las provincias
        if ($user->role === User::ADMIN_USER) {
            $provinces = Province::all();
        }

        $role_admin = User::ADMIN_USER;

        return view('provinces.index', compact('provinces', 'user', 'role_admin'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('provinces.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProvinceRequest $request)
    {
        $province = Province::create($request->validated());

        // Redirigir a la lista de provincias con un mensaje de éxito
        return redirect()->route('provinces.index')->with('success', 'la provincia ' . $province->name . ' fue agregada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Province $province)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Province $province)
    {
        return view('provinces.edit', compact('province'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProvinceRequest $request, Province $province)
    {
        $province->update($request->validated());

        // Redirigir a la lista de provincias con un mensaje de éxito
        return redirect()->route('provinces.index')->with('success', 'la provincia ' . $province->name . ' fue actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProvinceRequest $request, Province $province)
    {

        $province->delete();

        // Redirigir a la lista de provincias con un mensaje de éxito
        return redirect()->route('provinces.index')->with('success', 'La provincia ' . $province->name . ' fue eliminada correctamente.');
    }

    // Método para listar provincias eliminadas
    public function trashed()
    {
        $provinces = Province::onlyTrashed()->get();
        return view('provinces.trashed', compact('provinces'));
    }

    // Método para restaurar una provincia
    public function restore($id)
    {

        $province = Province::withTrashed()->findOrFail($id);
        $province->restore();

        return redirect()->route('provinces.trashed')->with('success', 'La provincia ' . $province->name . ' fue restaurada correctamente.');
    }
}
