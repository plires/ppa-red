<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserAccountRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::where('role', '!=', User::PARTNER_USER)->get();

        return Inertia::render('Users/Index', ['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Users/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserAccountRequest $request)
    {
        $user = User::create($request->validated());

        return redirect()->route('users.index')->with('success', 'El usuario '.$user->name.' fue agregado correctamente.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $this->ensureNotPartner($user);

        return Inertia::render('Users/Edit', ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserAccountRequest $request, User $user)
    {
        $data = $request->validated();

        if (empty($data['password'])) {
            unset($data['password']);
        }
        unset($data['password_confirmation']);

        $user->update($data);

        return redirect()->back()->with('success', 'El usuario '.$user->name.' fue actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserAccountRequest $request, User $user)
    {
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'No podés eliminar tu propio usuario.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'El usuario '.$user->name.' fue eliminado correctamente.');
    }

    // Método para listar usuarios eliminados
    public function trashed()
    {
        $users = User::onlyTrashed()->where('role', '!=', User::PARTNER_USER)->get();

        return Inertia::render('Users/Trashed', ['users' => $users]);
    }

    // Método para restaurar un usuario
    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $this->ensureNotPartner($user);

        $user->restore();

        return redirect()->route('users.trashed')->with('success', 'El usuario '.$user->name.' fue restaurado correctamente.');
    }

    /**
     * Guard para edit()/restore(): no pasan por UserAccountRequest, así que el
     * chequeo de rol no lo cubre authorize(). update()/destroy() ya lo resuelven ahí.
     */
    private function ensureNotPartner(User $user): void
    {
        if ($user->role === User::PARTNER_USER) {
            throw new NotFoundHttpException;
        }
    }
}
