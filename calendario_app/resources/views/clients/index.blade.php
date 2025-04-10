@extends('layouts.app')

@section('title', 'Clientes')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-semibold text-gray-800">Fichas de Servidores</h2>
        <a href="{{ route('clients.create') }}" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 inline-flex items-center">
            <i class="fas fa-plus mr-2"></i> Novo Cliente
        </a>
    </div>

    @if(count($clients) > 0)
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200">
            <thead>
                <tr>
                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                        Nome
                    </th>
                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                        Contato
                    </th>
                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                        Agendamentos
                    </th>
                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                        Ações
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($clients as $client)
                <tr>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <span class="text-blue-800 font-semibold text-lg">{{ strtoupper(substr($client->name, 0, 1)) }}</span>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm leading-5 font-medium text-gray-900">{{ $client->name }}</div>
                                @if($client->birth_date)
                                <div class="text-sm leading-5 text-gray-500">
                                    {{ $client->birth_date->format('d/m/Y') }}
                                    ({{ $client->birth_date->age }} anos)
                                </div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                        @if($client->phone)
                        <div class="text-sm leading-5 text-gray-900">
                            <i class="fas fa-phone text-gray-400 mr-1"></i> {{ $client->phone }}
                        </div>
                        @endif
                        @if($client->email)
                        <div class="text-sm leading-5 text-gray-900">
                            <i class="fas fa-envelope text-gray-400 mr-1"></i> {{ $client->email }}
                        </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                        <div class="text-sm leading-5 text-center">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $client->appointments->count() }}
                            </span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap text-right border-b border-gray-200 text-sm leading-5 font-medium">
                        <div class="flex space-x-3">
                            <a href="{{ route('clients.show', $client->id) }}" class="text-blue-600 hover:text-blue-900">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('clients.edit', $client->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="{{ route('clients.history', $client->id) }}" class="text-green-600 hover:text-green-900" title="Histórico">
                                <i class="fas fa-history"></i>
                            </a>
                            <a href="{{ route('appointments.create', ['client_id' => $client->id]) }}" class="text-teal-600 hover:text-teal-900" title="Novo Agendamento">
                                <i class="fas fa-calendar-plus"></i>
                            </a>
                            <form action="{{ route('clients.destroy', $client->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Tem certeza que deseja excluir este cliente? Todos os agendamentos vinculados serão mantidos.')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="bg-blue-50 py-4 px-6 rounded text-center">
        <p class="text-blue-600">Nenhum cliente cadastrado. <a href="{{ route('clients.create') }}" class="text-blue-800 underline">Clique aqui</a> para cadastrar um novo.</p>
    </div>
    @endif
</div>
@endsection