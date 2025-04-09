@extends('layouts.app')

@section('title', 'Calendário')

@section('styles')
<style>
    /* Estilos para animações */
    .fc-day:hover {
        background-color: rgba(59, 130, 246, 0.1) !important;
        transition: background-color 0.3s ease;
    }

    .fc-button {
        transition: transform 0.2s ease, background-color 0.3s ease !important;
    }

    .fc-button:hover {
        transform: translateY(-2px);
    }

    .fc-button:active {
        transform: translateY(1px);
    }

    #calendar {
        transition: box-shadow 0.3s ease;
    }

    #calendar:hover {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
</style>
@endsection

@section('content')
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-semibold text-gray-800">Calendário de Agendamentos</h2>
        <a href="{{ route('appointments.create') }}" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 inline-flex items-center">
            <i class="fas fa-plus mr-2"></i> Novo Agendamento
        </a>
    </div>

    <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4">
        <div class="w-full md:w-9/12">
            <div id="calendar" class="bg-white p-4 rounded shadow-sm"></div>
        </div>

        <div class="w-full md:w-3/12">
            <div class="bg-blue-50 rounded p-4 mb-4">
                <h3 class="font-semibold text-lg text-blue-800 mb-3">Detalhes do Agendamento</h3>
                <div id="appointmentDetails" class="text-sm">
                    <p class="text-gray-600">Selecione uma data no calendário para ver os agendamentos.</p>
                </div>
            </div>

            <div id="createAppointmentPanel" class="bg-green-50 rounded p-4 hidden">
                <h3 class="font-semibold text-lg text-green-800 mb-3">Novo Agendamento</h3>
                <p class="text-sm text-green-700 mb-3">Criar agendamento para: <span id="selectedDate" class="font-semibold"></span></p>
                <a href="#" id="createAppointmentBtn" class="w-full bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700 inline-flex items-center justify-center">
                    <i class="fas fa-plus mr-2"></i> Criar Agendamento
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar');

        // Usar o JSON diretamente do controlador
        const calendarEvents = JSON.parse('@php echo addslashes($calendarEventsJson); @endphp');

        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'pt-br',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: calendarEvents,
            dateClick: function(info) {
                const clickedDate = info.dateStr;

                // Adicionar animação ao elemento clicado
                const dayEl = info.dayEl;
                dayEl.classList.add('bg-blue-100');
                dayEl.style.transition = 'transform 0.3s ease, background-color 0.3s ease';
                dayEl.style.transform = 'scale(0.95)';

                setTimeout(() => {
                    dayEl.style.transform = 'scale(1)';
                    setTimeout(() => {
                        dayEl.classList.remove('bg-blue-100');
                    }, 300);
                }, 300);

                showCreateAppointmentPanel(clickedDate);
                fetchAppointmentsForDate(clickedDate);
            }
        });

        calendar.render();

        // Função para mostrar o painel de criação de agendamento
        function showCreateAppointmentPanel(date) {
            const formattedDate = new Date(date).toLocaleDateString('pt-BR');
            const selectedDateEl = document.getElementById('selectedDate');
            const createPanel = document.getElementById('createAppointmentPanel');

            selectedDateEl.textContent = formattedDate;

            // Adicionar animação ao painel
            createPanel.style.opacity = '0';
            createPanel.style.transform = 'translateY(20px)';
            createPanel.classList.remove('hidden');
            createPanel.style.transition = 'opacity 0.3s ease, transform 0.3s ease';

            setTimeout(() => {
                createPanel.style.opacity = '1';
                createPanel.style.transform = 'translateY(0)';
            }, 10);

            // Atualiza o link para criar agendamento com a data selecionada
            const createBtn = document.getElementById('createAppointmentBtn');
            createBtn.href = "{{ route('appointments.create') }}?date=" + date;
        }

        function fetchAppointmentsForDate(date) {
            fetch(`/appointments/by-date?date=${date}`)
                .then(response => response.json())
                .then(data => {
                    displayAppointments(data, date);
                })
                .catch(error => {
                    console.error('Erro ao buscar agendamentos:', error);
                });
        }

        function displayAppointments(appointments, date) {
            const detailsContainer = document.getElementById('appointmentDetails');
            const formattedDate = new Date(date).toLocaleDateString('pt-BR');

            let html = `<h4 class="font-medium text-blue-800 mb-2">Agendamentos para ${formattedDate}</h4>`;

            if (appointments.length > 0) {
                html += '<div class="space-y-3">';
                appointments.forEach((appointment, index) => {
                    html += `
                        <div class="border-l-3 border-blue-500 pl-3 py-1 appointment-item" 
                             style="opacity: 0; transform: translateX(20px); transition: opacity 0.3s ease, transform 0.3s ease; transition-delay: ${index * 0.1}s;">
                            <div class="font-medium">${appointment.title}</div>
                            <div class="text-xs text-gray-600">
                                Horário: ${new Date('2000-01-01T' + appointment.start_time).toLocaleTimeString('pt-BR', {hour: '2-digit', minute:'2-digit'})}
                                ${appointment.end_time ? ' - ' + new Date('2000-01-01T' + appointment.end_time).toLocaleTimeString('pt-BR', {hour: '2-digit', minute:'2-digit'}) : ''}
                            </div>
                            ${appointment.client_name ? `<div class="text-xs text-gray-600">Cliente: ${appointment.client_name}</div>` : ''}
                            <div class="mt-1">
                                <a href="/appointments/${appointment.id}" class="text-blue-600 hover:text-blue-800 text-xs">
                                    <i class="fas fa-eye mr-1"></i> Ver detalhes
                                </a>
                            </div>
                        </div>
                    `;
                });
                html += '</div>';
            } else {
                html += '<p class="text-gray-600">Nenhum agendamento para esta data.</p>';
            }

            detailsContainer.innerHTML = html;

            // Animar a entrada dos itens de agendamento
            setTimeout(() => {
                const appointmentItems = document.querySelectorAll('.appointment-item');
                appointmentItems.forEach(item => {
                    item.style.opacity = '1';
                    item.style.transform = 'translateX(0)';
                });
            }, 10);
        }
    });
</script>
@endsection