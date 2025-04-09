@extends('layouts.app')

@section('title', 'Agendamentos')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-semibold text-gray-800">Todos os Agendamentos</h2>
        <a href="{{ route('appointments.create') }}" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 inline-flex items-center">
            <i class="fas fa-plus mr-2"></i> Novo Agendamento
        </a>
    </div>

    @if(count($appointments) > 0)
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200">
            <thead>
                <tr>
                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                        Título
                    </th>
                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                        Data
                    </th>
                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                        Horário
                    </th>
                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                        Cliente
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
                        <div class="text-sm leading-5 font-medium text-gray-900">{{ $appointment->title }}</div>
                        <div class="text-sm leading-5 text-gray-500">{{ Str::limit($appointment->description, 50) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                        <div class="text-sm leading-5 text-gray-900">{{ $appointment->date->format('d/m/Y') }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                        <div class="text-sm leading-5 text-gray-900">
                            {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}
                            @if($appointment->end_time)
                            - {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                        <div class="text-sm leading-5 text-gray-900">{{ $appointment->client_name ?? '-' }}</div>
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
                    <td class="px-6 py-4 whitespace-no-wrap text-right border-b border-gray-200 text-sm leading-5 font-medium">
                        <a href="{{ route('appointments.show', $appointment->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('appointments.edit', $appointment->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('appointments.destroy', $appointment->id) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Tem certeza que deseja excluir este agendamento?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="bg-blue-50 py-4 px-6 rounded text-center">
        <p class="text-blue-600">Nenhum agendamento encontrado. <a href="{{ route('appointments.create') }}" class="text-blue-800 underline">Clique aqui</a> para criar um novo.</p>
    </div>
    @endif
</div>
@endsection