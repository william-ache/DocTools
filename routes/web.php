<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\ConsultorioController;
use App\Http\Controllers\Admin\ServicioController;
use App\Http\Controllers\Admin\MetodoPagoController;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TemplateController;
use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\Admin\FinanceController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Api\DocIaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
})->name('home');

Route::get('/calendario', function () {
    return view('calendario');
});

Route::get('/politica-privacidad', function () {
    return view('politica-privacidad');
})->name('politica-privacidad');

Route::get('/cookies', function () {
    return view('cookies');
})->name('cookies');

// Autenticación
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Panel Administrativo
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/admin/calendario', function () {
        $consultorios = \App\Models\Consultorio::all();
        $pacientes = \App\Models\Patient::latest()->get();
        return view('admin.calendario', compact('consultorios', 'pacientes'));
    })->name('admin.calendario');

    Route::resource('admin/consultorios', ConsultorioController::class)->names([
        'index' => 'consultorios.index',
        'create' => 'consultorios.create',
        'store' => 'consultorios.store',
        'edit' => 'consultorios.edit',
        'update' => 'consultorios.update',
        'destroy' => 'consultorios.destroy',
    ]);
    Route::post('admin/consultorios/{consultorio}/toggle-booking', [ConsultorioController::class, 'toggleBooking'])->name('consultorios.toggle-booking');

    Route::resource('admin/servicios', ServicioController::class)->names([
        'index' => 'servicios.index',
        'create' => 'servicios.create',
        'store' => 'servicios.store',
        'edit' => 'servicios.edit',
        'update' => 'servicios.update',
        'destroy' => 'servicios.destroy',
    ]);
    Route::post('admin/servicios/{servicio}/toggle-status', [ServicioController::class, 'toggleStatus'])->name('servicios.toggle-status');

    // Finanzas & Cobros
    Route::get('admin/finanzas', [FinanceController::class, 'index'])->name('finanzas.index');
    Route::post('admin/finanzas/cobros', [FinanceController::class, 'storeCobro'])->name('cobros.store');
    Route::put('admin/finanzas/cobros/{id}', [FinanceController::class, 'updateCobro'])->name('cobros.update');
    Route::delete('admin/finanzas/cobros/{id}', [FinanceController::class, 'destroyCobro'])->name('cobros.destroy');
    
    // Pagos a Personal
    Route::post('admin/finanzas/employee-payments', [FinanceController::class, 'storeEmployeePayment'])->name('employee-payments.store');
    Route::delete('admin/finanzas/employee-payments/{id}', [FinanceController::class, 'destroyEmployeePayment'])->name('employee-payments.destroy');

    // Empleados CRUD
    Route::resource('admin/employees', EmployeeController::class)->names([
        'index' => 'employees.index',
        'store' => 'employees.store',
        'update' => 'employees.update',
        'destroy' => 'employees.destroy',
    ]);
    
    Route::resource('admin/metodos', MetodoPagoController::class)->except(['index'])->names([
        'create' => 'metodos.create',
        'store' => 'metodos.store',
        'edit' => 'metodos.edit',
        'update' => 'metodos.update',
        'destroy' => 'metodos.destroy',
    ]);
    Route::post('admin/metodos/{finanza}/toggle-status', [MetodoPagoController::class, 'toggleStatus'])->name('metodos.toggle-status');

    Route::get('admin/pacientes/search', [PatientController::class, 'search'])->name('pacientes.search');
    Route::resource('admin/pacientes', PatientController::class)->names([
        'index' => 'pacientes.index',
        'create' => 'pacientes.create',
        'store' => 'pacientes.store',
        'edit' => 'pacientes.edit',
        'update' => 'pacientes.update',
        'destroy' => 'pacientes.destroy',
    ]);

    // Configuración
    Route::get('admin/configuracion', [SettingController::class, 'index'])->name('settings.index');
    Route::put('admin/configuracion', [SettingController::class, 'update'])->name('settings.update');
    Route::put('admin/configuracion/perfil', [SettingController::class, 'updateProfile'])->name('settings.updateProfile');
    Route::post('admin/configuracion/reset', [SettingController::class, 'reset'])->name('settings.reset');
    // Plantillas
    Route::resource('admin/plantillas', TemplateController::class)->names([
        'index' => 'templates.index',
        'store' => 'templates.store',
        'update' => 'templates.update',
        'destroy' => 'templates.destroy',
    ]);
    Route::post('admin/plantillas/{template}/toggle-status', [TemplateController::class, 'toggleStatus'])->name('templates.toggle-status');

    Route::post('admin/configuracion/foto', [SettingController::class, 'updatePhoto'])->name('settings.updatePhoto');

    Route::get('admin/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::post('admin/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
    Route::put('admin/appointments/{id}', [AppointmentController::class, 'update'])->name('appointments.update');
    Route::delete('admin/appointments/{id}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');

    // DocIA Routes
    Route::post('api/doc-ia/voice', [DocIaController::class, 'processVoice']);
});
