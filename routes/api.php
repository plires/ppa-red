<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProvinceController;
use App\Http\Controllers\Api\ZoneController;
use App\Http\Controllers\Api\LocalityController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Route::get('/provinces', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


// Obtener provincias (pública con cache y throttle)
Route::middleware('throttle:60,1')->get('/provinces', [ProvinceController::class, 'index']);
Route::middleware('throttle:60,1')->get('/zones', [ZoneController::class, 'index']);
Route::middleware('throttle:60,1')->get('/localities', [LocalityController::class, 'index']);
Route::middleware('throttle:60,1')->get('/get-zones/{provinceId}', [ZoneController::class, 'getZonesByProvinceId']);
