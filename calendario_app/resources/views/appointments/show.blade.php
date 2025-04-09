@extends('layouts.app')

@section('title', 'Detalhes do Agendamento')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Detalhes do Agendamento</h2>
        <div class="flex space-x-2">
            <a href="{{ route('appointments.edit', $appointment->id) }}" class="bg-indigo-600 text-white py-2 px-4 rounded hover:bg-indigo-700 inline-flex items-center">
                <i class="fas fa-edit mr-2"></i> Editar
            </a>
            <a href="{{ route('appointments.index') }}" class="bg-gray-500 text-white py-2 px-4 rounded hover:bg-gray-600 inline-flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Voltar
            </a>
        </div>
    </div>

    <div class="bg-blue-50 p-4 rounded-lg mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-xl font-semibold text-blue-800 mb-4">Informações do Agendamento</h3>

                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-1">Título:</p>
                    <p class="text-lg font-medium text-gray-900">{{ $appointment->title }}</p>
                </div>

                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-1">Descrição:</p>
                    <p class="text-gray-900">{{ $appointment->description ?? 'Nenhuma descrição fornecida.' }}</p>
                </div>

                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-1">Status:</p>
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            @if($appointment->status == 'scheduled') bg-green-100 text-green-800 
                            @elseif($appointment->status == 'completed') bg-blue-100 text-blue-800 
                            @elseif($appointment->status == 'cancelled') bg-red-100 text-red-800 
                            @else bg-gray-100 text-gray-800 @endif">
                        {{ ucfirst($appointment->status) }}
                    </span>
                </div>
            </div>

            <div>
                <h3 class="text-xl font-semibold text-blue-800 mb-4">Detalhes da Agenda</h3>

                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-1">Data:</p>
                    <p class="text-lg font-medium text-gray-900">{{ $appointment->date->format('d/m/Y') }}</p>
                </div>

                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-1">Horário:</p>
                    <p class="text-lg font-medium text-gray-900">
                        {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}
                        @if($appointment->end_time)
                        - {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}
                        @endif
                    </p>
                </div>

                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-1">Cliente:</p>
                    @if($appointment->client_id)
                    <div class="flex items-center">
                        <p class="text-lg font-medium text-gray-900">{{ $appointment->client->name }}</p>
                        <a href="{{ route('clients.show', $appointment->client_id) }}" class="ml-2 text-blue-600 hover:text-blue-800">
                            <i class="fas fa-external-link-alt"></i> Ver ficha
                        </a>
                    </div>
                    @if($appointment->client->phone)
                    <p class="text-sm text-gray-600">
                        <i class="fas fa-phone text-gray-400 mr-1"></i> {{ $appointment->client->phone }}
                    </p>
                    @endif
                    @if($appointment->client->email)
                    <p class="text-sm text-gray-600">
                        <i class="fas fa-envelope text-gray-400 mr-1"></i> {{ $appointment->client->email }}
                    </p>
                    @endif
                    @else
                    <p class="text-lg font-medium text-gray-900">{{ $appointment->client_name ?? 'Não especificado' }}</p>
                    @if($appointment->client_name)
                    <div class="mt-1">
                        <a href="{{ route('clients.create') }}" class="text-blue-600 hover:underline text-sm">
                            <i class="fas fa-plus-circle"></i> Cadastrar como cliente
                        </a>
                    </div>
                    @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="border-t border-gray-200 pt-4 flex justify-between items-center">
        <p class="text-sm text-gray-600">
            Criado em: {{ $appointment->created_at->format('d/m/Y H:i') }}
            @if($appointment->created_at != $appointment->updated_at)
            | Atualizado em: {{ $appointment->updated_at->format('d/m/Y H:i') }}
            @endif
        </p>

        <form action="{{ route('appointments.destroy', $appointment->id) }}" method="POST" class="inline-block">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-600 text-white py-2 px-4 rounded hover:bg-red-700 inline-flex items-center" onclick="return confirm('Tem certeza que deseja excluir este agendamento?')">
                <i class="fas fa-trash-alt mr-2"></i> Excluir
            </button>
        </form>
    </div>
</div>
@endsection