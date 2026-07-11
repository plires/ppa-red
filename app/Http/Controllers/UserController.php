<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Inertia\Inertia;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $partners = User::where('role', User::PARTNER_USER)->get();

        return Inertia::render('Partners/Index', ['partners' => $partners]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Partners/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $partner = User::create([...$request->validated(), 'role' => User::PARTNER_USER]);

        // Redirigir a la lista de provincias con un mensaje de éxito
        return redirect()->route('partners.index')->with('success', 'el partner'.$partner->name.' fue agregado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $partner)
    {
        $this->ensurePartner($partner);

        $partner->load(['localities.zone', 'localities.province']);

        $recentSubmissions = $partner->formSubmissions()
            ->with(['locality', 'status'])
            ->latest()
            ->take(10)
            ->get()
            ->map(fn ($s) => [
                'id' => $s->id,
                'end_user_name' => data_get(json_decode($s->data, true), 'name', '—'),
                'locality' => $s->locality?->name,
                'status' => $s->status?->name,
                'date' => $s->created_at->format('d/m/Y'),
            ]);

        return Inertia::render('Partners/Show', [
            'partner' => $partner,
            'recentSubmissions' => $recentSubmissions,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $partner)
    {
        $this->ensurePartner($partner);

        return Inertia::render('Partners/Edit', ['partner' => $partner]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $partner)
    {
        $data = $request->validated();

        if (empty($data['password'])) {
            unset($data['password']);
        }
        unset($data['password_confirmation']);

        $partner->update($data);

        return redirect()->back()->with('success', 'El partner '.$partner->name.' fue actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserRequest $request, User $partner)
    {
        $partner->delete();

        // Redirigir a la lista de provincias con un mensaje de éxito
        return redirect()->route('partners.index')->with('success', 'El partner '.$partner->name.' fue eliminado correctamente.');
    }

    // Método para listar partners eliminados
    public function trashed()
    {
        $partners = User::onlyTrashed()->where('role', User::PARTNER_USER)->get();

        return Inertia::render('Partners/Trashed', ['partners' => $partners]);
    }

    // Método para restaurar un partner
    public function restore($id)
    {
        $partner = User::withTrashed()->findOrFail($id);
        $this->ensurePartner($partner);

        $partner->restore();

        return redirect()->route('partners.trashed')->with('success', 'El partner '.$partner->name.' fue restaurado correctamente.');
    }

    /**
     * Guard para show()/edit(): no pasan por UserRequest, así que el chequeo de
     * rol no lo cubre authorize(). update()/destroy() ya lo resuelven vía UserRequest.
     */
    private function ensurePartner(User $partner): void
    {
        if ($partner->role !== User::PARTNER_USER) {
            throw new NotFoundHttpException;
        }
    }
}
