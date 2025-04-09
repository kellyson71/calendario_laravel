# Exemplos de Serviços Personalizados

## CalendarService.php
```php
<?php

namespace App\Services;

use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class CalendarService
{
    public function getAvailableSlots(string $date, int $doctorId): Collection
    {
        $startTime = Carbon::parse($date)->startOfDay();
        $endTime = Carbon::parse($date)->endOfDay();
        
        $bookedSlots = Appointment::where('doctor_id', $doctorId)
            ->whereBetween('start_time', [$startTime, $endTime])
            ->pluck('start_time')
            ->map(fn ($time) => Carbon::parse($time)->format('H:i'))
            ->toArray();

        $availableSlots = collect();
        $currentTime = $startTime->copy()->setHour(8)->setMinute(0);

        while ($currentTime <= $endTime->copy()->setHour(17)->setMinute(0)) {
            $timeSlot = $currentTime->format('H:i');
            if (!in_array($timeSlot, $bookedSlots)) {
                $availableSlots->push([
                    'time' => $timeSlot,
                    'is_available' => true
                ]);
            }
            $currentTime->addMinutes(30);
        }

        return $availableSlots;
    }

    public function isSlotAvailable(string $date, string $time, int $doctorId): bool
    {
        $appointmentTime = Carbon::parse("$date $time");
        
        return !Appointment::where('doctor_id', $doctorId)
            ->where('start_time', $appointmentTime)
            ->exists();
    }

    public function getDoctorSchedule(int $doctorId, string $startDate, string $endDate): Collection
    {
        return Appointment::where('doctor_id', $doctorId)
            ->whereBetween('start_time', [$startDate, $endDate])
            ->with('patient')
            ->orderBy('start_time')
            ->get();
    }
}
```

## NotificationService.php
```php
<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\User;
use App\Notifications\AppointmentReminder;
use App\Notifications\AppointmentConfirmation;
use App\Notifications\AppointmentCancellation;
use Illuminate\Support\Facades\Notification;

class NotificationService
{
    public function sendAppointmentConfirmation(Appointment $appointment): void
    {
        $users = [
            $appointment->patient,
            $appointment->doctor
        ];

        Notification::send($users, new AppointmentConfirmation($appointment));
    }

    public function sendAppointmentReminder(Appointment $appointment): void
    {
        $users = [
            $appointment->patient,
            $appointment->doctor
        ];

        Notification::send($users, new AppointmentReminder($appointment));
    }

    public function sendAppointmentCancellation(Appointment $appointment, string $reason): void
    {
        $users = [
            $appointment->patient,
            $appointment->doctor
        ];

        Notification::send($users, new AppointmentCancellation($appointment, $reason));
    }

    public function sendDailyReminders(): void
    {
        $tomorrow = now()->addDay()->format('Y-m-d');
        
        $appointments = Appointment::whereDate('start_time', $tomorrow)
            ->where('status', 'scheduled')
            ->with(['patient', 'doctor'])
            ->get();

        foreach ($appointments as $appointment) {
            $this->sendAppointmentReminder($appointment);
        }
    }
}
```

## PDFService.php
```php
<?php

namespace App\Services;

use App\Models\Appointment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class PDFService
{
    public function generateAppointmentPDF(Appointment $appointment): string
    {
        $pdf = PDF::loadView('pdf.appointment', [
            'appointment' => $appointment
        ]);

        $filename = "appointments/{$appointment->id}/appointment.pdf";
        Storage::put($filename, $pdf->output());

        return $filename;
    }

    public function generateDailyReport(string $date): string
    {
        $appointments = Appointment::whereDate('start_time', $date)
            ->with(['patient', 'doctor'])
            ->get();

        $pdf = PDF::loadView('pdf.daily-report', [
            'appointments' => $appointments,
            'date' => $date
        ]);

        $filename = "reports/daily/{$date}.pdf";
        Storage::put($filename, $pdf->output());

        return $filename;
    }

    public function cleanOldPDFs(int $days = 30): void
    {
        $oldDate = now()->subDays($days)->format('Y-m-d');
        
        $directories = [
            'appointments/*',
            'reports/daily/*'
        ];

        foreach ($directories as $directory) {
            $files = Storage::files($directory);
            foreach ($files as $file) {
                if (Storage::lastModified($file) < strtotime($oldDate)) {
                    Storage::delete($file);
                }
            }
        }
    }
}
``` 