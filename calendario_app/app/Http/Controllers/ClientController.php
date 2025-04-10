<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Appointment;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients = Client::where('user_id', auth()->id())
            ->orderBy('name')
            ->get();

        return view('clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // Verifica se há um nome pré-definido vindo do formulário de agendamento
        $name = $request->query('name');

        // Adicionar redirect_to como query string se existir
        $redirect_to = $request->query('redirect_to', '');

        return view('clients.create', compact('name', 'redirect_to'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $validatedData['user_id'] = auth()->id();

        $client = Client::create($validatedData);

        // Verificar se há um parâmetro de redirecionamento
        if ($request->filled('redirect_to') && $request->input('redirect_to') === 'appointments.create') {
            return redirect()->route('appointments.create', ['client_id' => $client->id])
                ->with('success', 'Cliente cadastrado com sucesso!');
        }

        return redirect()->route('clients.index')
            ->with('success', 'Cliente cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        // Verificar se o cliente pertence ao usuário atual
        if ($client->user_id !== auth()->id()) {
            abort(403, 'Acesso não autorizado.');
        }

        // Buscar os agendamentos deste cliente
        $appointments = Appointment::where('client_id', $client->id)
            ->orderBy('date', 'desc')
            ->orderBy('start_time')
            ->get();

        return view('clients.show', compact('client', 'appointments'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        // Verificar se o cliente pertence ao usuário atual
        if ($client->user_id !== auth()->id()) {
            abort(403, 'Acesso não autorizado.');
        }

        return view('clients.edit', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        // Verificar se o cliente pertence ao usuário atual
        if ($client->user_id !== auth()->id()) {
            abort(403, 'Acesso não autorizado.');
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $client->update($validatedData);

        return redirect()->route('clients.index')
            ->with('success', 'Cliente atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        // Verificar se o cliente pertence ao usuário atual
        if ($client->user_id !== auth()->id()) {
            abort(403, 'Acesso não autorizado.');
        }

        // Remover o cliente
        $client->delete();

        return redirect()->route('clients.index')
            ->with('success', 'Cliente excluído com sucesso!');
    }

    /**
     * Display client history with all appointments.
     */
    public function history(Client $client)
    {
        // Verificar se o cliente pertence ao usuário atual
        if ($client->user_id !== auth()->id()) {
            abort(403, 'Acesso não autorizado.');
        }

        // Buscar todos os agendamentos deste cliente
        $appointments = Appointment::where('client_id', $client->id)
            ->orderBy('date', 'desc')
            ->orderBy('start_time')
            ->paginate(10);

        return view('clients.history', compact('client', 'appointments'));
    }

    /**
     * Display a printable client form.
     */
    public function printForm(Client $client)
    {
        // Verificar se o cliente pertence ao usuário atual
        if ($client->user_id !== auth()->id()) {
            abort(403, 'Acesso não autorizado.');
        }

        // Buscar os agendamentos deste cliente
        $appointments = Appointment::where('client_id', $client->id)
            ->orderBy('date', 'desc')
            ->orderBy('start_time')
            ->take(5)
            ->get();

        return view('clients.print_form', compact('client', 'appointments'));
    }
}
