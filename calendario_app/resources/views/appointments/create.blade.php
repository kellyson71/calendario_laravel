@extends('layouts.app')

@section('title', 'Novo Agendamento')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Novo Agendamento</h2>
        <a href="{{ route('appointments.index') }}" class="bg-gray-500 text-white py-2 px-4 rounded hover:bg-gray-600 inline-flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Voltar
        </a>
    </div>

    @if ($errors->any())
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
        <p class="font-bold">Ocorreram erros:</p>
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('appointments.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Título *</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
                    <textarea name="description" id="description" rows="4"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('description') }}</textarea>
                </div>

                <div class="mb-4">
                    <label for="client_id" class="block text-sm font-medium text-gray-700 mb-1">Cliente Cadastrado</label>
                    <select name="client_id" id="client_id"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                        onchange="clientSelectedHandler(this.value)">
                        <option value="">Selecione um cliente...</option>
                        @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ old('client_id', $selectedClientId) == $client->id ? 'selected' : '' }}>
                            {{ $client->name }} {{ $client->phone ? '- ' . $client->phone : '' }}
                        </option>
                        @endforeach
                    </select>
                    <div class="mt-1 text-sm text-gray-500">
                        <a href="{{ route('clients.create') }}" class="text-blue-600 hover:underline" target="_blank">
                            <i class="fas fa-plus-circle"></i> Cadastrar novo cliente
                        </a>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="client_name" class="block text-sm font-medium text-gray-700 mb-1">ou Nome do Cliente Avulso</label>
                    <input type="text" name="client_name" id="client_name" value="{{ old('client_name') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <div class="mt-1 text-sm text-gray-500">
                        Para clientes não cadastrados no sistema
                    </div>
                </div>
            </div>

            <div>
                <div class="mb-4">
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Data *</label>
                    <input type="date" name="date" id="date" value="{{ old('date', $selectedDate) }}" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="mb-4">
                    <label for="start_time" class="block text-sm font-medium text-gray-700 mb-1">Horário de Início *</label>
                    <input type="time" name="start_time" id="start_time" value="{{ old('start_time', '08:00') }}" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="mb-4">
                    <label for="end_time" class="block text-sm font-medium text-gray-700 mb-1">Horário de Término</label>
                    <input type="time" name="end_time" id="end_time" value="{{ old('end_time', '09:00') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="mb-4">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" id="status"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="scheduled" {{ old('status') == 'scheduled' ? 'selected' : '' }}>Agendado</option>
                        <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Concluído</option>
                        <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 inline-flex items-center">
                <i class="fas fa-save mr-2"></i> Salvar Agendamento
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    function clientSelectedHandler(clientId) {
        // Se um cliente foi selecionado, limpa o campo de nome avulso
        if (clientId) {
            document.getElementById('client_name').value = '';
            document.getElementById('client_name').disabled = true;
        } else {
            document.getElementById('client_name').disabled = false;
        }
    }

    // Executar ao carregar a página
    document.addEventListener('DOMContentLoaded', function() {
        const clientId = document.getElementById('client_id').value;
        clientSelectedHandler(clientId);
    });
</script>
@endsection