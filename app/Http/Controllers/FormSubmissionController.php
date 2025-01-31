<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FormSubmission;
use Illuminate\Support\Facades\Auth;

class FormSubmissionController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = FormSubmission::query();

        // Si el usuario es 'partner', solo puede ver sus propios formularios
        if ($user->role === 'partner') {
            $query->where('user_id', $user->id);
        }

        // Aplicar filtrado y ordenación
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('sort_by') && in_array($request->sort_by, ['name', 'created_at'])) {
            $query->orderBy($request->sort_by, $request->get('order', 'asc'));
        }

        // Obtener resultados con paginación
        $formSubmissions = $query->paginate(10);

        return view('form_submissions.index', compact('formSubmissions'));
    }
}
