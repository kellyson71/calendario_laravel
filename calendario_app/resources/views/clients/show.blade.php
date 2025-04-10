@extends('layouts.app')

@section('title', 'Detalhes do Cliente')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-semibold text-gray-800">Ficha do Cliente</h2>
        <div class="flex space-x-2">
            <a href="{{ route('clients.print_form', $client->id) }}" class="bg-purple-600 text-white py-2 px-4 rounded hover:bg-purple-700 inline-flex items-center" target="_blank">
                <i class="fas fa-print mr-2"></i> Imprimir Ficha
            </a>
            <a href="{{ route('clients.edit', $client->id) }}" class="bg-indigo-600 text-white py-2 px-4 rounded hover:bg-indigo-700 inline-flex items-center">
                <i class="fas fa-edit mr-2"></i> Editar
            </a>
            <a href="{{ route('clients.index') }}" class="bg-gray-500 text-white py-2 px-4 rounded hover:bg-gray-600 inline-flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Voltar
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-1">
            <div class="bg-blue-50 p-4 rounded-lg">
                <div class="flex items-center mb-4">
                    <div class="h-20 w-20 bg-blue-100 rounded-full flex items-center justify-center">
                        <span class="text-blue-800 font-bold text-3xl">{{ strtoupper(substr($client->name, 0, 1)) }}</span>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-xl font-semibold text-gray-800">{{ $client->name }}</h3>
                        @if($client->birth_date)
                        <p class="text-gray-600">
                            {{ $client->birth_date->format('d/m/Y') }}
                            ({{ $client->birth_date->age }} anos)
                        </p>
                        @endif
                    </div>
                </div>

                <div class="mt-4 space-y-2">
                    @if($client->phone)
                    <div class="flex items-center">
                        <i class="fas fa-phone text-blue-800 w-6"></i>
                        <span class="ml-2">{{ $client->phone }}</span>
                    </div>
                    @endif

                    @if($client->email)
                    <div class="flex items-center">
                        <i class="fas fa-envelope text-blue-800 w-6"></i>
                        <span class="ml-2">{{ $client->email }}</span>
                    </div>
                    @endif

                    @if($client->gender)
                    <div class="flex items-center">
                        <i class="fas fa-venus-mars text-blue-800 w-6"></i>
                        <span class="ml-2">
                            @if($client->gender == 'male')
                            Masculino
                            @elseif($client->gender == 'female')
                            Feminino
                            @else
                            Outro
                            @endif
                        </span>
                    </div>
                    @endif

                    @if($client->address)
                    <div class="flex items-start">
                        <i class="fas fa-map-marker-alt text-blue-800 w-6 mt-1"></i>
                        <span class="ml-2">{{ $client->address }}</span>
                    </div>
                    @endif
                </div>

                <div class="mt-6">
                    <h4 class="font-medium text-gray-700 mb-2">Observações:</h4>
                    <div class="bg-white p-3 rounded border border-gray-200">
                        <p class="text-gray-600">{{ $client->notes ?? 'Nenhuma observação registrada.' }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('appointments.create', ['client_id' => $client->id]) }}" class="w-full bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700 inline-flex items-center justify-center">
                    <i class="fas fa-calendar-plus mr-2"></i> Novo Agendamento
                </a>
            </div>
        </div>

        <div class="md:col-span-2">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Últimos Agendamentos</h3>

            @if(count($appointments) > 0)
            <div class="space-y-4 mb-4">
                @foreach($appointments->take(5) as $appointment)
                <div class="border-l-4 {{ $appointment->date->isPast() ? 'border-gray-400' : 'border-blue-500' }} pl-4 py-3 bg-gray-50 rounded-r">
                    <div class="flex justify-between items-start">
                        <div>
                            <h4 class="font-semibold text-gray-800">{{ $appointment->title }}</h4>
                            <p class="text-sm text-gray-600">
                                {{ $appointment->date->format('d/m/Y') }} às
                                {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}
                                @if($appointment->end_time)
                                - {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}
                                @endif
                            </p>
                            <p class="text-sm text-gray-600 mt-1">
                                {{ Str::limit($appointment->description, 100) }}
                            </p>
                        </div>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            @if($appointment->status == 'scheduled') bg-green-100 text-green-800 
                            @elseif($appointment->status == 'completed') bg-blue-100 text-blue-800 
                            @elseif($appointment->status == 'cancelled') bg-red-100 text-red-800 
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst($appointment->status) }}
                        </span>
                    </div>
                    <div class="mt-2 flex space-x-2">
                        <a href="{{ route('appointments.show', $appointment->id) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                            <i class="fas fa-eye mr-1"></i> Ver detalhes
                        </a>
                        <a href="{{ route('appointments.edit', $appointment->id) }}" class="text-indigo-600 hover:text-indigo-800 text-sm">
                            <i class="fas fa-edit mr-1"></i> Editar
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            @if(count($appointments) > 5)
            <div class="mt-4 text-center">
                <a href="{{ route('clients.history', $client->id) }}" class="text-blue-600 hover:text-blue-800 inline-flex items-center">
                    <i class="fas fa-history mr-1"></i> Ver histórico completo ({{ count($appointments) }} agendamentos)
                </a>
            </div>
            @endif
            @else
            <div class="bg-blue-50 py-4 px-6 rounded text-center">
                <p class="text-blue-600">Nenhum agendamento registrado para este cliente.</p>
                <a href="{{ route('appointments.create', ['client_id' => $client->id]) }}" class="text-blue-800 underline mt-2 inline-block">
                    Clique aqui para criar um novo agendamento
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection