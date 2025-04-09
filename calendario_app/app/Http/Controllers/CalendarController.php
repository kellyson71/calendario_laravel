<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);

        $date = Carbon::createFromDate($year, $month, 1);
        $daysInMonth = $date->daysInMonth;
        $firstDayOfWeek = $date->copy()->startOfMonth()->dayOfWeek;

        // Ajuste para o calendário começar no domingo (0)
        $firstDayOfWeek = $firstDayOfWeek === 0 ? 7 : $firstDayOfWeek;

        // Buscar todos os agendamentos do usuário para este mês
        $appointments = Appointment::where('user_id', auth()->id())
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get();

        // Agrupar agendamentos por dia
        $groupedAppointments = $appointments->groupBy(function ($appointment) {
            return Carbon::parse($appointment->date)->day;
        });

        // Preparando os eventos para o calendário no formato JSON
        $calendarEvents = [];
        foreach ($appointments as $appointment) {
            $calendarEvents[] = [
                'id' => $appointment->id,
                'title' => $appointment->title,
                'start' => $appointment->date->format('Y-m-d') . 'T' . Carbon::parse($appointment->start_time)->format('H:i:s'),
                'end' => $appointment->end_time ? $appointment->date->format('Y-m-d') . 'T' . Carbon::parse($appointment->end_time)->format('H:i:s') : null,
                'url' => route('appointments.show', $appointment->id),
                'backgroundColor' => '#3788d8',
                'borderColor' => '#3788d8'
            ];
        }

        // Converter os dados para JSON para uso no JavaScript
        $calendarEventsJson = json_encode($calendarEvents);

        return view('calendar.index', compact(
            'month',
            'year',
            'daysInMonth',
            'firstDayOfWeek',
            'groupedAppointments',
            'date',
            'calendarEventsJson'
        ));
    }

    public function dashboard()
    {
        // Buscar agendamentos recentes para exibir na dashboard
        $upcomingAppointments = Appointment::where('user_id', auth()->id())
            ->where('date', '>=', Carbon::today()->toDateString())
            ->orderBy('date')
            ->orderBy('start_time')
            ->take(5)
            ->get();

        return view('dashboard', compact('upcomingAppointments'));
    }
}
