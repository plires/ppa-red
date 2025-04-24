<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ZoneController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LocalityController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\FormResponseController;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Controllers\FormSubmissionController;
use App\Http\Controllers\FormNotificationController;
use App\Http\Controllers\PublicFormResponseController;
use App\Http\Controllers\PublicFormSubmissionController;

Route::get('/', function () {
    // No necesita lógica, el middleware maneja la redirección
})->middleware(RedirectIfAuthenticated::class);

// mostrar formulario con sus datos
Route::get('/public/form_submission/{token}', [PublicFormSubmissionController::class, 'show'])
    ->name('public.form_submission.show');

// ruta para que el usuario publico agregue FormResponses
Route::post('/public/form_responses/', [PublicFormResponseController::class, 'store'])
    ->name('public.form_responses.store');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rutas para interactuar con las notificaciones
    Route::get(
        '/notification/{notification}/{formSubmission}',
        [FormNotificationController::class, 'markAsReadAndRedirect']
    )->name('notification.mark_as_read');

    Route::post(
        '/notification',
        [FormNotificationController::class, 'markAsReadAllNotifications']
    )->name('notification.mark_as_all_read');

    // Rutas para interactuar con los formularios enviados por los clientes
    Route::get('/form_submissions', [FormSubmissionController::class, 'index'])->name('form_submissions.index');
    Route::get('/form_submissions/{formSubmission}', [FormSubmissionController::class, 'show'])->name('form_submissions.show');
    Route::get('/form_submissions/{formSubmission}/edit', [FormSubmissionController::class, 'edit'])->middleware('can:admin')->name('form_submissions.edit');
    Route::put('/form_submissions/{formSubmission}', [FormSubmissionController::class, 'update'])->name('form_submissions.update');
    Route::delete('/form_submissions/{formSubmission}', [FormSubmissionController::class, 'destroy'])->middleware('can:admin')->name('form_submissions.destroy');

    // Rutas estándar de Responses
    Route::post('/form_responses', [FormResponseController::class, 'store'])->name('form_responses.store');
    Route::get(
        '/form_responses/{formResponse}',
        [FormResponseController::class, 'markAsReadAndRedirect']
    )->name('responses.mark_as_read');

    Route::post(
        '/form_responses/mark_as_all_read/{user}',
        [FormResponseController::class, 'markAsReadAllResponses']
    )->name('responses.mark_as_all_read');

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

        // Rutas para interactuar con los reportes
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/forms-by-partner', [ReportController::class, 'getFormSubmissionByPartner'])
            ->name('reports.form_submissions_by_partner');
        Route::get('/reports/form_submissions/{user_id}/{start}/{end}', [ReportController::class, 'getFormSubmissionByPartnerDetail'])
            ->name('reportes.form_submissionsDetail');

        Route::get('/reports/status-chart', [ReportController::class, 'statusChart'])
            ->name('reports.status_chart');
        Route::get('/reports/form_status_chart', [ReportController::class, 'formStatusChart'])->name('reports.form_status_chart');

        Route::get('/reports/form_submissions_by_status/{user_id}/{status_id}/{start}/{end}', [ReportController::class, 'getFormulariosByStatus'])->name('reports.form_status_chart_detail');
    });
});

require __DIR__ . '/auth.php';
