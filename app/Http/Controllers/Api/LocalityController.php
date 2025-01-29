<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Locality;
use Illuminate\Http\Request;

class LocalityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Obtener los parámetros de la URL
        $provinceId = $request->query('province_id');
        $zoneId = $request->query('zone_id');

        // Filtrar localidades según los parámetros recibidos
        $localities = Locality::when($provinceId, function ($query) use ($provinceId) {
            return $query->where('province_id', $provinceId);
        })
            ->when($zoneId, function ($query) use ($zoneId) {
                return $query->where('zone_id', $zoneId);
            })
            ->get();

        // Retornar la respuesta en formato JSON
        return response()->json($localities);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Locality $locality)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Locality $locality)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Locality $locality)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Locality $locality)
    {
        //
    }
}
