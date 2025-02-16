<?php

use App\Http\Controllers\FormSubmissionController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminMiddleware;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    return "Funciona!";
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rutas para interactuar con los formularios enviados por los clientes
    Route::get('/form_submissions', [FormSubmissionController::class, 'index'])->name('form_submissions.index');
    Route::get('/form_submissions/{formSubmission}', [FormSubmissionController::class, 'show'])->name('form_submissions.show');
    Route::get('/form_submissions/{formSubmission}/edit', [FormSubmissionController::class, 'edit'])->middleware('can:admin')->name('form_submissions.edit');
    Route::delete('/form_submissions/{formSubmission}', [FormSubmissionController::class, 'destroy'])->middleware('can:admin')->name('form_submissions.destroy');

    // Rutas protegidas: acceso únicamente para los admin
    Route::middleware(['auth', AdminMiddleware::class])->group(function () {
        // Rutas para provincias eliminadas
        Route::get('/provinces/trashed', [ProvinceController::class, 'trashed'])->name('provinces.trashed');
        Route::patch('/provinces/{id}/restore', [ProvinceController::class, 'restore'])->name('provinces.restore');

        // Rutas estándar de Provinces
        Route::get('/provinces', [ProvinceController::class, 'index'])->name('provinces.index');
        Route::get('/provinces/create', [ProvinceController::class, 'create'])->name('provinces.create'); // <- si hay formulario de creación
        Route::post('/provinces', [ProvinceController::class, 'store'])->name('provinces.store'); // <- si guardas nuevas provincias
        Route::get('/provinces/{province}/edit', [ProvinceController::class, 'edit'])->name('provinces.edit');
        Route::put('/provinces/{province}', [ProvinceController::class, 'update'])->name('provinces.update');
        Route::delete('/provinces/{province}', [ProvinceController::class, 'destroy'])->name('provinces.destroy');
        Route::get('/provinces/{province}', [ProvinceController::class, 'show'])->name('provinces.show');
    });
});

require __DIR__ . '/auth.php';
