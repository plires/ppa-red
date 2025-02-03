<?php

use App\Http\Controllers\FormSubmissionController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    return view('test');
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
    Route::get('/form_submissions/{id}/edit', [FormSubmissionController::class, 'edit'])->middleware('can:admin')->name('form_submissions.edit');
    Route::delete('/form_submissions/{id}', [FormSubmissionController::class, 'destroy'])->middleware('can:admin')->name('form_submissions.destroy');
});

require __DIR__ . '/auth.php';
