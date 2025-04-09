<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Client;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    /**
     * Exibir todos os agendamentos.
     */
    public function index()
    {
        $appointments = Appointment::where('user_id', auth()->id())
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        return view('appointments.index', compact('appointments'));
    }

    /**
     * Mostrar formulário para criar um novo agendamento.
     */
    public function create(Request $request)
    {
        // Verificar se uma data foi passada via parâmetro GET
        $selectedDate = $request->input('date', now()->format('Y-m-d'));

        // Listar todos os clientes para o select
        $clients = Client::where('user_id', auth()->id())
            ->orderBy('name')
            ->get();

        // Verificar se um cliente específico foi selecionado
        $selectedClientId = $request->input('client_id');

        return view('appointments.create', compact('selectedDate', 'clients', 'selectedClientId'));
    }

    /**
     * Armazenar um novo agendamento.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'nullable',
            'client_id' => 'nullable|exists:clients,id',
            'client_name' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
        ]);

        $validatedData['user_id'] = auth()->id();

        Appointment::create($validatedData);

        return redirect()->route('dashboard')
            ->with('success', 'Agendamento criado com sucesso!');
    }

    /**
     * Exibir um agendamento específico.
     */
    public function show(Appointment $appointment)
    {
        // Verificar se o agendamento pertence ao usuário atual
        if ($appointment->user_id !== auth()->id()) {
            abort(403, 'Acesso não autorizado.');
        }

        return view('appointments.show', compact('appointment'));
    }

    /**
     * Mostrar formulário para editar um agendamento.
     */
    public function edit(Appointment $appointment)
    {
        // Verificar se o agendamento pertence ao usuário atual
        if ($appointment->user_id !== auth()->id()) {
            abort(403, 'Acesso não autorizado.');
        }

        // Listar todos os clientes para o select
        $clients = Client::where('user_id', auth()->id())
            ->orderBy('name')
            ->get();

        return view('appointments.edit', compact('appointment', 'clients'));
    }

    /**
     * Atualizar um agendamento.
     */
    public function update(Request $request, Appointment $appointment)
    {
        // Verificar se o agendamento pertence ao usuário atual
        if ($appointment->user_id !== auth()->id()) {
            abort(403, 'Acesso não autorizado.');
        }

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'nullable',
            'client_id' => 'nullable|exists:clients,id',
            'client_name' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
        ]);

        $appointment->update($validatedData);

        return redirect()->route('appointments.index')
            ->with('success', 'Agendamento atualizado com sucesso!');
    }

    /**
     * Remover um agendamento.
     */
    public function destroy(Appointment $appointment)
    {
        // Verificar se o agendamento pertence ao usuário atual
        if ($appointment->user_id !== auth()->id()) {
            abort(403, 'Acesso não autorizado.');
        }

        $appointment->delete();

        return redirect()->route('appointments.index')
            ->with('success', 'Agendamento excluído com sucesso!');
    }

    /**
     * Buscar agendamentos para uma data específica (usado em AJAX).
     */
    public function getAppointmentsByDate(Request $request)
    {
        $date = $request->input('date');

        $appointments = Appointment::where('user_id', auth()->id())
            ->where('date', $date)
            ->orderBy('start_time')
            ->get();

        return response()->json($appointments);
    }
}
