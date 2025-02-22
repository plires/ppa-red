<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Zone;
use Illuminate\Http\Request;

class ZoneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Obtener el parámetro province_id de la URL
        $provinceId = $request->query('province_id');

        // Si se pasa province_id, filtrar por provincia; si no, devolver todas las zonas
        $zones = Zone::when($provinceId, function ($query) use ($provinceId) {
            return $query->where('province_id', $provinceId);
        })->get();

        // Devolver la respuesta en formato JSON
        return response()->json($zones);
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
    public function show(Zone $zone)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Zone $zone)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Zone $zone)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Zone $zone)
    {
        //
    }

    public function getZonesByProvinceId($provinceId)
    {
        return response()->json(Zone::where('province_id', $provinceId)->get());
    }
}
