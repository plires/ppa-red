<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\FormSubmission;
use Illuminate\Support\Facades\DB;
use App\Models\FormSubmissionStatus;

class ReportController extends Controller
{
    public function index()
    {
        $partners = User::where('role', User::PARTNER_USER)->get(); // Filtramos solo partners
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

    public function getFormSubmissionByPartnerDetail($user_id, $start, $end)
    {

        $formSubmissions = FormSubmission::where('user_id', $user_id)
            ->with(['locality']) // Cargar la localidad relacionada
            ->whereBetween('created_at', [$start, $end])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($formSubmissions);
    }

    public function statusChart()
    {
        $partners = User::where('role', User::PARTNER_USER)->get(); // Ajustá esto a tu lógica de roles

        $statuses = [
            FormSubmissionStatus::STATUS_PENDIENTE_RTA_DE_PARTNER => "#007bff",
            FormSubmissionStatus::STATUS_RESPONDIO_PARTNER => "#28a745",
            FormSubmissionStatus::STATUS_DEMORADO_POR_PARTNER => "#dc3545",
            FormSubmissionStatus::STATUS_CERRADO_SIN_RTA_PARTNER => "#ffc107",
            FormSubmissionStatus::STATUS_CERRADO_SIN_RTA_USUARIO => "#6c757d",
            FormSubmissionStatus::STATUS_CERRADO_POR_EL_PARTNER => "#343a40",
        ];

        return view('reports.status_chart', compact('partners', 'statuses'));
    }

    public function formStatusChart(Request $request)
    {
        $query = FormSubmission::query();

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        if ($request->filled('partner_id')) {
            $query->where('user_id', $request->partner_id);
        }

        $results = $query->select('form_submission_status_id', DB::raw('count(*) as total'))
            ->groupBy('form_submission_status_id')
            ->with('status') // Asegurate que tenga la relación en el modelo
            ->get();

        $labels = [];
        $data = [];

        foreach ($results as $row) {
            $labels[] = $row->status->name;
            $data[] = [
                'label' => $row->status->name,
                'value' => $row->total,
                'id' => $row->form_submission_status_id,
            ];
        }

        return response()->json([
            'labels' => $labels,
            'data' => array_column($data, 'value'),
            'status_ids' => array_column($data, 'id'),
        ]);
    }

    public function getFormulariosByStatus($user_id, $status_id, $start, $end)
    {

        $query = FormSubmission::with(['status']) // Añadí relaciones que necesites
            ->where('form_submission_status_id', (int)$status_id)
            ->whereBetween('created_at', [$start, $end])
            ->orderBy('created_at', 'desc');

        if ($user_id != "null") {
            $query->where('user_id', $user_id);
        }

        return response()->json($query->get());
    }
}
