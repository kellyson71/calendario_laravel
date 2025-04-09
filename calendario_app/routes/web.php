<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\Admin\AgendamentoController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

// Rotas de autenticação
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rota para criar usuário padrão
Route::get('/create-default-user', [AuthController::class, 'createDefaultUser']);

// Rotas protegidas por autenticação
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [CalendarController::class, 'dashboard'])->name('dashboard');

    // Calendário
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');

    // Agendamentos
    Route::resource('appointments', AppointmentController::class);

    // AJAX para buscar agendamentos por data
    Route::get('/appointments/by-date', [AppointmentController::class, 'getAppointmentsByDate'])->name('appointments.by-date');

    // Clientes
    Route::resource('clients', ClientController::class);
    Route::get('/clients/{client}/history', [ClientController::class, 'history'])->name('clients.history');
});

// Rotas de administração
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.agendamentos.index');
    })->name('dashboard');

    Route::resource('agendamentos', AgendamentoController::class);
});
