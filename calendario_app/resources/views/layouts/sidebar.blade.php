<div class="sidebar bg-blue-800 text-white shadow">
    <div class="px-4 py-5 flex flex-col items-center">
        <img src="{{ asset('img/logo.png') }}" alt="Logo da Prefeitura" class="h-16 mb-4">
        <h2 class="text-lg font-semibold">Sistema de Agendamento</h2>
    </div>

    <nav class="mt-5">
        <ul class="space-y-2 px-4">
            <li>
                <a href="{{ route('dashboard') }}" class="flex items-center py-2 px-4 rounded hover:bg-blue-700 {{ request()->routeIs('dashboard') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-tachometer-alt w-6"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('calendar.index') }}" class="flex items-center py-2 px-4 rounded hover:bg-blue-700 {{ request()->routeIs('calendar.index') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-calendar w-6"></i>
                    <span>Calendário</span>
                </a>
            </li>
            <li>
                <a href="{{ route('appointments.index') }}" class="flex items-center py-2 px-4 rounded hover:bg-blue-700 {{ request()->routeIs('appointments.index') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-list w-6"></i>
                    <span>Agendamentos</span>
                </a>
            </li>
            <li>
                <a href="{{ route('appointments.create') }}" class="flex items-center py-2 px-4 rounded hover:bg-blue-700 {{ request()->routeIs('appointments.create') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-plus w-6"></i>
                    <span>Novo Agendamento</span>
                </a>
            </li>
            <li>
                <a href="{{ route('clients.index') }}" class="flex items-center py-2 px-4 rounded hover:bg-blue-700 {{ request()->routeIs('clients.*') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-users w-6"></i>
                    <span>Clientes</span>
                </a>
            </li>
        </ul>
    </nav>

    <div class="mt-8 px-6 pb-6">
        <h3 class="text-sm font-semibold uppercase text-blue-300 mb-2">Agendamentos Recentes</h3>
        <ul class="text-sm space-y-2">
            @php
            $recentAppointments = \App\Models\Appointment::where('user_id', auth()->id())
            ->where('date', '>=', \Carbon\Carbon::today()->toDateString())
            ->orderBy('date')
            ->orderBy('start_time')
            ->take(5)
            ->get();
            @endphp

            @forelse($recentAppointments as $appointment)
            <li class="bg-blue-700 p-2 rounded">
                <div class="font-semibold">{{ $appointment->title }}</div>
                <div class="text-xs text-blue-300">
                    {{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }} às
                    {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}
                </div>
            </li>
            @empty
            <li class="text-blue-300">Nenhum agendamento recente.</li>
            @endforelse
        </ul>
    </div>
</div>