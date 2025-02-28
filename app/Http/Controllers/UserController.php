<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $partners = [];

        // Si el usuario es 'admin', se listan los partners
        if ($user->role === User::ADMIN_USER) {
            $partners = User::all();
        }

        $role_admin = User::ADMIN_USER;

        return view('partners.index', compact('partners', 'user', 'role_admin'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('partners.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $partner = User::create($request->validated());

        // Redirigir a la lista de provincias con un mensaje de éxito
        return redirect()->route('partners.index')->with('success', 'el partner' . $partner->name . ' fue agregado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $partner)
    {
        return view('partners.show', compact('partner'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $partner)
    {
        return view('partners.edit', compact('partner'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $partner)
    {
        $partner->update($request->validated());

        return redirect()->back()->with('success', 'El partner ' . $partner->name . ' fue actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserRequest $request, User $partner)
    {

        $partner->delete();

        // Redirigir a la lista de provincias con un mensaje de éxito
        return redirect()->route('partners.index')->with('success', 'El partner ' . $partner->name . ' fue eliminado correctamente.');
    }

    // Método para listar provincias eliminadas
    public function trashed()
    {
        $partners = User::onlyTrashed()->get();
        return view('partners.trashed', compact('partners'));
    }

    // Método para restaurar una provincia
    public function restore($id)
    {

        $partner = User::withTrashed()->findOrFail($id);
        $partner->restore();

        return redirect()->route('partners.trashed')->with('success', 'El partner ' . $partner->name . ' fue restaurado correctamente.');
    }
}
