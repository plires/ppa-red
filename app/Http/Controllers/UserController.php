<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Jobs\SendPartnerWelcomeEmail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
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
        // El partner elige su propia contraseña al activar la cuenta desde el
        // correo de bienvenida; acá solo generamos una inutilizable de arranque.
        $partner = User::create([
            ...$request->validated(),
            'role' => User::PARTNER_USER,
            'password' => Hash::make(Str::random(40)),
        ]);

        SendPartnerWelcomeEmail::dispatch($partner);

        return redirect()->route('partners.index')->with('success', 'El partner '.$partner->name.' fue agregado correctamente. Se le envió un correo de bienvenida para que active su cuenta.');
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
        $setsNewPassword = ! empty($data['password']);
        $wasPending = ! $partner->isActivated();

        if (! $setsNewPassword) {
            unset($data['password']);
        }
        unset($data['password_confirmation']);

        $partner->update($data);

        if ($setsNewPassword && $wasPending) {
            // El admin le puso una contraseña a mano: ya no tiene sentido dejarlo
            // marcado como "pendiente de activación" ni seguir ofreciendo el resend.
            $partner->forceFill(['activated_at' => now(), 'email_verified_at' => now()])->save();
        }

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

    // Reenvía el correo de bienvenida a un partner que todavía no activó su cuenta
    public function resendWelcome(User $partner)
    {
        $this->ensurePartner($partner);

        if ($partner->isActivated()) {
            return back()->with('error', 'Este partner ya activó su cuenta.');
        }

        SendPartnerWelcomeEmail::dispatch($partner);

        return back()->with('success', 'Se reenvió el correo de bienvenida a '.$partner->name.'.');
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
