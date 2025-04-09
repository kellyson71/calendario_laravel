@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Bem-vindo, {{ Auth::user()->name }}!</h2>
    <p class="text-gray-600 mb-4">Bem-vindo ao sistema de agendamento. Abaixo estão seus próximos agendamentos.</p>

    <div class="mt-4">
        <a href="{{ route('appointments.create') }}" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 inline-flex items-center">
            <i class="fas fa-plus mr-2"></i> Novo Agendamento
        </a>
        <a href="{{ route('calendar.index') }}" class="bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700 inline-flex items-center ml-2">
            <i class="fas fa-calendar mr-2"></i> Ver Calendário
        </a>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">
            <i class="fas fa-calendar-day text-blue-600 mr-2"></i> Agendamentos Hoje
        </h3>

        @php
        $todayAppointments = \App\Models\Appointment::where('user_id', auth()->id())
        ->where('date', \Carbon\Carbon::today()->toDateString())
        ->orderBy('start_time')
        ->get();
        @endphp

        @if($todayAppointments->count() > 0)
        <div class="space-y-3">
            @foreach($todayAppointments as $appointment)
            <div class="border-l-4 border-blue-500 pl-4 py-2">
                <div class="font-semibold text-gray-800">{{ $appointment->title }}</div>
                <div class="text-sm text-gray-600">
                    Horário: {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}
                    @if($appointment->end_time)
                    - {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}
                    @endif
                </div>
                @if($appointment->client_name)
                <div class="text-sm text-gray-600">Cliente: {{ $appointment->client_name }}</div>
                @endif
                <div class="mt-1">
                    <a href="{{ route('appointments.show', $appointment->id) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                        <i class="fas fa-eye mr-1"></i> Ver
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-gray-600">Nenhum agendamento para hoje.</p>
        @endif
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">
            <i class="fas fa-calendar-week text-green-600 mr-2"></i> Próximos Agendamentos
        </h3>

        @if($upcomingAppointments->count() > 0)
        <div class="space-y-3">
            @foreach($upcomingAppointments as $appointment)
            <div class="border-l-4 border-green-500 pl-4 py-2">
                <div class="font-semibold text-gray-800">{{ $appointment->title }}</div>
                <div class="text-sm text-gray-600">
                    Data: {{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}
                </div>
                <div class="text-sm text-gray-600">
                    Horário: {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}
                    @if($appointment->end_time)
                    - {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}
                    @endif
                </div>
                @if($appointment->client_name)
                <div class="text-sm text-gray-600">Cliente: {{ $appointment->client_name }}</div>
                @endif
                <div class="mt-1">
                    <a href="{{ route('appointments.show', $appointment->id) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                        <i class="fas fa-eye mr-1"></i> Ver
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-gray-600">Nenhum agendamento próximo.</p>
        @endif
    </div>
</div>
@endsection