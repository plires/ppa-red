<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ZoneController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LocalityController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\FormResponseController;
use App\Http\Controllers\FormSubmissionController;
use App\Http\Controllers\PublicFormSubmissionController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    return "Funciona!";
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/public/form_submission/{token}', [PublicFormSubmissionController::class, 'show'])
    ->name('public.form_submission.show');
Route::post('/public/form_submission/', [PublicFormSubmissionController::class, 'store'])
    ->name('public.form_submission.store');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rutas para interactuar con los formularios enviados por los clientes
    Route::get('/form_submissions', [FormSubmissionController::class, 'index'])->name('form_submissions.index');
    Route::get('/form_submissions/{formSubmission}', [FormSubmissionController::class, 'show'])->name('form_submissions.show');
    Route::get('/form_submissions/{formSubmission}/edit', [FormSubmissionController::class, 'edit'])->middleware('can:admin')->name('form_submissions.edit');
    Route::delete('/form_submissions/{formSubmission}', [FormSubmissionController::class, 'destroy'])->middleware('can:admin')->name('form_submissions.destroy');

    // Rutas estándar de Responses
    Route::post('/form_responses', [FormResponseController::class, 'store'])->name('form_responses.store');

    // Rutas protegidas: acceso únicamente para los admin
    Route::middleware(['auth', AdminMiddleware::class])->group(function () {
        // Rutas para provincias eliminadas
        Route::get('/provinces/trashed', [ProvinceController::class, 'trashed'])->name('provinces.trashed');
        Route::patch('/provinces/{id}/restore', [ProvinceController::class, 'restore'])->name('provinces.restore');

        // Rutas estándar de Provinces
        Route::get('/provinces', [ProvinceController::class, 'index'])->name('provinces.index');
        Route::get('/provinces/create', [ProvinceController::class, 'create'])->name('provinces.create');
        Route::post('/provinces', [ProvinceController::class, 'store'])->name('provinces.store');
        Route::get('/provinces/{province}/edit', [ProvinceController::class, 'edit'])->name('provinces.edit');
        Route::put('/provinces/{province}', [ProvinceController::class, 'update'])->name('provinces.update');
        Route::delete('/provinces/{province}', [ProvinceController::class, 'destroy'])->name('provinces.destroy');
        Route::get('/provinces/{province}', [ProvinceController::class, 'show'])->name('provinces.show');

        // Rutas para zonas eliminadas
        Route::get('/zones/trashed', [ZoneController::class, 'trashed'])->name('zones.trashed');
        Route::patch('/zones/{id}/restore', [ZoneController::class, 'restore'])->name('zones.restore');

        // Rutas estándar de zones
        Route::get('/zones', [ZoneController::class, 'index'])->name('zones.index');
        Route::get('/zones/create', [ZoneController::class, 'create'])->name('zones.create');
        Route::post('/zones', [ZoneController::class, 'store'])->name('zones.store');
        Route::get('/zones/{zone}/edit', [ZoneController::class, 'edit'])->name('zones.edit');
        Route::put('/zones/{zone}', [ZoneController::class, 'update'])->name('zones.update');
        Route::delete('/zones/{zone}', [ZoneController::class, 'destroy'])->name('zones.destroy');
        Route::get('/zones/{zone}', [ZoneController::class, 'show'])->name('zones.show');

        // Rutas para localidades eliminadas
        Route::get('/localities/trashed', [LocalityController::class, 'trashed'])->name('localities.trashed');
        Route::patch('/localities/{id}/restore', [LocalityController::class, 'restore'])->name('localities.restore');

        // Rutas estándar de localidades
        Route::get('/localities', [LocalityController::class, 'index'])->name('localities.index');
        Route::get('/localities/create', [LocalityController::class, 'create'])->name('localities.create');
        Route::post('/localities', [LocalityController::class, 'store'])->name('localities.store');
        Route::get('/localities/{locality}/edit', [LocalityController::class, 'edit'])->name('localities.edit');
        Route::put('/localities/{locality}', [LocalityController::class, 'update'])->name('localities.update');
        Route::delete('/localities/{locality}', [LocalityController::class, 'destroy'])->name('localities.destroy');
        Route::get('/localities/{locality}', [LocalityController::class, 'show'])->name('localities.show');

        // Rutas para Partners (usuarios) eliminadas
        Route::get('/partners/trashed', [UserController::class, 'trashed'])->name('partners.trashed');
        Route::patch('/partners/{id}/restore', [UserController::class, 'restore'])->name('partners.restore');

        // Rutas estándar de Partners (usuarios)
        Route::get('/partners', [UserController::class, 'index'])->name('partners.index');
        Route::get('/partners/create', [UserController::class, 'create'])->name('partners.create');
        Route::post('/partners', [UserController::class, 'store'])->name('partners.store');
        Route::get('/partners/{partner}/edit', [UserController::class, 'edit'])->name('partners.edit');
        Route::put('/partners/{partner}', [UserController::class, 'update'])->name('partners.update');
        Route::delete('/partners/{partner}', [UserController::class, 'destroy'])->name('partners.destroy');
        Route::get('/partners/{partner}', [UserController::class, 'show'])->name('partners.show');
    });
});

require __DIR__ . '/auth.php';
