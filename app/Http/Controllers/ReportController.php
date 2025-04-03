<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\FormSubmission;

class ReportController extends Controller
{
    public function index()
    {
        $partners = User::where('role', 'partner')->get(); // Filtramos solo partners
        return view('reports.index', compact('partners'));
    }

    public function getFormSubmissionByPartner(Request $request)
    {
        $query = FormSubmission::query();

        // Filtrar por fechas si están presentes
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        // Filtrar por partner si está seleccionado
        if ($request->filled('partner_id')) {
            $query->where('user_id', $request->partner_id);
        }

        // Agrupar por partner y contar formularios
        $data = $query->selectRaw('user_id, COUNT(*) as total')
            ->groupBy('user_id')
            ->with('user')
            ->get();

        $id = $data->map(fn($d) => $d->user->id);
        $labels = $data->map(fn($d) => $d->user->name);
        $counts = $data->map(fn($d) => $d->total);

        return response()->json([
            'id' => $id,
            'labels' => $labels,
            'data' => $counts,
        ]);
    }

    public function getFormSubmissionByPartnerDetail($user_id)
    {
        $FormSubmissions = FormSubmission::where('user_id', $user_id)
            ->with(['province']) // Cargar la provincia relacionada
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($FormSubmissions);
    }
}
