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
                    <label for="client_search" class="block text-sm font-medium text-gray-700 mb-1">Cliente Cadastrado</label>
                    <div class="relative">
                        <input type="text" id="client_search" placeholder="Digite o nome do cliente..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <input type="hidden" name="client_id" id="client_id" value="{{ old('client_id', $selectedClientId) }}">

                        <div id="client_results" class="absolute z-10 mt-1 w-full bg-white shadow-lg rounded-md border border-gray-300 hidden max-h-60 overflow-y-auto">
                        </div>

                        <div id="no_results" class="absolute z-10 mt-1 w-full bg-white shadow-lg rounded-md border border-gray-300 hidden p-3">
                            <p class="text-gray-600 mb-2">Nenhum cliente encontrado com este nome</p>
                            <button type="button" id="create_client_btn" class="w-full bg-blue-600 text-white py-2 px-3 rounded hover:bg-blue-700 text-sm flex items-center justify-center">
                                <i class="fas fa-plus-circle mr-2"></i> Criar novo cliente
                            </button>
                        </div>
                    </div>
                    <div class="mt-1 text-sm text-gray-500" id="selected_client_info"></div>
                </div>

                <div class="mb-4">
                    <label for="client_name" class="block text-sm font-medium text-gray-700 mb-1">ou Nome do Cliente Avulso</label>
                    <input type="text" name="client_name" id="client_name" value="{{ old('client_name') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <div class="mt-1 text-sm text-gray-500">
                        Para servidores não cadastrados no sistema
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
    // Lista de todos os clientes para o autocomplete
    const allClients = JSON.parse('{!! json_encode($clients->map(function($client) { return ["id" => $client->id, "name" => $client->name, "phone" => $client->phone ?? "", "display" => $client->name . ($client->phone ? " - " . $client->phone : "")]; })) !!}');

    // Elementos do DOM
    const clientSearchInput = document.getElementById('client_search');
    const clientIdInput = document.getElementById('client_id');
    const clientResults = document.getElementById('client_results');
    const noResults = document.getElementById('no_results');
    const createClientBtn = document.getElementById('create_client_btn');
    const clientNameInput = document.getElementById('client_name');
    const selectedClientInfo = document.getElementById('selected_client_info');

    // Ao digitar, filtra os clientes e mostra os resultados
    clientSearchInput.addEventListener('input', function() {
        const searchText = this.value.toLowerCase().trim();

        if (searchText === '') {
            clientResults.classList.add('hidden');
            noResults.classList.add('hidden');
            return;
        }

        // Filtrar os clientes pelo texto de busca
        const filteredClients = allClients.filter(client =>
            client.name.toLowerCase().includes(searchText) ||
            (client.phone && client.phone.includes(searchText))
        );

        // Se não encontrar resultados
        if (filteredClients.length === 0) {
            clientResults.classList.add('hidden');
            noResults.classList.remove('hidden');
            return;
        }

        // Mostrar os resultados encontrados
        clientResults.innerHTML = '';
        filteredClients.forEach(client => {
            const resultItem = document.createElement('div');
            resultItem.className = 'px-4 py-2 hover:bg-blue-50 cursor-pointer';
            resultItem.textContent = client.display;
            resultItem.addEventListener('click', () => selectClient(client));
            clientResults.appendChild(resultItem);
        });

        clientResults.classList.remove('hidden');
        noResults.classList.add('hidden');
    });

    // Função para selecionar um cliente
    function selectClient(client) {
        clientIdInput.value = client.id;
        clientSearchInput.value = client.name;
        clientResults.classList.add('hidden');

        // Atualiza a informação do cliente selecionado
        selectedClientInfo.innerHTML = `<span class="text-blue-600"><i class="fas fa-check-circle"></i> Cliente selecionado: ${client.display}</span>`;

        // Desabilita o campo de nome avulso
        clientNameInput.value = '';
        clientNameInput.disabled = true;
    }

    // Botão para criar novo cliente
    createClientBtn.addEventListener('click', function() {
        const searchText = clientSearchInput.value.trim();
        if (searchText) {
            // Redireciona para a página de criação de cliente com o nome preenchido
            window.location.href = "{{ route('clients.create') }}?name=" + encodeURIComponent(searchText) + "&redirect_to=appointments.create";
        } else {
            window.location.href = "{{ route('clients.create') }}?redirect_to=appointments.create";
        }
    });

    // Clicar fora do autocomplete fecha os resultados
    document.addEventListener('click', function(e) {
        if (!clientSearchInput.contains(e.target) && !clientResults.contains(e.target) && !noResults.contains(e.target)) {
            clientResults.classList.add('hidden');
            noResults.classList.add('hidden');
        }
    });

    // Verifica se já existe um cliente selecionado ao carregar a página
    document.addEventListener('DOMContentLoaded', function() {
        const clientId = clientIdInput.value;
        if (clientId) {
            const selectedClient = allClients.find(client => client.id == clientId);
            if (selectedClient) {
                clientSearchInput.value = selectedClient.name;
                selectedClientInfo.innerHTML = `<span class="text-blue-600"><i class="fas fa-check-circle"></i> Cliente selecionado: ${selectedClient.display}</span>`;
                clientNameInput.disabled = true;
            }
        } else {
            clientNameInput.disabled = false;
        }
    });

    // Ao digitar no campo de nome avulso, limpa o cliente selecionado
    clientNameInput.addEventListener('input', function() {
        if (this.value.trim() !== '') {
            clientIdInput.value = '';
            clientSearchInput.value = '';
            selectedClientInfo.innerHTML = '';
        }
    });
</script>
@endsection