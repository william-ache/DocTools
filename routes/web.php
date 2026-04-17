<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\ConsultorioController;
use App\Http\Controllers\Admin\ServicioController;
use App\Http\Controllers\Admin\MetodoPagoController;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Api\DocIaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
})->name('home');

Route::get('/calendario', function () {
    return view('calendario');
});

// Autenticación
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Panel Administrativo
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

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

    Route::resource('admin/finanzas', MetodoPagoController::class)->names([
        'index' => 'metodos.index',
        'create' => 'metodos.create',
        'store' => 'metodos.store',
        'edit' => 'metodos.edit',
        'update' => 'metodos.update',
        'destroy' => 'metodos.destroy',
    ]);
    Route::post('admin/finanzas/{finanza}/toggle-status', [MetodoPagoController::class, 'toggleStatus'])->name('metodos.toggle-status');

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

    // DocIA Routes
    Route::post('api/doc-ia/voice', [DocIaController::class, 'processVoice']);
});
