@extends('layouts.app')

@section('title', 'Histórico do Cliente')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Histórico de Agendamentos</h2>
        <div class="flex space-x-2">
            <a href="{{ route('clients.show', $client->id) }}" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 inline-flex items-center">
                <i class="fas fa-user mr-2"></i> Perfil do Cliente
            </a>
            <a href="{{ route('appointments.create', ['client_id' => $client->id]) }}" class="bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700 inline-flex items-center">
                <i class="fas fa-calendar-plus mr-2"></i> Novo Agendamento
            </a>
        </div>
    </div>

    <div class="mb-6 bg-blue-50 p-4 rounded-lg">
        <div class="flex items-center">
            <div class="h-12 w-12 bg-blue-100 rounded-full flex items-center justify-center">
                <span class="text-blue-800 font-bold text-xl">{{ strtoupper(substr($client->name, 0, 1)) }}</span>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-800">{{ $client->name }}</h3>
                <div class="flex space-x-4 text-sm text-gray-600">
                    @if($client->phone)
                    <span><i class="fas fa-phone text-blue-800 mr-1"></i> {{ $client->phone }}</span>
                    @endif

                    @if($client->email)
                    <span><i class="fas fa-envelope text-blue-800 mr-1"></i> {{ $client->email }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="mb-4">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Todos os Agendamentos</h3>

        @if(count($appointments) > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            Data e Hora
                        </th>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            Título/Descrição
                        </th>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            Ações
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @foreach($appointments as $appointment)
                    <tr>
                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                            <div class="text-sm leading-5 font-medium text-gray-900">
                                {{ $appointment->date->format('d/m/Y') }}
                            </div>
                            <div class="text-sm leading-5 text-gray-500">
                                {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}
                                @if($appointment->end_time)
                                - {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 border-b border-gray-200">
                            <div class="text-sm leading-5 font-medium text-gray-900">{{ $appointment->title }}</div>
                            <div class="text-sm leading-5 text-gray-500">{{ Str::limit($appointment->description, 100) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($appointment->status == 'scheduled') bg-green-100 text-green-800 
                                    @elseif($appointment->status == 'completed') bg-blue-100 text-blue-800 
                                    @elseif($appointment->status == 'cancelled') bg-red-100 text-red-800 
                                    @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($appointment->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('appointments.show', $appointment->id) }}" class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('appointments.edit', $appointment->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $appointments->links() }}
        </div>
        @else
        <div class="bg-blue-50 py-4 px-6 rounded text-center">
            <p class="text-blue-600">Nenhum agendamento registrado para este cliente.</p>
            <a href="{{ route('appointments.create', ['client_id' => $client->id]) }}" class="text-blue-800 underline mt-2 inline-block">
                Clique aqui para criar um novo agendamento
            </a>
        </div>
        @endif
    </div>

    <div class="mt-6 flex justify-end">
        <a href="{{ route('clients.index') }}" class="bg-gray-500 text-white py-2 px-4 rounded hover:bg-gray-600 inline-flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Voltar para Servidores
        </a>
    </div>
</div>
@endsection