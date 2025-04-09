# Exemplos de JavaScript

## calendar.js
```javascript
class Calendar {
    constructor() {
        this.currentDate = new Date();
        this.calendarBody = document.getElementById('calendar-body');
        this.monthYearDisplay = document.getElementById('monthYearDisplay');
        
        this.initializeEventListeners();
        this.renderCalendar();
    }

    initializeEventListeners() {
        document.getElementById('prevMonth').addEventListener('click', () => this.changeMonth(-1));
        document.getElementById('nextMonth').addEventListener('click', () => this.changeMonth(1));
        document.getElementById('hoje').addEventListener('click', () => this.goToToday());
    }

    changeMonth(delta) {
        this.currentDate.setMonth(this.currentDate.getMonth() + delta);
        this.renderCalendar();
        this.fetchAppointments();
    }

    goToToday() {
        this.currentDate = new Date();
        this.renderCalendar();
        this.fetchAppointments();
    }

    renderCalendar() {
        const year = this.currentDate.getFullYear();
        const month = this.currentDate.getMonth();
        
        this.monthYearDisplay.textContent = this.getMonthYearString();
        
        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        
        let html = '';
        let day = 1;
        
        // Primeira semana
        html += '<tr>';
        for (let i = 0; i < firstDay.getDay(); i++) {
            html += '<td class="p-2 border h-24"></td>';
        }
        
        for (let i = firstDay.getDay(); i < 7; i++) {
            html += this.renderDayCell(day++);
        }
        html += '</tr>';
        
        // Semanas restantes
        while (day <= lastDay.getDate()) {
            html += '<tr>';
            for (let i = 0; i < 7 && day <= lastDay.getDate(); i++) {
                html += this.renderDayCell(day++);
            }
            html += '</tr>';
        }
        
        this.calendarBody.innerHTML = html;
    }

    renderDayCell(day) {
        const date = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth(), day);
        const isToday = this.isToday(date);
        const classes = [
            'p-2',
            'border',
            'h-24',
            isToday ? 'bg-blue-50' : '',
            'relative'
        ].join(' ');
        
        return `
            <td class="${classes}" data-date="${date.toISOString().split('T')[0]}">
                <div class="absolute top-0 right-0 p-1 text-sm ${isToday ? 'text-blue-600 font-bold' : 'text-gray-600'}">
                    ${day}
                </div>
                <div class="mt-6 appointments-container"></div>
            </td>
        `;
    }

    isToday(date) {
        const today = new Date();
        return date.getDate() === today.getDate() &&
               date.getMonth() === today.getMonth() &&
               date.getFullYear() === today.getFullYear();
    }

    getMonthYearString() {
        const options = { month: 'long', year: 'numeric' };
        return this.currentDate.toLocaleDateString('pt-BR', options);
    }

    async fetchAppointments() {
        const year = this.currentDate.getFullYear();
        const month = this.currentDate.getMonth() + 1;
        
        try {
            const response = await fetch(`/api/appointments?month=${month}&year=${year}`);
            const appointments = await response.json();
            this.renderAppointments(appointments);
        } catch (error) {
            console.error('Erro ao buscar agendamentos:', error);
        }
    }

    renderAppointments(appointments) {
        appointments.forEach(appointment => {
            const date = new Date(appointment.date);
            const cell = this.calendarBody.querySelector(`[data-date="${date.toISOString().split('T')[0]}"]`);
            if (cell) {
                const container = cell.querySelector('.appointments-container');
                const appointmentElement = document.createElement('div');
                appointmentElement.className = 'text-xs p-1 mb-1 bg-blue-100 rounded';
                appointmentElement.textContent = `${appointment.time} - ${appointment.patient_name}`;
                container.appendChild(appointmentElement);
            }
        });
    }
}

// Inicializar o calendário quando o DOM estiver carregado
document.addEventListener('DOMContentLoaded', () => {
    new Calendar();
});
```

## sidebar.js
```javascript
class Sidebar {
    constructor() {
        this.sidebar = document.getElementById('sidebar-consultas');
        this.fetchConsultas();
    }

    async fetchConsultas() {
        try {
            const response = await fetch('/api/consultas');
            const consultas = await response.json();
            this.renderConsultas(consultas);
        } catch (error) {
            console.error('Erro ao buscar consultas:', error);
        }
    }

    renderConsultas(consultas) {
        let html = '';
        consultas.forEach(consulta => {
            html += `
                <div class="p-3 border-b hover:bg-gray-50 cursor-pointer">
                    <div class="flex justify-between items-center">
                        <span class="font-medium">${consulta.patient_name}</span>
                        <span class="text-sm text-gray-500">${consulta.time}</span>
                    </div>
                    <div class="text-sm text-gray-600">${consulta.doctor}</div>
                </div>
            `;
        });
        this.sidebar.innerHTML = html;
    }
}

// Inicializar a sidebar quando o DOM estiver carregado
document.addEventListener('DOMContentLoaded', () => {
    new Sidebar();
});
``` 